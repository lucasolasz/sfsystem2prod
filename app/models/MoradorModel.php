<?php

class MoradorModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }


    public function recuperarTodosOsMoradoresCadastrados()
    {
        $this->db->query("SELECT * FROM tb_morador ORDER BY nm_morador");
        return $this->db->resultados();
    }

    public function armazenarMorador($dados)
    {
        $query = "INSERT INTO tb_morador
            (nm_morador, 
            fk_casa,
            documento_morador, 
            dt_nascimento_morador, 
            tel_um_morador, 
            tel_dois_morador, 
            email_morador, 
            tel_emergencia,
            flag_locatario,
            nm_locatario,
            documento_locatario,
            dt_nascimento_locatario,
            email_locatario,
            tel_um_locatario,
            tel_dois_locatario,
            flag_tem_pet,
            qtd_pets,
            flag_adesivo,
            qtd_adesivos,
            ds_detalhes
            )
            VALUES(:nm_morador,
             :fk_casa, 
             :documento_morador, 
             :dt_nascimento_morador, 
             :tel_um_morador, 
             :tel_dois_morador, 
             :email_morador, 
             :tel_emergencia,
             :flag_locatario,
             :nm_locatario,
             :documento_locatario,
             :dt_nascimento_locatario,
             :email_locatario,
             :tel_um_locatario,
             :tel_dois_locatario,   
             :flag_tem_pet,
             :qtd_pets,
             :flag_adesivo,
             :qtd_adesivos,
             :ds_detalhes
             );";

        $this->db->query($query);

        $this->db->bind("nm_morador", $dados['txtNomeProprietario']);
        $this->db->bind("fk_casa", $dados['cboCasa']);
        $this->db->bind("documento_morador", $dados['txtDocumentoProprietario']);
        $this->db->bind("dt_nascimento_morador", $dados['dateNascimentoProprietario']);
        $this->db->bind("tel_um_morador", $dados['txtTelefoneUmProprietario']);
        $this->db->bind("tel_dois_morador", $dados['txtTelefoneDoisProprietario']);
        $this->db->bind("email_morador", $dados['txtEmailProprietario']);
        $this->db->bind("tel_emergencia", $dados['txtTelefoneEmergenciaProprietario']);

        $this->db->bind("flag_locatario", $dados['chkLocatario']);
        $this->db->bind("nm_locatario", $dados['txtNomeLocatario']);
        $this->db->bind("documento_locatario", $dados['txtDocumentoLocatario']);
        $this->db->bind("dt_nascimento_locatario", $dados['dateNascimentoLocatario']);
        $this->db->bind("email_locatario", $dados['txtEmailLocatario']);
        $this->db->bind("tel_um_locatario", $dados['txtTelefoneUmLocatario']);
        $this->db->bind("tel_dois_locatario", $dados['txtTelefoneDoisLocatario']);
        $this->db->bind("flag_tem_pet", $dados['chkPossuiPets']);
        $this->db->bind("qtd_pets", $dados['qtdPets']);
        $this->db->bind("flag_adesivo", $dados['chkRecebeuAdesivo']);
        $this->db->bind("qtd_adesivos", $dados['qtdAdesivos']);
        $this->db->bind("ds_detalhes", $dados['txtDetalhes']);


        if ($this->db->executa()) {
            return true;
        } else {
            return false;
        }
    }

    public function ultimoIdInserido()
    {
        return $this->db->ultimoIdInserido();
    }

    public function retornarMoradorPorId($id)
    {
        $queryMontada = " SELECT * FROM tb_morador m ";
        $queryMontada .= " LEFT JOIN tb_casa tc ON tc.id_casa = m.fk_casa";
        $queryMontada .= " WHERE id_morador = :id_morador ";

        $this->db->query($queryMontada);

        $this->db->bind("id_morador", $id);

        return $this->db->resultado();
    }


    public function atualizarMorador($dados)
    {
        $query = "UPDATE tb_morador
        SET 
            nm_morador = :nm_morador,
            fk_casa = :fk_casa,
            documento_morador = :documento_morador,
            dt_nascimento_morador = :dt_nascimento_morador,
            tel_um_morador = :tel_um_morador,
            tel_dois_morador = :tel_dois_morador,
            email_morador = :email_morador,
            tel_emergencia = :tel_emergencia,
            flag_locatario = :flag_locatario,
            nm_locatario = :nm_locatario,
            documento_locatario = :documento_locatario,
            dt_nascimento_locatario = :dt_nascimento_locatario,
            email_locatario = :email_locatario,
            tel_um_locatario = :tel_um_locatario,
            tel_dois_locatario = :tel_dois_locatario,
            flag_tem_pet = :flag_tem_pet,
            qtd_pets = :qtd_pets,
            flag_adesivo = :flag_adesivo,
            qtd_adesivos = :qtd_adesivos,
            ds_detalhes = :ds_detalhes
        WHERE id_morador = :id_morador;";

        $this->db->query($query);

        $this->db->bind("nm_morador", $dados['txtNomeProprietario']);
        $this->db->bind("fk_casa", $dados['cboCasa']);
        $this->db->bind("documento_morador", $dados['txtDocumentoProprietario']);
        $this->db->bind("dt_nascimento_morador", $dados['dateNascimentoProprietario']);
        $this->db->bind("tel_um_morador", $dados['txtTelefoneUmProprietario']);
        $this->db->bind("tel_dois_morador", $dados['txtTelefoneDoisProprietario']);
        $this->db->bind("email_morador", $dados['txtEmailProprietario']);
        $this->db->bind("tel_emergencia", $dados['txtTelefoneEmergenciaProprietario']);

        $this->db->bind("flag_locatario", $dados['chkLocatario']);
        $this->db->bind("nm_locatario", $dados['txtNomeLocatario']);
        $this->db->bind("documento_locatario", $dados['txtDocumentoLocatario']);
        $this->db->bind("dt_nascimento_locatario", $dados['dateNascimentoLocatario']);
        $this->db->bind("email_locatario", $dados['txtEmailLocatario']);
        $this->db->bind("tel_um_locatario", $dados['txtTelefoneUmLocatario']);
        $this->db->bind("tel_dois_locatario", $dados['txtTelefoneDoisLocatario']);
        $this->db->bind("flag_tem_pet", $dados['chkPossuiPets']);
        $this->db->bind("qtd_pets", $dados['qtdPets']);
        $this->db->bind("flag_adesivo", $dados['chkRecebeuAdesivo']);
        $this->db->bind("qtd_adesivos", $dados['qtdAdesivos']);
        $this->db->bind("id_morador", $dados['idMorador']);
        $this->db->bind("ds_detalhes", $dados['txtDetalhes']);


        if ($this->db->executa()) {
            return true;
        } else {
            return false;
        }
    }

    public function deletarMorador($id)
    {
        $this->db->query("DELETE FROM tb_morador WHERE id_morador = :id_morador");
        $this->db->bind("id_morador", $id);

        if ($this->db->executa()) {
            return true;
        } else {
            return false;
        }
    }

    public function retornarMoradorCadastradoPorIdUsuarioOuFkCasa($id, $fk_casa)
    {

        $queryMontada = " SELECT * FROM tb_morador m ";
        $queryMontada .= " LEFT JOIN tb_casa tc ON tc.id_casa = m.fk_casa";
        $queryMontada .= " WHERE fk_usuario = :fk_usuario OR fk_casa = :fk_casa";

        $this->db->query($queryMontada);

        $this->db->bind("fk_usuario", $id);
        $this->db->bind("fk_casa", $fk_casa);

        return $this->db->resultados();
    }


    public function armazenarMoradorPorIdUsuario($dados)
    {
        $query = "INSERT INTO tb_morador
            (nm_morador, 
            fk_casa,
            documento_morador, 
            dt_nascimento_morador, 
            tel_um_morador, 
            tel_dois_morador, 
            email_morador, 
            tel_emergencia,
            flag_locatario,
            nm_locatario,
            documento_locatario,
            dt_nascimento_locatario,
            email_locatario,
            tel_um_locatario,
            tel_dois_locatario,
            flag_tem_pet,
            qtd_pets,
            flag_adesivo,
            qtd_adesivos,
            fk_usuario,
            ds_detalhes
            )
            VALUES(:nm_morador,
             :fk_casa, 
             :documento_morador, 
             :dt_nascimento_morador, 
             :tel_um_morador, 
             :tel_dois_morador, 
             :email_morador, 
             :tel_emergencia,
             :flag_locatario,
             :nm_locatario,
             :documento_locatario,
             :dt_nascimento_locatario,
             :email_locatario,
             :tel_um_locatario,
             :tel_dois_locatario,   
             :flag_tem_pet,
             :qtd_pets,
             :flag_adesivo,
             :qtd_adesivos,
             :fk_usuario,
             :ds_detalhes
             );";

        $this->db->query($query);

        $this->db->bind("nm_morador", $dados['txtNomeProprietario']);
        $this->db->bind("fk_casa", $dados['cboCasa']);
        $this->db->bind("documento_morador", $dados['txtDocumentoProprietario']);
        $this->db->bind("dt_nascimento_morador", $dados['dateNascimentoProprietario']);
        $this->db->bind("tel_um_morador", $dados['txtTelefoneUmProprietario']);
        $this->db->bind("tel_dois_morador", $dados['txtTelefoneDoisProprietario']);
        $this->db->bind("email_morador", $dados['txtEmailProprietario']);
        $this->db->bind("tel_emergencia", $dados['txtTelefoneEmergenciaProprietario']);

        $this->db->bind("flag_locatario", $dados['chkLocatario']);
        $this->db->bind("nm_locatario", $dados['txtNomeLocatario']);
        $this->db->bind("documento_locatario", $dados['txtDocumentoLocatario']);
        $this->db->bind("dt_nascimento_locatario", $dados['dateNascimentoLocatario']);
        $this->db->bind("email_locatario", $dados['txtEmailLocatario']);
        $this->db->bind("tel_um_locatario", $dados['txtTelefoneUmLocatario']);
        $this->db->bind("tel_dois_locatario", $dados['txtTelefoneDoisLocatario']);
        $this->db->bind("flag_tem_pet", $dados['chkPossuiPets']);
        $this->db->bind("qtd_pets", $dados['qtdPets']);
        $this->db->bind("flag_adesivo", $dados['chkRecebeuAdesivo']);
        $this->db->bind("qtd_adesivos", $dados['qtdAdesivos']);

        $this->db->bind("fk_usuario", $dados['usuario']->id_usuario);
        $this->db->bind("ds_detalhes", $dados['txtDetalhes']);


        if ($this->db->executa()) {
            return true;
        } else {
            return false;
        }
    }

    public function recuperaMoradorPeloUsuarioLogado($id){

        $this->db->query("SELECT * FROM tb_morador WHERE fk_usuario = :id_usuario ");
        
        $this->db->bind("id_usuario", $id);            
        
        return $this->db->resultados();
    }
}
