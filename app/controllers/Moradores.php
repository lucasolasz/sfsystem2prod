<?php

class Moradores extends Controller
{

    private $model;

    private $casaModel;

    private $modelVeiculo;

    private $usuarioModel;

    //Construtor do model do Usuário que fará o acesso ao banco
    public function __construct()
    {
        $permissoes = [ADMINISTRADOR, MORADOR, SINDICO];
        $this->verificaSeEstaLogadoETemPermissao($permissoes);
        $this->model = $this->model("MoradorModel");
        $this->casaModel = $this->model("CasaModel");
        $this->modelVeiculo = $this->model("VeiculoModel");
        $this->usuarioModel = $this->model("UsuarioModel");
    }

    public function visualizarMoradores()
    {
        $moradores = $this->model->recuperarTodosOsMoradoresCadastrados();

        $dados = [
            'moradores' => $moradores
        ];

        //Retorna para a view
        $this->view('morador/visualizarTodos', $dados);
    }

    public function cadastrar()
    {
        $listaTiposVeiculos = $this->modelVeiculo->recuperarTiposVeiculos();
        $listaCoresVeiculos = $this->modelVeiculo->recuperarCoresVeiculos();
        $casas = $this->casaModel->reuperarTodasCasas();

        $formulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($formulario)) {

            $listaVeiculosCadastradosForm = VeiculosUtil::recuperarVeiculosFormulario($formulario);

            $dados = [
                'txtNomeProprietario' => trim($formulario['txtNomeProprietario']),
                'txtDocumentoProprietario' => trim($formulario['txtDocumentoProprietario']),
                'dateNascimentoProprietario' => $formulario['dateNascimentoProprietario'],
                'txtEmailProprietario' => trim($formulario['txtEmailProprietario']),
                'txtTelefoneUmProprietario' => trim($formulario['txtTelefoneUmProprietario']),
                'txtTelefoneDoisProprietario' => trim($formulario['txtTelefoneDoisProprietario']),
                'txtTelefoneEmergenciaProprietario' => trim($formulario['txtTelefoneEmergenciaProprietario']),
                'cboCasa' => $formulario['cboCasa'],
                'txtDetalhes' => $formulario['txtDetalhes'],

                'chkLocatario' => isset($formulario['chkLocatario']) ? trim($formulario['chkLocatario']) : "N",
                'txtNomeLocatario' => trim($formulario['txtNomeLocatario']),
                'txtDocumentoLocatario' => $formulario['txtDocumentoLocatario'],
                'dateNascimentoLocatario' => trim($formulario['dateNascimentoLocatario']) == "" ? null : trim($formulario['dateNascimentoLocatario']),
                'txtEmailLocatario' => trim($formulario['txtEmailLocatario']),
                'txtTelefoneUmLocatario' => trim($formulario['txtTelefoneUmLocatario']),
                'txtTelefoneDoisLocatario' => trim($formulario['txtTelefoneDoisLocatario']),

                'qtdPets' => isset($formulario['qtdPets']) != "" ? intval($formulario['qtdPets']) : 0,
                'chkPossuiPets' => isset($formulario['chkPossuiPets']) ? trim($formulario['chkPossuiPets']) : "N",

                'qtdAdesivos' => isset($formulario['qtdAdesivos']) != "" ? intval($formulario['qtdAdesivos']) : 0,
                'chkRecebeuAdesivo' => isset($formulario['chkRecebeuAdesivo']) ? trim($formulario['chkRecebeuAdesivo']) : "N",


                'nomeProprietario_erro' => '',
                'documentoProprietario_erro' => '',
                'dataNascimentoProprieratio_erro' => '',
                'emailProprietario_erro' => '',
                'telefone_um_proprietario_erro' => '',
                'cboCasa_erro' => '',
                'nomeLocatario_erro' => '',
                'documentoLocatario_erro' => '',
                'dataNascimentoLocatario_erro' => '',
                'emailLocatario_erro' => '',
                'telefone_um_locatario_erro' => '',
                'quantidade_pets_erro' => '',
                'quantidade_adesivos_erro' => '',


                'casas' => $casas,
                'listaTiposVeiculos' => $listaTiposVeiculos,
                'listaCoresVeiculos' => $listaCoresVeiculos
            ];

            if ($dados['chkRecebeuAdesivo'] == 'N') {
                $dados['qtdAdesivos'] = 0;
            }

            if ($dados['chkPossuiPets'] == 'N') {
                $dados['qtdPets'] = 0;
            }

            if (empty($formulario['txtNomeProprietario'])) {
                $dados['nomeProprietario_erro'] = "Preencha o Nome";
            } elseif (empty($formulario["txtDocumentoProprietario"])) {
                $dados["documentoProprietario_erro"] = "Preencha o documento";
            } elseif (empty($formulario['dateNascimentoProprietario'])) {
                $dados["dataNascimentoProprieratio_erro"] = "Escolha uma data";
            } elseif (empty($formulario['txtEmailProprietario'])) {
                $dados["emailProprietario_erro"] = "Preencha um email";
            } elseif (empty($formulario['txtTelefoneUmProprietario'])) {
                $dados["telefone_um_proprietario_erro"] = "Preencha um telefone";
            } elseif (empty($formulario['cboCasa'])) {
                $dados["cboCasa_erro"] = "Escolha uma casa";
            } elseif (($dados['chkPossuiPets'] == 'S' && $dados['qtdPets'] == 0)) {
                $dados["quantidade_pets_erro"] = "Digite a quantidade de pets valida *";
            } elseif (($dados['chkRecebeuAdesivo'] == 'S' && $dados['qtdAdesivos'] == 0)) {
                $dados["quantidade_adesivos_erro"] = "Digite a quantidade de adesivos valida *";
            } else {

                $idRetorno = $this->executarQuerysCadastroMorador($listaVeiculosCadastradosForm, $dados);

                if (!empty($idRetorno)) {
                    Alertas::mensagem('morador', 'Morador cadastrado com sucesso');
                    Redirecionamento::redirecionar('Moradores/visualizarMoradores');
                } else {
                    Alertas::mensagem('morador', 'Algo deu errado. Se o problema persistir, contate o administrador do sistema.', 'alert alert-danger');
                    Redirecionamento::redirecionar('Moradores/visualizarMoradores');
                }
            }
        } else {

            $dados = [
                'txtNomeProprietario' => '',
                'txtDocumentoProprietario' => '',
                'dateNascimentoProprietario' => '',
                'txtEmailProprietario' => '',
                'txtTelefoneUmProprietario' => '',
                'txtTelefoneDoisProprietario' => '',
                'txtTelefoneEmergenciaProprietario' => '',
                'txtDetalhes' => '',

                'chkLocatario' => '',
                'txtNomeLocatario' => '',
                'txtDocumentoLocatario' => '',
                'dateNascimentoLocatario' => '',
                'txtEmailLocatario' => '',
                'txtTelefoneUmLocatario' => '',
                'txtTelefoneDoisLocatario' => '',
                'cboCasa' => '',
                'qtdPets' => '',
                'chkPossuiPets' => '',
                'qtdAdesivos' => '',
                'chkRecebeuAdesivo' => '',
                



                'nomeProprietario_erro' => '',
                'documentoProprietario_erro' => '',
                'dataNascimentoProprieratio_erro' => '',
                'emailProprietario_erro' => '',
                'telefone_um_proprietario_erro' => '',
                'cboCasa_erro' => '',
                'quantidade_pets_erro' => '',
                'quantidade_adesivos_erro' => '',

                'nomeLocatario_erro' => '',
                'documentoLocatario_erro' => '',
                'dataNascimentoLocatario_erro' => '',
                'emailLocatario_erro' => '',
                'telefone_um_locatario_erro' => '',


                'casas' => $casas,
                'listaTiposVeiculos' => $listaTiposVeiculos,
                'listaCoresVeiculos' => $listaCoresVeiculos

            ];
        }

