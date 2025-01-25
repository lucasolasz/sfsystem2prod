<?php
class TipoVisitaModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function recuperarTodosTipoVisita()
    {
        $this->db->query("SELECT * FROM tb_tipo_visita ORDER BY ds_tipo_visita");
        return $this->db->resultados();
    }
}
