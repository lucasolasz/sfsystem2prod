<?php

class VisitaModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }


    public function armazenarVisita($dados)
    {

        $query = " INSERT INTO tb_visita ";
        $query .= " (fk_visitante, 
        fk_veiculo,
        fk_tipo_visita, 
        fk_usuario_entrada, 
        dt_entrada_visita, 
        dt_hora_entrada_visita, 
        qt_pessoas_carro, 
        fk_casa, 
        observacao_visita) VALUES ";
        $query .= "(:fk_visitante,
        :fk_veiculo,
        :fk_tipo_visita,
        :fk_usuario_entrada,
        :dt_entrada_visita, 
        :dt_hora_entrada_visita,
        :qt_pessoas_carro,
        :fk_casa,
        :observacao_visita
        )";

        $this->db->query($query);

        $this->db->bind("fk_visitante", intval($dados['fk_visitante']));
        $this->db->bind("fk_veiculo", intval($dados['fk_veiculo']));
        $this->db->bind("fk_usuario_entrada", intval($dados['fk_usuario_entrada']));
        $this->db->bind("fk_tipo_visita", intval($dados['fk_tipo_visita']));
        $this->db->bind("dt_entrada_visita", $dados['dt_entrada_visita']);
        $this->db->bind("dt_hora_entrada_visita", $dados['dt_hora_entrada_visita']);
        $this->db->bind("qt_pessoas_carro", trim($dados['qt_pessoas_carro']));
        $this->db->bind("fk_casa", intval($dados['fk_casa']));
        $this->db->bind("observacao_visita", $dados['observacao_visita']);

        $this->db->executa();
    }

    public function visualizarVisitasEmAndamento()
    {
        $query = " SELECT * FROM tb_visita tv ";
        $query .= " LEFT JOIN tb_usuario as tus ON tus.id_usuario = tv.fk_usuario_entrada";
        $query .= " LEFT JOIN tb_tipo_visita as tvista ON tvista.id_tipo_visita = tv.fk_tipo_visita ";
        $query .= " LEFT JOIN tb_visitante as tvis ON tvis.id_visitante = tv.fk_visitante ";
        $query .= " LEFT JOIN tb_veiculo as tve ON tve.id_veiculo = tv.fk_veiculo ";
        $query .= " LEFT JOIN tb_tipo_veiculo as ttv ON ttv.id_tipo_veiculo = tve.fk_tipo_veiculo ";
        $query .= " LEFT JOIN tb_cor_veiculo as tcv ON tcv.id_cor_veiculo = tve.fk_cor_veiculo ";
        $query .= " LEFT JOIN tb_casa as tca ON tca.id_casa = tv.fk_casa ";

        $query .= "WHERE dt_saida_visita IS NULL AND dt_hora_saida_visita IS NULL ORDER BY dt_entrada_visita asc, dt_hora_entrada_visita asc";

        $this->db->query($query);
        return $this->db->resultados();
    }

    public function registrarSaida($dados)
    {
        $query = " UPDATE tb_visita SET ";
        $query .= " fk_usuario_saida = :fk_usuario_saida, ";
        $query .= " dt_saida_visita = :dt_saida_visita, ";
        $query .= " dt_hora_saida_visita = :dt_hora_saida_visita ";
        $query .= " WHERE fk_visitante = :fk_visitante ";

        $this->db->query($query);

        $this->db->bind("fk_visitante", $dados['fk_visitante']);
        $this->db->bind("fk_usuario_saida", $dados['fk_usuario_saida']);
        $this->db->bind("dt_saida_visita", $dados['dt_saida_visita']);
        $this->db->bind("dt_hora_saida_visita", $dados['dt_hora_saida_visita']);

        $this->db->executa();
    }

    public function verificarSeExisteVisitaEmAndamentoPorIdVisitante($idVisitante)
    {
        $query = " SELECT * FROM tb_visita tv ";
        $query .= " WHERE fk_visitante = :idVisitante ";
        $query .= " AND (dt_saida_visita IS NULL AND dt_hora_saida_visita IS NULL)";

        $this->db->query($query);

        $this->db->bind("idVisitante", intval($idVisitante));
        $this->db->executa();
        return $this->db->totalResultados();
    }
}
