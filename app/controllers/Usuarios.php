<?php

class Usuarios extends Controller
{
    private $model;

    private $casaModel;

    //Construtor do model do Usuário que fará o acesso ao banco
    public function __construct()
    {
        $permissoes = [ADMINISTRADOR, SINDICO];
        $this->verificaSeEstaLogadoETemPermissao($permissoes);

        $this->model = $this->model("UsuarioModel");
        $this->casaModel = $this->model("CasaModel");
    }

    public function cadastrar()
    {

        $tiposUsuario = $this->model->listarTipoUsuario();
        $cargoUsuario = $this->model->listarCargoUsuario();
        $casas = $this->casaModel->reuperarTodasCasasNaoCadastradas();

        //Evita que codigos maliciosos sejam enviados pelos campos
        $formulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($formulario)) {

            // var_dump($dados);
            // exit();

            $dados = [
                'txtNome' => trim($formulario['txtNome']),
                'txtSobreNome' => trim($formulario['txtSobreNome']),
                'txtEmail' => trim($formulario['txtEmail']),
                'txtSenha' => trim($formulario['txtSenha']),
                'txtConfirmaSenha' => trim($formulario['txtConfirmaSenha']),
                'cboTipoUsuario' => $this->defineTipoUsuarioDeAcordoComCargoSelecionado($formulario['cboCargoUsuario']),
                'cboCargoUsuario' => $formulario['cboCargoUsuario'],
                'casas' => $casas,
                'tiposUsuario' => $tiposUsuario,
                'cargoUsuario' => $cargoUsuario,
                // 'cboCasa' => $formulario['cboCasa'],
                
                'cboCasa_erro' => '',
                'nome_erro' => '',
                'email_erro' => '',
                'sobrenome_erro' => '',
                'senha_erro' => '',
                'confirma_senha_erro' => '',
                'tipoUsuario_erro' => '',
                'tipoCargo_erro' => ''
            ];

            if($formulario['cboCargoUsuario'] == "3"){
                $dados['cboCasa'] = $formulario['cboCasa'];
            } else {
                // echo("combocasa");
                $dados['cboCasa'] = NULL;
            }


            if(empty($formulario['txtNome'])){
                $dados['nome_erro'] = "Preencha o Nome";
            } elseif (Checa::checarNome($formulario['txtNome'])) {
                $dados['nome_erro'] = "Nome inválido";
            } elseif (empty($formulario["txtSobreNome"])) {
                $dados["sobrenome_erro"] = "Preencha o sobrenome";
            } elseif (empty($formulario['txtEmail'])) {
                $dados['email_erro'] = "Preencha o email";
            } elseif (Checa::checarEmail($formulario['txtEmail'])) {
                $dados['email_erro'] = "Email inválido";
            } elseif ($formulario['cboCargoUsuario'] == "3" && empty($dados['cboCasa'])) {
                $dados["cboCasa_erro"] = "Escolha uma casa";
            } elseif ($formulario['cboCargoUsuario'] == 'NULL') {
                $dados['tipoCargo_erro'] = "Escolha um cargo de usuário";
            } elseif (empty($formulario['txtSenha'])) {
                $dados['senha_erro'] = "Preencha a senha";
            } elseif (empty($formulario['txtConfirmaSenha'])) {
                $dados['confirma_senha_erro'] = "Preencha a confirmação de senha";
            } elseif ($this->model->checarEmailUsuario($dados)) {
                $dados['email_erro'] = "Email já está sendo utilizado";
            } elseif (strlen($formulario['txtSenha']) < 6) {
                $dados['senha_erro'] = "A senha precisa ter no mínimo 6 caracteres";
            } elseif ($formulario['txtSenha'] != $formulario['txtConfirmaSenha']) {
                $dados['confirma_senha_erro'] = "As senhas são diferentes";
            } else {

                //Criptografa a senha com hash em php
                $dados['txtSenha'] = password_hash($formulario['txtSenha'], PASSWORD_DEFAULT);

                if ($this->model->armazenarUsuario($dados)) {

                    //Para exibir mensagem success , não precisa informar o tipo de classe
                    Alertas::mensagem('usuario', 'Usuário cadastrado com sucesso');
                    Redirecionamento::redirecionar('usuarios/visualizarUsuarios');
                } else {
                    die("Erro ao armazenar usuário no banco de dados");
                }
            }

        } else {
            $dados = [
                'txtNome' => '',
                'txtSobreNome' => '',
                'txtEmail' => '',
                'txtSenha' => '',
                'txtConfirmaSenha' => '',
                'cboCargoUsuario' => '',
                'cboCasa' => '',

                'cboCasa_erro' => '',
                'nome_erro' => '',
                'sobrenome_erro' => '',
                'email_erro' => '',
                'senha_erro' => '',
                'confirma_senha_erro' => '',
                'tipoUsuario_erro' => '',
                'tipoCargo_erro' => '',

                'casas' => $casas,
                'tiposUsuario' => $tiposUsuario,
                'cargoUsuario' => $cargoUsuario
            ];
        }

