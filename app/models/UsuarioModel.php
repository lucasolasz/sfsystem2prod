<?php

class UsuarioModel
{

    private $db;


    public function __construct()
    {
        $this->db = new Database();
    }

    //Realiza o login do usuário baseado no email e senha hash
    public function checarLogin($email, $senha)
    {
        $this->db->query("SELECT * FROM tb_usuario JOIN tb_tipo_usuario as tus ON tus.id_tipo_usuario = fk_tipo_usuario WHERE ds_email_usuario = :ds_email_usuario");

        $this->db->bind("ds_email_usuario", $email);

        if ($this->db->resultado()) {

            $resultado = $this->db->resultado();


            //Verifica o hash code
            if (password_verify($senha, $resultado->ds_senha)) {
                return $resultado;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Verifica se email existe
    public function checarEmailUsuario($dados)
    {
        $this->db->query("SELECT ds_email_usuario FROM tb_usuario WHERE ds_email_usuario = :ds_email_usuario");

        $this->db->bind("ds_email_usuario", $dados['txtEmail']);

        if ($this->db->resultado()) {
            return true;
        } else {
            return false;
        }
    }


    //Armazena usuário no banco
    public function armazenarUsuario($dados)
    {

        $this->db->query("INSERT INTO tb_usuario (ds_nome_usuario, ds_sobrenome_usuario, ds_email_usuario, ds_senha, fk_cargo, fk_tipo_usuario) VALUES (:ds_nome_usuario, :ds_sobrenome_usuario, :ds_email_usuario, :ds_senha, :fk_cargo, :fk_tipo_usuario)");

        $this->db->bind("ds_nome_usuario", $dados['txtNome']);
        $this->db->bind("ds_sobrenome_usuario", $dados['txtSobreNome']);
        $this->db->bind("ds_email_usuario", $dados['txtEmail']);
        $this->db->bind("ds_senha", $dados['txtSenha']);
        $this->db->bind("fk_cargo", $dados['cboCargoUsuario']);
        $this->db->bind("fk_tipo_usuario", $dados['cboTipoUsuario']);


        if ($this->db->executa()) {
            return true;
        } else {
            return false;
        }
    }

    //A principio, criada para retornar a linha com os dados do usuário específico
    public function lerUsuarioPorId($id)
    {
        $this->db->query("SELECT * FROM tb_usuario WHERE id_usuario = :id_usuario");

        $this->db->bind("id_usuario", $id);

        return $this->db->resultado();
    }

    public function listarTipoUsuario()
    {
        $this->db->query("SELECT * FROM tb_tipo_usuario ORDER BY ds_tipo_usuario");

        return $this->db->resultados();
    }

    public function listarCargoUsuario()
    {
        $this->db->query("SELECT * FROM tb_cargo ORDER BY ds_cargo");

        return $this->db->resultados();
    }

    public function visualizarUsuarios()
    {
        $this->db->query("SELECT * FROM tb_usuario WHERE id_usuario != 0");
        return $this->db->resultados();
    }

    public function atualizarUsuarioComSenha($dados)
    {
        $this->db->query("UPDATE tb_usuario SET 
        ds_nome_usuario=:ds_nome_usuario,
        ds_sobrenome_usuario=:ds_sobrenome_usuario,
        ds_email_usuario=:ds_email_usuario, 
        fk_cargo=:fk_cargo,
        fk_tipo_usuario=:fk_tipo_usuario
        
        WHERE id_usuario= :id_usuario;
        ");

        $this->db->bind("ds_nome_usuario", trim($dados['txtNome']));
        $this->db->bind("ds_sobrenome_usuario", trim($dados['txtSobreNome']));
        $this->db->bind("ds_email_usuario", trim($dados['txtEmail']));
        $this->db->bind("fk_cargo", $dados['cboCargoUsuario']);
        $this->db->bind("fk_tipo_usuario", $dados['cboTipoUsuario']);
        $this->db->bind("id_usuario", $dados['idUsuario']);

        if ($this->db->executa()) {
            return true;
        } else {
            return false;
        }
    }

    public function atualizarUsuarioSemSenha($dados)
    {
        $this->db->query("UPDATE tb_usuario SET 
        ds_nome_usuario=:ds_nome_usuario,
        ds_sobrenome_usuario=:ds_sobrenome_usuario,
        ds_email_usuario=:ds_email_usuario, 
        fk_cargo=:fk_cargo,
        fk_tipo_usuario=:fk_tipo_usuario
        
        WHERE id_usuario= :id_usuario;
        ");

        $this->db->bind("ds_nome_usuario", trim($dados['txtNome']));
        $this->db->bind("ds_sobrenome_usuario", trim($dados['txtSobreNome']));
        $this->db->bind("ds_email_usuario", trim($dados['txtEmail']));
        $this->db->bind("fk_cargo", $dados['cboCargoUsuario']);
        $this->db->bind("fk_tipo_usuario", $dados['cboTipoUsuario']);
        $this->db->bind("id_usuario", $dados['idUsuario']);

        if ($this->db->executa()) {
            return true;
        } else {
            return false;
        }
    }

    public function deletarUsuario($id)
    {
        $this->db->query("DELETE FROM tb_usuario WHERE id_usuario = :id_usuario");
        $this->db->bind("id_usuario", $id);

        if ($this->db->executa()) {
            return true;
        } else {
            return false;
        }
    }

    public function carregarPermissoes($id_usuario)
    {
        $query = "select * from tb_tipo_usuario_cargo ttuc 
                join tb_cargo tc on tc.id_cargo = ttuc.fk_cargo 
                join tb_tipo_usuario ttu on ttu.id_tipo_usuario = ttuc.fk_tipo_usuario 
                join tb_usuario tu on tu.fk_tipo_usuario = ttuc.fk_tipo_usuario
                where tu.id_usuario = :id_usuario";
        $this->db->query($query);
        $this->db->bind("id_usuario", $id_usuario);

        return $this->db->resultados();
    }

    public function lerUsuarioPorIdComCasas($id)
    {

        $query = "select * from tb_usuario tbu 
                    LEFT JOIN tb_casa tbc ON tbc.id_casa = tbu.fk_casa
                    WHERE id_usuario = :id_usuario";

        $this->db->query($query);

        $this->db->bind("id_usuario", $id);

        return $this->db->resultado();
    }
}
