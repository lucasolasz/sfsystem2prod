<?php

class VisitanteModel
{

    private $db;


    public function __construct()
    {
        $this->db = new Database();
    }

    public function retornarVisitantePorId($id)
    {
        $queryMontada = " SELECT * FROM tb_visitante v ";
        $queryMontada .= " LEFT JOIN tb_veiculo as tv ON tv.fk_visitante = v.id_visitante ";
        $queryMontada .= " LEFT JOIN tb_tipo_veiculo as ttv ON ttv.id_tipo_veiculo = tv.fk_tipo_veiculo ";
        $queryMontada .= " LEFT JOIN tb_cor_veiculo as tcv ON tcv.id_cor_veiculo = tv.fk_cor_veiculo ";
        $queryMontada .= " WHERE id_visitante = :id_visitante ";


        $this->db->query($queryMontada);

        $this->db->bind("id_visitante", $id);

        return $this->db->resultado();
    }


    public function armazenarVisitante($dados)
    {
        $this->db->query("INSERT INTO tb_visitante (nm_visitante, documento_visitante, telefone_um_visitante, telefone_dois_visitante) 
            VALUES (:nm_visitante, :documento_visitante, :telefone_um_visitante, :telefone_dois_visitante)");

        $this->db->bind("nm_visitante", $dados['txtNome']);
        $this->db->bind("documento_visitante", $dados['txtDocumento']);
        $this->db->bind("telefone_um_visitante", $dados['txtTelefoneUm']);
        $this->db->bind("telefone_dois_visitante", $dados['txtTelefoneDois']);

        if ($this->db->executa()) {
            return true;
        }
        return false;
    }

    public function visualizarVisitantes()
    {
        $this->db->query("SELECT * FROM tb_visitante ORDER BY nm_visitante");
        return $this->db->resultados();
    }

    public function atualizarVisitante($dados)
    {

        $this->db->query("UPDATE tb_visitante SET 
        nm_visitante = :nm_visitante,
        documento_visitante =  :documento_visitante, 
        telefone_um_visitante = :telefone_um_visitante,
        telefone_dois_visitante = :telefone_dois_visitante
        WHERE id_visitante = :id_visitante;");

        $this->db->bind("nm_visitante", trim($dados['txtNome']));
        $this->db->bind("documento_visitante", trim($dados['txtDocumento']));
        $this->db->bind("telefone_um_visitante", $dados['txtTelefoneUm']);
        $this->db->bind("telefone_dois_visitante", $dados['txtTelefoneDois']);
        $this->db->bind("id_visitante", $dados['idVisitante']);

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
}