        //Retorna para a view
        $this->view('usuarios/cadastrar', $dados);
    }


    public function defineTipoUsuarioDeAcordoComCargoSelecionado($cboCargoUsuario)
    {
        if (!empty($cboCargoUsuario)) {

            switch ($cboCargoUsuario) {
                case "1":
                    return 2;
                case "2":
                    return 1;
                case "3":
                    return 3;
                case "4":
                    return 4;
            }
        }

    }

    public function visualizarUsuarios()
    {
        $usuarios = $this->model->visualizarUsuarios();

        $dados = [
            'usuarios' => $usuarios,
            'tituloBreadcrumb' => ''
        ];

        //Retorna para a view
        $this->view('usuarios/visualizar', $dados);
    }

    public function editarUsuario($id)
    {

        $tiposUsuario = $this->model->listarTipoUsuario();
        $cargoUsuario = $this->model->listarCargoUsuario();
        $usuario = $this->model->lerUsuarioPorIdComCasas($id);
        $casas = $this->casaModel->reuperarTodasCasasNaoCadastradas();

        //Evita que codigos maliciosos sejam enviados pelos campos
        $formulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($formulario)) {

            $dados = [
                'txtNome' => trim($formulario['txtNome']),
                'txtSobreNome' => trim($formulario['txtSobreNome']),
                'txtEmail' => trim($formulario['txtEmail']),
                'txtSenha' => trim($formulario['txtSenha']),
                'txtConfirmaSenha' => trim($formulario['txtConfirmaSenha']),
                'cboTipoUsuario' => $this->defineTipoUsuarioDeAcordoComCargoSelecionado($formulario['cboCargoUsuario']),
                'cboCargoUsuario' => $formulario['cboCargoUsuario'],
                'idUsuario' => $id,

                'casas' => $casas,
                'tiposUsuario' => $tiposUsuario,
                'cargoUsuario' => $cargoUsuario,
                'usuario' => $usuario,

                'cboCasa_erro' => '',
                'nome_erro' => '',
                'sobrenome_erro' => '',
                'email_erro' => '',
                'senha_erro' => '',
                'confirma_senha_erro' => '',
                'tipoUsuario_erro' => '',
                'tipoCargo_erro' => ''
            ];

            if($formulario['cboCargoUsuario'] == "3"){
                $dados['cboCasa'] = $formulario['cboCasa'];
            } else {
                // echo("combocasa");
                $dados['cboCasa'] = NULL;
            }

            if(empty($formulario['txtNome'])){
                $dados['nome_erro'] = "Preencha o Nome";
            } elseif (Checa::checarNome($formulario['txtNome'])) {
                $dados['nome_erro'] = "Nome inválido";
            } elseif (empty($formulario["txtSobreNome"])) {
                $dados["sobrenome_erro"] = "Preencha o sobrenome";
            } elseif (empty($formulario['txtEmail'])) {
                $dados['email_erro'] = "Preencha o email";
            } elseif ($formulario['cboCargoUsuario'] == "3" && empty($dados['cboCasa'])) {
                $dados["cboCasa_erro"] = "Escolha uma casa";
            } elseif ($formulario['cboCargoUsuario'] == 'NULL') {
                $dados['tipoCargo_erro'] = "Escolha um cargo de usuário";
            } else {

                if ($formulario['txtSenha'] != "") {
                    if (strlen($formulario['txtSenha']) < 6) {
                        $dados['senha_erro'] = "A senha precisa ter no mínimo 6 caracteres";
                    } elseif ($formulario['txtSenha'] != $formulario['txtConfirmaSenha']) {
                        $dados['confirma_senha_erro'] = "As senhas são diferentes";
                    }
                    //Criptografa a senha com hash em php
                    $dados['txtSenha'] = password_hash($formulario['txtSenha'], PASSWORD_DEFAULT);

                    if ($this->model->atualizarUsuarioComSenha($dados)) {
                        //Para exibir mensagem success , não precisa informar o tipo de classe
                        Alertas::mensagem('usuario', 'Usuário atualizado com sucesso');
                        Redirecionamento::redirecionar('Usuarios/visualizarUsuarios');
                    } else {
                        Alertas::mensagem('usuario', 'Usuário atualizado com sucesso', 'alert alert-danger');
                    }
                } else {
                    if ($this->model->atualizarUsuarioSemSenha($dados)) {

                        //Para exibir mensagem success , não precisa informar o tipo de classe
                        Alertas::mensagem('usuario', 'Usuário atualizado com sucesso');
                        Redirecionamento::redirecionar('Usuarios/visualizarUsuarios');
                    } else {
                        Alertas::mensagem('usuario', 'Usuário atualizado com sucesso', 'alert alert-danger');
                    }
                }
            }
        } else {
            $dados = [
                'txtNome' => '',
                'txtSobreNome' => '',
                'txtEmail' => '',
                'txtSenha' => '',
                'txtConfirmaSenha' => '',

                'cboCasa_erro' => '',
                'nome_erro' => '',
                'sobrenome_erro' => '',
                'email_erro' => '',
                'senha_erro' => '',
                'confirma_senha_erro' => '',
                'tipoUsuario_erro' => '',
                'tipoCargo_erro' => '',
                'casas' => $casas,
                'tiposUsuario' => $tiposUsuario,
                'cargoUsuario' => $cargoUsuario,
                'usuario' => $usuario,
            ];
        }

        //Retorna para a view
        $this->view('usuarios/editar', $dados);
    }

    public function deletarUsuario($id)
    {

        if ($this->model->deletarUsuario($id)) {

            //Para exibir mensagem success , não precisa informar o tipo de classe
            Alertas::mensagem('usuario', 'Usuário deletado com sucesso');
            Redirecionamento::redirecionar('Usuarios/visualizarUsuarios');
        } else {
            Alertas::mensagem('usuario', 'Não foi possível deletar o usuário', 'alert alert-danger');
            Redirecionamento::redirecionar('Usuarios/visualizarUsuarios');
        }
    }
}
