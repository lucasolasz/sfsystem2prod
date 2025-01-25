<?php

class Visitantes extends Controller
{
    private $model;
    private $modelVeiculo;
    private $modelVisita;

    //Construtor do model do Usuário que fará o acesso ao banco
    public function __construct()
    {
        $permissoes = [ADMINISTRADOR, PORTEIRO, SINDICO];
        $this->verificaSeEstaLogadoETemPermissao($permissoes);

        $this->model = $this->model("VisitanteModel");
        $this->modelVeiculo = $this->model("VeiculoModel");
        $this->modelVisita = $this->model("VisitaModel");
    }

    public function cadastrar()
    {
        $listaTiposVeiculos = $this->modelVeiculo->recuperarTiposVeiculos();
        $listaCoresVeiculos = $this->modelVeiculo->recuperarCoresVeiculos();

        $formulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($formulario)) {

            $listaVeiculosCadastradosForm = VeiculosUtil::recuperarVeiculosFormulario($formulario);

            $dados = [
                'txtNome' => trim($formulario['txtNome']),
                'txtDocumento' => trim($formulario['txtDocumento']),
                'txtTelefoneUm' => trim($formulario['txtTelefoneUm']),
                'txtTelefoneDois' => $formulario['txtTelefoneDois'],
                'nome_erro' => '',
                'documento_erro' => '',
                'listaTiposVeiculos' => $listaTiposVeiculos,
                'listaCoresVeiculos' => $listaCoresVeiculos

            ];

            // Verifica se está vazio
            if (empty($formulario['txtNome'])) {
                $dados['nome_erro'] = "Preencha o Nome";
            } elseif (empty($formulario["txtDocumento"])) {
                $dados["txtDocumento"] = "Preencha o documento";
            } else {

                if ($formulario['acao'] === OPERACAO_SALVAR) {
                    $this->cadastrarVisitante($listaVeiculosCadastradosForm, $dados);
                }
                if ($formulario['acao'] === OPERACAO_SALVAR_E_ENTRAR) {
                    $this->cadastrarVisitanteDarEntradaVisita($listaVeiculosCadastradosForm, $dados);
                }
            }
        } else {
            $dados = [
                'txtNome' => '',
                'txtDocumento' => '',
                'txtTelefoneUm' => '',
                'txtTelefoneDois' => '',
                'nome_erro' => '',
                'documento_erro' => '',
                'listaTiposVeiculos' => $listaTiposVeiculos,
                'listaCoresVeiculos' => $listaCoresVeiculos

            ];
        }

        //Retorna para a view
        $this->view('visitantes/cadastrar', $dados);
    }

    private function cadastrarVisitante($listaVeiculosCadastradosForm, $dados)
    {
        $idRetorno = $this->executarQuerysCadastro($listaVeiculosCadastradosForm, $dados);
        if (!empty($idRetorno)) {
            Alertas::mensagem('visitante', texto: 'Visitante cadastrado com sucesso');
            Redirecionamento::redirecionar('Visitantes/visualizarVisitantes');
        }
    }

    private function cadastrarVisitanteDarEntradaVisita($listaVeiculosCadastradosForm, $dados)
    {
        $idVisitanteRetorno = $this->executarQuerysCadastro($listaVeiculosCadastradosForm, $dados);
        if (!empty($idVisitanteRetorno)) {
            Alertas::mensagem('visita', texto: 'Visita cadastrada com sucesso');
            Redirecionamento::redirecionar('Visitas/carregarTelaCadastroVisita/' . $idVisitanteRetorno);
        }
    }

    private function executarQuerysCadastro($listaVeiculosCadastradosForm, $dados)
    {
        $idVisitante = null;

        if ($this->model->armazenarVisitante($dados)) {

            $idVisitante = $this->model->ultimoIdInserido();

            if (!empty($listaVeiculosCadastradosForm)) {
                $this->modelVeiculo->armazenarListaCarros($listaVeiculosCadastradosForm, $idVisitante);
            }

            return $idVisitante;
        }

        return $idVisitante;
    }

    public function visualizarVisitantes()
    {
        $visitantes = $this->model->visualizarVisitantes();

        $dados = [
            'visitantes' => $visitantes
        ];

        //Retorna para a view
        $this->view('visitantes/visualizar', $dados);
    }

    public function editarVisitante($id)
    {

        $visitante = $this->model->retornarVisitantePorId($id);
        $veiculosVisitante = $this->modelVeiculo->recuperarListaTodosOsVeiculosPorIdVisitante($id);
        $listaTiposVeiculos = $this->modelVeiculo->recuperarTiposVeiculos();
        $listaCoresVeiculos = $this->modelVeiculo->recuperarCoresVeiculos();

        //Evita que codigos maliciosos sejam enviados pelos campos
        $formulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($formulario)) {

            $listaVeiculosCadastradosForm = VeiculosUtil::recuperarVeiculosFormulario($formulario);

            $dados = [
                'txtNome' => trim($formulario['txtNome']),
                'txtDocumento' => trim($formulario['txtDocumento']),
                'txtTelefoneUm' => trim($formulario['txtTelefoneUm']),
                'txtTelefoneDois' => $formulario['txtTelefoneDois'],
                'nome_erro' => '',
                'documento_erro' => '',
                'idVisitante' => $id,
                'listaTiposVeiculos' => $listaTiposVeiculos,
                'listaCoresVeiculos' => $listaCoresVeiculos,
                'veiculosVisitante' => $veiculosVisitante,
            ];

            if ($this->model->atualizarVisitante($dados)) {

                $resultado = $this->modelVisita->verificarSeExisteVisitaEmAndamentoPorIdVisitante($id);
                if ($resultado > 0) {
                    Alertas::mensagem('visitante', 'Não é possivel editar o visitante. Existe visita em andamento para este visitante', 'alert alert-danger');
                    Redirecionamento::redirecionar('Visitantes/visualizarVisitantes');
                }
                $this->modelVeiculo->editarCarrosVisitante($listaVeiculosCadastradosForm, $id);

                Alertas::mensagem('visitante', 'Visitante atualizado com sucesso');
                Redirecionamento::redirecionar('Visitantes/visualizarVisitantes');
            }
        } else {
            $dados = [
                'txtNome' => '',
                'txtDocumento' => '',
                'txtTelefoneUm' => '',
                'txtTelefoneDois' => '',
                'nome_erro' => '',
                'documento_erro' => '',
                'visitante' => $visitante,
                'listaTiposVeiculos' => $listaTiposVeiculos,
                'listaCoresVeiculos' => $listaCoresVeiculos,
                'veiculosVisitante' => $veiculosVisitante,
            ];
        }

        // var_dump($dados);
        //Retorna para a view
        $this->view('visitantes/editar', $dados);
    }
}
