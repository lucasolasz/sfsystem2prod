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
}