        $this->view('morador/cadastrar', $dados);
    }

    private function executarQuerysCadastroMorador($listaVeiculosCadastradosForm, $dados)
    {
        $idMorador = null;

        if ($this->model->armazenarMorador($dados)) {

            $idMorador = $this->model->ultimoIdInserido();

            if (!empty($listaVeiculosCadastradosForm)) {
                $this->modelVeiculo->armazenarListaCarrosMorador($listaVeiculosCadastradosForm, $idMorador);
            }

            return $idMorador;
        }

        return $idMorador;
    }

    private function executarQuerysCadastroMoradorPorIdUsuario($listaVeiculosCadastradosForm, $dados)
    {
        $idMorador = null;

        if ($this->model->armazenarMoradorPorIdUsuario($dados)) {

            $idMorador = $this->model->ultimoIdInserido();

            if (!empty($listaVeiculosCadastradosForm)) {
                $this->modelVeiculo->armazenarListaCarrosMorador($listaVeiculosCadastradosForm, $idMorador);
            }

            return $idMorador;
        }

        return $idMorador;
    }

    public function editarMorador($id)
    {

        $morador = $this->model->retornarMoradorPorId($id);
        $veiculosMorador = $this->modelVeiculo->recuperarListaTodosOsVeiculosPorIdMorador($id);
        $listaTiposVeiculos = $this->modelVeiculo->recuperarTiposVeiculos();
        $listaCoresVeiculos = $this->modelVeiculo->recuperarCoresVeiculos();
        $casas = $this->casaModel->reuperarTodasCasas();

        //Evita que codigos maliciosos sejam enviados pelos campos
        $formulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($formulario)) {

            $listaVeiculosCadastradosForm = VeiculosUtil::recuperarVeiculosFormulario($formulario);

            $dados = [
                'txtNomeProprietario' => trim($formulario['txtNomeProprietario']),
                'txtDocumentoProprietario' => trim($formulario['txtDocumentoProprietario']),
                'dateNascimentoProprietario' => $formulario['dateNascimentoProprietario'],
                'txtEmailProprietario' => trim($formulario['txtEmailProprietario']),
                'txtTelefoneUmProprietario' => trim($formulario['txtTelefoneUmProprietario']),
                'txtTelefoneDoisProprietario' => trim($formulario['txtTelefoneDoisProprietario']),
                'txtTelefoneEmergenciaProprietario' => trim($formulario['txtTelefoneEmergenciaProprietario']),
                'cboCasa' => $formulario['cboCasa'],
                'txtDetalhes' => $formulario['txtDetalhes'],

                'chkLocatario' => isset($formulario['chkLocatario']) ? trim($formulario['chkLocatario']) : "N",
                'txtNomeLocatario' => trim($formulario['txtNomeLocatario']),
                'txtDocumentoLocatario' => $formulario['txtDocumentoLocatario'],
                'dateNascimentoLocatario' => trim($formulario['dateNascimentoLocatario']),
                'txtEmailLocatario' => trim($formulario['txtEmailLocatario']),
                'txtTelefoneUmLocatario' => trim($formulario['txtTelefoneUmLocatario']),
                'txtTelefoneDoisLocatario' => trim($formulario['txtTelefoneDoisLocatario']),

                'qtdPets' => isset($formulario['qtdPets']) != "" ? intval($formulario['qtdPets']) : 0,
                'chkPossuiPets' => isset($formulario['chkPossuiPets']) ? trim($formulario['chkPossuiPets']) : "N",

                'qtdAdesivos' => isset($formulario['qtdAdesivos']) != "" ? intval($formulario['qtdAdesivos']) : 0,
                'chkRecebeuAdesivo' => isset($formulario['chkRecebeuAdesivo']) ? trim($formulario['chkRecebeuAdesivo']) : "N",


                'nomeProprietario_erro' => '',
                'documentoProprietario_erro' => '',
                'dataNascimentoProprieratio_erro' => '',
                'emailProprietario_erro' => '',
                'telefone_um_proprietario_erro' => '',
                'cboCasa_erro' => '',
                'nomeLocatario_erro' => '',
                'documentoLocatario_erro' => '',
                'dataNascimentoLocatario_erro' => '',
                'emailLocatario_erro' => '',
                'telefone_um_locatario_erro' => '',
                'quantidade_pets_erro' => '',
                'quantidade_adesivos_erro' => '',


                'casas' => $casas,
                'listaTiposVeiculos' => $listaTiposVeiculos,
                'listaCoresVeiculos' => $listaCoresVeiculos,

                'veiculosMorador' => $veiculosMorador,
                'morador' => $morador,

                'idMorador' => $id
            ];

            if (empty($formulario['txtNomeProprietario'])) {
                $dados['nomeProprietario_erro'] = "Preencha o Nome";
            } elseif (empty($formulario["txtDocumentoProprietario"])) {
                $dados["documentoProprietario_erro"] = "Preencha o documento";
            } elseif (empty($formulario['dateNascimentoProprietario'])) {
                $dados["dataNascimentoProprieratio_erro"] = "Escolha uma data";
            } elseif (empty($formulario['txtEmailProprietario'])) {
                $dados["emailProprietario_erro"] = "Preencha um email";
            } elseif (empty($formulario['txtTelefoneUmProprietario'])) {
                $dados["telefone_um_proprietario_erro"] = "Preencha um telefone";
            } elseif (empty($formulario['cboCasa'])) {
                $dados["cboCasa_erro"] = "Escolha uma casa";
            } elseif (($dados['chkPossuiPets'] == 'S' && $dados['qtdPets'] == 0)) {
                $dados["quantidade_pets_erro"] = "Digite a quantidade de pets valida *";
            } elseif (($dados['chkRecebeuAdesivo'] == 'S' && $dados['qtdAdesivos'] == 0)) {
                $dados["quantidade_adesivos_erro"] = "Digite a quantidade de adesivos valida *";
            }else {

                $dadosAtualizado = $this->verificarSeNaoTemLocatario($dados);

                if ($this->model->atualizarMorador($dadosAtualizado)) {

                    $this->modelVeiculo->editarCarrosMorador($listaVeiculosCadastradosForm, $id);
                }

                Alertas::mensagem('morador', 'Morador atualizado com sucesso');
                Redirecionamento::redirecionar('Moradores/visualizarMoradores');
            }
        } else {

            $dados = [
                'txtNomeProprietario' => '',
                'txtDocumentoProprietario' => '',
                'dateNascimentoProprietario' => '',
                'txtEmailProprietario' => '',
                'txtTelefoneUmProprietario' => '',
                'txtTelefoneDoisProprietario' => '',
                'txtTelefoneEmergenciaProprietario' => '',
                'txtDetalhes' => '',

                'chkLocatario' => '',
                'txtNomeLocatario' => '',
                'txtDocumentoLocatario' => '',
                'dateNascimentoLocatario' => '',
                'txtEmailLocatario' => '',
                'txtTelefoneUmLocatario' => '',
                'txtTelefoneDoisLocatario' => '',
                'cboCasa' => '',
                'qtdPets' => '',
                'chkPossuiPets' => '',
                'qtdAdesivos' => '',
                'chkRecebeuAdesivo' => '',


                'nomeProprietario_erro' => '',
                'documentoProprietario_erro' => '',
                'dataNascimentoProprieratio_erro' => '',
                'emailProprietario_erro' => '',
                'telefone_um_proprietario_erro' => '',
                'cboCasa_erro' => '',
                'quantidade_pets_erro' => '',
                'quantidade_adesivos_erro' => '',

                'nomeLocatario_erro' => '',
                'documentoLocatario_erro' => '',
                'dataNascimentoLocatario_erro' => '',
                'emailLocatario_erro' => '',
                'telefone_um_locatario_erro' => '',


                'casas' => $casas,
                'listaTiposVeiculos' => $listaTiposVeiculos,
                'listaCoresVeiculos' => $listaCoresVeiculos,

                'veiculosMorador' => $veiculosMorador,
                'morador' => $morador,
                'idMorador' => $id

            ];
        }

        $this->view('morador/editar', $dados);
    }

    public function verificarSeNaoTemLocatario($dados)
    {
        if ($dados['chkLocatario'] == 'N') {

            $dados['txtNomeLocatario'] = '';
            $dados['txtDocumentoLocatario'] = '';
            $dados['dateNascimentoLocatario'] = null;
            $dados['txtEmailLocatario'] = '';
            $dados['txtTelefoneUmLocatario'] = '';
            $dados['txtTelefoneDoisLocatario'] = '';

            return $dados;
        }

        return $dados;
    }

    public function deletarMorador($id)
    {
        $this->modelVeiculo->executarQueryDeleteVeiculosPorIdMorador($id);
        $this->model->deletarMorador($id);

        //Para exibir mensagem success , não precisa informar o tipo de classe
        Alertas::mensagem('morador', 'Morador deletado com sucesso');
        Redirecionamento::redirecionar('Moradores/visualizarMoradores/');
    }

    public function deletarMoradorPorIdUsuario($id)
    {
        $this->modelVeiculo->executarQueryDeleteVeiculosPorIdMorador($id);
        $this->model->deletarMorador($id);

        //Para exibir mensagem success , não precisa informar o tipo de classe
        Alertas::mensagem('morador', 'Morador deletado com sucesso');
        Redirecionamento::redirecionar('Moradores/visualizarMoradorPorIdUsuario/' . $_SESSION['id_usuario']);
    }

    public function visualizarMoradorPorIdUsuario($idUsuario)
    {
        $usuario = $this->usuarioModel->lerUsuarioPorIdComCasas($idUsuario);

        if(empty($usuario)){
            Redirecionamento::redirecionar('Moradores/visualizarMoradorPorIdUsuario/' . $_SESSION['id_usuario']);
        }

        $fk_casa = $usuario->fk_casa;

        $morador = $this->model->retornarMoradorCadastradoPorIdUsuarioOuFkCasa($idUsuario, $fk_casa);

        $novoDisabled = !empty($morador) ? "disabled" : "";

        if ($idUsuario != $_SESSION['id_usuario']) {
            Redirecionamento::redirecionar('Moradores/visualizarMoradorPorIdUsuario/' . $_SESSION['id_usuario']);
        }

        $dados = [
            'morador' => $morador,
            'novoDisabled' => $novoDisabled
        ];

        $this->view('morador/visualizarPorId', $dados);
    }

    public function cadastrarMoradorPorIdUsuario($idUsuario)
    {

        $usuario = $this->usuarioModel->lerUsuarioPorIdComCasas($idUsuario);
        $listaTiposVeiculos = $this->modelVeiculo->recuperarTiposVeiculos();
        $listaCoresVeiculos = $this->modelVeiculo->recuperarCoresVeiculos();
        // $casas = $this->casaModel->reuperarTodasCasas();

        if ($idUsuario != $_SESSION['id_usuario']) {
            Redirecionamento::redirecionar('Moradores/visualizarMoradorPorIdUsuario/' . $_SESSION['id_usuario']);
        }

        $formulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($formulario)) {

            $listaVeiculosCadastradosForm = VeiculosUtil::recuperarVeiculosFormulario($formulario);

            $dados = [
                'txtNomeProprietario' => trim($formulario['txtNomeProprietario']),
                'txtDocumentoProprietario' => trim($formulario['txtDocumentoProprietario']),
                'dateNascimentoProprietario' => $formulario['dateNascimentoProprietario'],
                'txtEmailProprietario' => trim($formulario['txtEmailProprietario']),
                'txtTelefoneUmProprietario' => trim($formulario['txtTelefoneUmProprietario']),
                'txtTelefoneDoisProprietario' => trim($formulario['txtTelefoneDoisProprietario']),
                'txtTelefoneEmergenciaProprietario' => trim($formulario['txtTelefoneEmergenciaProprietario']),
                'cboCasa' => $usuario->fk_casa,
                'txtDetalhes' => $formulario['txtDetalhes'],

                'chkLocatario' => isset($formulario['chkLocatario']) ? trim($formulario['chkLocatario']) : "N",
                'txtNomeLocatario' => trim($formulario['txtNomeLocatario']),
                'txtDocumentoLocatario' => $formulario['txtDocumentoLocatario'],
                'dateNascimentoLocatario' => trim($formulario['dateNascimentoLocatario']) == "" ? null : trim($formulario['dateNascimentoLocatario']),
                'txtEmailLocatario' => trim($formulario['txtEmailLocatario']),
                'txtTelefoneUmLocatario' => trim($formulario['txtTelefoneUmLocatario']),
                'txtTelefoneDoisLocatario' => trim($formulario['txtTelefoneDoisLocatario']),

                'qtdPets' => isset($formulario['qtdPets']) != "" ? intval($formulario['qtdPets']) : 0,
                'chkPossuiPets' => isset($formulario['chkPossuiPets']) ? trim($formulario['chkPossuiPets']) : "N",

                'qtdAdesivos' => isset($formulario['qtdAdesivos']) != "" ? intval($formulario['qtdAdesivos']) : 0,
                'chkRecebeuAdesivo' => isset($formulario['chkRecebeuAdesivo']) ? trim($formulario['chkRecebeuAdesivo']) : "N",

                'nomeProprietario_erro' => '',
                'documentoProprietario_erro' => '',
                'dataNascimentoProprieratio_erro' => '',
                'emailProprietario_erro' => '',
                'telefone_um_proprietario_erro' => '',
                // 'cboCasa_erro' => '',
                'nomeLocatario_erro' => '',
                'documentoLocatario_erro' => '',
                'dataNascimentoLocatario_erro' => '',
                'emailLocatario_erro' => '',
                'telefone_um_locatario_erro' => '',
                'quantidade_pets_erro' => '',
                'quantidade_adesivos_erro' => '',
                // 'casas' => $casas,
                'usuario' => $usuario,
                'listaTiposVeiculos' => $listaTiposVeiculos,
                'listaCoresVeiculos' => $listaCoresVeiculos
            ];

            if ($dados['chkRecebeuAdesivo'] == 'N') {
                $dados['qtdAdesivos'] = 0;
            }

            if ($dados['chkPossuiPets'] == 'N') {
                $dados['qtdPets'] = 0;
            }

            if (empty($formulario['txtNomeProprietario'])) {
                $dados['nomeProprietario_erro'] = "Preencha o Nome";
            } elseif (empty($formulario["txtDocumentoProprietario"])) {
                $dados["documentoProprietario_erro"] = "Preencha o documento";
            } elseif (empty($formulario['dateNascimentoProprietario'])) {
                $dados["dataNascimentoProprieratio_erro"] = "Escolha uma data";
            } elseif (empty($formulario['txtEmailProprietario'])) {
                $dados["emailProprietario_erro"] = "Preencha um email";
            } elseif (empty($formulario['txtTelefoneUmProprietario'])) {
                $dados["telefone_um_proprietario_erro"] = "Preencha um telefone";
            } elseif (($dados['chkPossuiPets'] == 'S' && $dados['qtdPets'] == 0)) {
                $dados["quantidade_pets_erro"] = "Digite a quantidade de pets valida *";
            } elseif ($dados['chkLocatario'] === 'S' && empty($dados['txtNomeLocatario'])) {
                $dados["nomeLocatario_erro"] = "Digite o nome do locatário *";
            } elseif ($dados['chkLocatario'] === 'S' && empty($dados['txtDocumentoLocatario'])) {
                $dados["documentoLocatario_erro"] = "Digite o documento do locatário *";
            } elseif ($dados['chkLocatario'] === 'S' && empty($dados['dateNascimentoLocatario'])) {
                $dados["dataNascimentoLocatario_erro"] = "Digite o data nascimento do locatário *";
            } elseif ($dados['chkLocatario'] === 'S' && empty($dados['txtTelefoneUmLocatario'])) {
                $dados["telefone_um_locatario_erro"] = "Digite o telefone do locatário *";
            } elseif (($dados['chkRecebeuAdesivo'] == 'S' && $dados['qtdAdesivos'] == 0)) {
                $dados["quantidade_adesivos_erro"] = "Digite a quantidade de adesivos valida *";
            } else {

                $idRetorno = $this->executarQuerysCadastroMoradorPorIdUsuario($listaVeiculosCadastradosForm, $dados);

                if (!empty($idRetorno)) {
                    Alertas::mensagem('morador', 'Morador cadastrado com sucesso');
                    Redirecionamento::redirecionar('Moradores/visualizarMoradorPorIdUsuario/' . $usuario->id_usuario);
                } else {
                    Alertas::mensagem('morador', 'Algo deu errado. Se o problema persistir, contate o administrador do sistema.', 'alert alert-danger');
                    Redirecionamento::redirecionar('Moradores/visualizarMoradorPorIdUsuario/' . $usuario->id_usuario);
                }
            }
        } else {

            $dados = [
                'txtNomeProprietario' => '',
                'txtDocumentoProprietario' => '',
                'dateNascimentoProprietario' => '',
                'txtEmailProprietario' => '',
                'txtTelefoneUmProprietario' => '',
                'txtTelefoneDoisProprietario' => '',
                'txtTelefoneEmergenciaProprietario' => '',
                'txtDetalhes' => '',

                'chkLocatario' => '',
                'txtNomeLocatario' => '',
                'txtDocumentoLocatario' => '',
                'dateNascimentoLocatario' => '',
                'txtEmailLocatario' => '',
                'txtTelefoneUmLocatario' => '',
                'txtTelefoneDoisLocatario' => '',
                'cboCasa' => '',
                'qtdPets' => '',
                'chkPossuiPets' => '',
                'qtdAdesivos' => '',
                'chkRecebeuAdesivo' => '',



                'nomeProprietario_erro' => '',
                'documentoProprietario_erro' => '',
                'dataNascimentoProprieratio_erro' => '',
                'emailProprietario_erro' => '',
                'telefone_um_proprietario_erro' => '',
                'cboCasa_erro' => '',

                'nomeLocatario_erro' => '',
                'documentoLocatario_erro' => '',
                'dataNascimentoLocatario_erro' => '',
                'emailLocatario_erro' => '',
                'telefone_um_locatario_erro' => '',
                'quantidade_pets_erro' => '',
                'quantidade_adesivos_erro' => '',


                // 'casas' => $casas,
                'usuario' => $usuario,
                'listaTiposVeiculos' => $listaTiposVeiculos,
                'listaCoresVeiculos' => $listaCoresVeiculos

            ];
        }

        $this->view('morador/cadastrarPorId', $dados);
    }

    public function editarMoradorPorIdMorador($id)
    {
        $morador = $this->model->retornarMoradorPorId($id);

        $usuario = $this->usuarioModel->lerUsuarioPorIdComCasas($_SESSION['id_usuario']);

        if(empty($usuario) || empty($morador) || $morador->fk_casa != $usuario->fk_casa){
            Redirecionamento::redirecionar('Moradores/visualizarMoradorPorIdUsuario/' . $_SESSION['id_usuario']);
        }

        $morador = $this->model->retornarMoradorPorId($id);
        $veiculosMorador = $this->modelVeiculo->recuperarListaTodosOsVeiculosPorIdMorador($id);
        $listaTiposVeiculos = $this->modelVeiculo->recuperarTiposVeiculos();
        $listaCoresVeiculos = $this->modelVeiculo->recuperarCoresVeiculos();
        $casas = $this->casaModel->reuperarTodasCasas();

        //Evita que codigos maliciosos sejam enviados pelos campos
        $formulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($formulario)) {

            $listaVeiculosCadastradosForm = VeiculosUtil::recuperarVeiculosFormulario($formulario);

            $dados = [
                'txtNomeProprietario' => trim($formulario['txtNomeProprietario']),
                'txtDocumentoProprietario' => trim($formulario['txtDocumentoProprietario']),
                'dateNascimentoProprietario' => $formulario['dateNascimentoProprietario'],
                'txtEmailProprietario' => trim($formulario['txtEmailProprietario']),
                'txtTelefoneUmProprietario' => trim($formulario['txtTelefoneUmProprietario']),
                'txtTelefoneDoisProprietario' => trim($formulario['txtTelefoneDoisProprietario']),
                'txtTelefoneEmergenciaProprietario' => trim($formulario['txtTelefoneEmergenciaProprietario']),
                'cboCasa' => $morador->fk_casa,
                'txtDetalhes' => $formulario['txtDetalhes'],

                'chkLocatario' => isset($formulario['chkLocatario']) ? trim($formulario['chkLocatario']) : "N",
                'txtNomeLocatario' => trim($formulario['txtNomeLocatario']),
                'txtDocumentoLocatario' => $formulario['txtDocumentoLocatario'],
                'dateNascimentoLocatario' => trim($formulario['dateNascimentoLocatario']) == "" ? null : trim($formulario['dateNascimentoLocatario']),
                'txtEmailLocatario' => trim($formulario['txtEmailLocatario']),
                'txtTelefoneUmLocatario' => trim($formulario['txtTelefoneUmLocatario']),
                'txtTelefoneDoisLocatario' => trim($formulario['txtTelefoneDoisLocatario']),

                'qtdPets' => trim($formulario['qtdPets']) != "" ? intval($formulario['qtdPets']) : 0,
                'chkPossuiPets' => isset($formulario['chkPossuiPets']) ? trim($formulario['chkPossuiPets']) : "N",

                'qtdAdesivos' => trim($formulario['qtdAdesivos']) != "" ? intval($formulario['qtdAdesivos']) : 0,
                'chkRecebeuAdesivo' => isset($formulario['chkRecebeuAdesivo']) ? trim($formulario['chkRecebeuAdesivo']) : "N",


                'nomeProprietario_erro' => '',
                'documentoProprietario_erro' => '',
                'dataNascimentoProprieratio_erro' => '',
                'emailProprietario_erro' => '',
                'telefone_um_proprietario_erro' => '',
                // 'cboCasa_erro' => '',
                'nomeLocatario_erro' => '',
                'documentoLocatario_erro' => '',
                'dataNascimentoLocatario_erro' => '',
                'emailLocatario_erro' => '',
                'telefone_um_locatario_erro' => '',
                'quantidade_pets_erro' => '',
                'quantidade_adesivos_erro' => '',


                'casas' => $casas,
                'listaTiposVeiculos' => $listaTiposVeiculos,
                'listaCoresVeiculos' => $listaCoresVeiculos,

                'veiculosMorador' => $veiculosMorador,
                'morador' => $morador,

                'idMorador' => $id
            ];


            if ($dados['chkRecebeuAdesivo'] == 'N') {
                $dados['qtdAdesivos'] = 0;
            }

            if ($dados['chkPossuiPets'] == 'N') {
                $dados['qtdPets'] = 0;
            }

            // var_dump($dados);
            // exit();

            if (empty($formulario['txtNomeProprietario'])) {
                $dados['nomeProprietario_erro'] = "Preencha o Nome";
            } elseif (empty($formulario["txtDocumentoProprietario"])) {
                $dados["documentoProprietario_erro"] = "Preencha o documento";
            } elseif (empty($formulario['dateNascimentoProprietario'])) {
                $dados["dataNascimentoProprieratio_erro"] = "Escolha uma data";
            } elseif (empty($formulario['txtEmailProprietario'])) {
                $dados["emailProprietario_erro"] = "Preencha um email";
            } elseif (empty($formulario['txtTelefoneUmProprietario'])) {
                $dados["telefone_um_proprietario_erro"] = "Preencha um telefone";
            } elseif (($dados['chkPossuiPets'] == 'S' && $dados['qtdPets'] == 0)) {
                $dados["quantidade_pets_erro"] = "Digite a quantidade de pets valida *";
            } elseif ($dados['chkLocatario'] === 'S' && empty($dados['txtNomeLocatario'])) {
                $dados["nomeLocatario_erro"] = "Digite o nome do locatário *";
            } elseif ($dados['chkLocatario'] === 'S' && empty($dados['txtDocumentoLocatario'])) {
                $dados["documentoLocatario_erro"] = "Digite o documento do locatário *";
            } elseif ($dados['chkLocatario'] === 'S' && empty($dados['dateNascimentoLocatario'])) {
                $dados["dataNascimentoLocatario_erro"] = "Digite o data nascimento do locatário *";
            } elseif ($dados['chkLocatario'] === 'S' && empty($dados['txtTelefoneUmLocatario'])) {
                $dados["telefone_um_locatario_erro"] = "Digite o telefone do locatário *";
            } elseif (($dados['chkRecebeuAdesivo'] == 'S' && $dados['qtdAdesivos'] == 0)) {
                $dados["quantidade_adesivos_erro"] = "Digite a quantidade de adesivos valida *";
            } else {

                $dadosAtualizado = $this->verificarSeNaoTemLocatario($dados);

                if ($this->model->atualizarMorador($dadosAtualizado)) {

                    $this->modelVeiculo->editarCarrosMorador($listaVeiculosCadastradosForm, $id);
                }

                Alertas::mensagem('morador', 'Morador atualizado com sucesso');
                Redirecionamento::redirecionar('Moradores/visualizarMoradorPorIdUsuario/' . $_SESSION['id_usuario']);
            }
        } else {

            $dados = [
                'txtNomeProprietario' => '',
                'txtDocumentoProprietario' => '',
                'dateNascimentoProprietario' => '',
                'txtEmailProprietario' => '',
                'txtTelefoneUmProprietario' => '',
                'txtTelefoneDoisProprietario' => '',
                'txtTelefoneEmergenciaProprietario' => '',
                'txtDetalhes' => '',

                'chkLocatario' => '',
                'txtNomeLocatario' => '',
                'txtDocumentoLocatario' => '',
                'dateNascimentoLocatario' => '',
                'txtEmailLocatario' => '',
                'txtTelefoneUmLocatario' => '',
                'txtTelefoneDoisLocatario' => '',
                // 'cboCasa' => '',
                'qtdPets' => '',
                'chkPossuiPets' => '',
                'qtdAdesivos' => '',
                'chkRecebeuAdesivo' => '',



                'nomeProprietario_erro' => '',
                'documentoProprietario_erro' => '',
                'dataNascimentoProprieratio_erro' => '',
                'emailProprietario_erro' => '',
                'telefone_um_proprietario_erro' => '',
                'cboCasa_erro' => '',

                'nomeLocatario_erro' => '',
                'documentoLocatario_erro' => '',
                'dataNascimentoLocatario_erro' => '',
                'emailLocatario_erro' => '',
                'telefone_um_locatario_erro' => '',
                'quantidade_pets_erro' => '',
                'quantidade_adesivos_erro' => '',


                'casas' => $casas,
                'listaTiposVeiculos' => $listaTiposVeiculos,
                'listaCoresVeiculos' => $listaCoresVeiculos,

                'veiculosMorador' => $veiculosMorador,
                'morador' => $morador,
                'idMorador' => $id

            ];
        }

        // var_dump($dados);
        // exit();


        $this->view('morador/editarPorId', $dados);
    }
}