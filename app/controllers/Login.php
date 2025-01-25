<?php

class Login extends Controller
{

    private $model;

    //Construtor do model do Usuário que fará o acesso ao banco
    public function __construct()
    {
        $this->model = $this->model("UsuarioModel");
    }


    public function login()
    {
        //Evita que codigos maliciosos sejam enviados pelos campos
        $formulario = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        if (isset($formulario)) {

            $dados = [
                'txtEmail' => trim($formulario['txtEmail']),
                'txtSenha' => trim($formulario['txtSenha']),
                'txtNome' => '',
                'email_erro' => '',
                'senha_erro' => ''
            ];

            if (in_array("", $formulario)) {

                //Verifica se está vazio
                if (empty($formulario['txtEmail'])) {
                    $dados['email_erro'] = "Preencha o email";
                }
                if (empty($formulario['txtSenha'])) {
                    $dados['senha_erro'] = "Preencha a senha";
                }
            } else {
                //Invoca método estatico da classe 
                if (Checa::checarEmail($formulario['txtEmail'])) {
                    $dados['email_erro'] = "Email inválido";
                } elseif (strlen($formulario['txtSenha']) < 6) {
                    $dados['senha_erro'] = "A senha precisa ter no mínimo 6 caracteres";
                } else {

                    $usuario = $this->model->checarLogin($formulario['txtEmail'], $formulario['txtSenha']);

                    if ($usuario) {
                        $permissoes = $this->model->carregarPermissoes($usuario->id_usuario);
                        $this->criarSessaoUsuario($usuario, $permissoes);
                    } else {
                        Alertas::mensagem('usuario', 'Usuário ou senha inválidos', 'alert alert-danger');
                    }
                }
            }
        } else {
            $dados = [
                'txtNome' => '',
                'txtEmail' => '',
                'txtSenha' => '',
                'email_erro' => '',
                'senha_erro' => ''
            ];
        }

        //Retorna para a view
        $this->view('usuarios/login', $dados);
    }

    //Cria as variaveis de sessao ao fazer login, resgatando informações do usuário
    private function criarSessaoUsuario($usuario, $permissoes)
    {
        $_SESSION['id_usuario'] = $usuario->id_usuario;
        $_SESSION['ds_nome_usuario'] = $usuario->ds_nome_usuario;
        $_SESSION['ds_email_usuario'] = $usuario->ds_email_usuario;
        $_SESSION['fk_cargo'] = $usuario->fk_cargo;
        $_SESSION['fk_tipo_usuario'] = $usuario->fk_tipo_usuario;
        $_SESSION['permissoes'] = $permissoes;

        Redirecionamento::redirecionar('Paginas/home');
    }


    //Destroi todas as variáveis de sessão para efetuar logof
    public function sair()
    {
        unset($_SESSION['id_usuario']);
        unset($_SESSION['ds_nome_usuario']);
        unset($_SESSION['ds_email_usuario']);
        unset($_SESSION['fk_cargo']);
        unset($_SESSION['fk_tipo_usuario']);
        unset($_SESSION['cod_tipo_usuario']);

        session_destroy();

        Redirecionamento::redirecionar('Login/login');
    }
}
