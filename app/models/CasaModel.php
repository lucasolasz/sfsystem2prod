<?php

class CasaModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }


    public function reuperarTodasCasas()
    {
        $this->db->query("SELECT * FROM tb_casa ORDER BY ds_numero_casa");
        return $this->db->resultados();
    }

    public function reuperarTodasCasasNaoCadastradas()
    {
        $this->db->query("SELECT * FROM tb_casa tc
                WHERE tc.id_casa NOT IN (SELECT DISTINCT(fk_casa) FROM tb_usuario WHERE fk_casa IS NOT NULL)");
        return $this->db->resultados();
    }

    public function recuperarTodasCasasNaoCadastradasMoradores()
    {
        $this->db->query("SELECT * FROM tb_casa tc
                WHERE tc.id_casa NOT IN (SELECT DISTINCT(fk_casa) FROM tb_morador WHERE fk_casa IS NOT NULL)");
        return $this->db->resultados();
    }
}
