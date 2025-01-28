<?php
class DataTablesModel
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function contarRegistros($tabela = null)
    {
        $this->db->query("SELECT COUNT(*) AS total FROM $tabela");
        return $this->db->resultado()->total;
    }

    public function obterRegistrosComFiltro(
        $busca = '',
        $colunaOrdenacao = 0,
        $tabela = null,
        $direcaoOrdenacao = 'asc',
        $listaColunasPesquisa = null,
        $listaColunaOrdenacao = null,
        $joins = []
    ) {

        $consulta = " SELECT * FROM $tabela ";

        if (!empty($joins)) {
            foreach ($joins as $join) {
                // Verifica se cada item de join contém 'tabela' e 'condicao' para garantir que o JOIN seja válido
                if (isset($join['tabela']) && isset($join['condicao'])) {
                    $consulta .= " JOIN " . $join['tabela'] . " ON " . $join['condicao'];
                }
            }
        }

        if (!empty($busca)) {
            $consulta .= " WHERE 1=1";
            $consulta .= $this->montarQueryBusca($listaColunasPesquisa);
        }

        // Adicionar a cláusula ORDER BY
        if (!empty($listaColunaOrdenacao)) {
            $consulta .= " ORDER BY ";
            foreach ($listaColunaOrdenacao as $coluna) {
                $consulta .= " $coluna,";
            }
            // Remover a última vírgula e adicionar a direção de ordenação
            $consulta = rtrim($consulta, ',') . " $direcaoOrdenacao";
        }

        $this->db->query($consulta);

        if (!empty($busca)) {
            $this->db->bind("busca", '%' . $busca . '%');
        }

        return $this->db->resultados();
    }

    public function obterRegistrosPaginados(
        $inicio = null,
        $quantidade = null,
        $busca = '',
        $colunaOrdenacao = 0,
        $direcaoOrdenacao = 'asc',
        $tabela = null,
        $listaColunasPesquisa = null,
        $listaColunaOrdenacao = null,
        $joins = []
    ) {

        $consulta = " SELECT * FROM $tabela ";

        if (!empty($joins)) {
            foreach ($joins as $join) {
                // Verifica se cada item de join contém 'tabela' e 'condicao' para garantir que o JOIN seja válido
                if (isset($join['tabela']) && isset($join['condicao'])) {
                    $consulta .= " LEFT JOIN " . $join['tabela'] . " ON " . $join['condicao'];
                }
            }
        }

        if (!empty($busca)) {
            $consulta .= " WHERE 1=1 ";
            $consulta .= $this->montarQueryBusca($listaColunasPesquisa);
        }

        // $consulta .= " ORDER BY $colunaOrdenada $direcaoOrdenacao LIMIT :inicio, :quantidade";
        $consulta .= $this->montarQueryOderBy($listaColunaOrdenacao, $direcaoOrdenacao);

        $this->db->query($consulta);
        $this->db->bind("inicio", (int) $inicio, PDO::PARAM_INT);
        $this->db->bind("quantidade", (int) $quantidade, PDO::PARAM_INT);
        if (!empty($busca)) {
            // $consulta .= " AND (nm_visitante LIKE :busca)";
            $this->db->bind("busca", '%' . $busca . '%');
        }

        return $this->db->resultados();
    }

    private function montarQueryOderBy($listaColunaOrdenacao, $direcaoOrdenacao)
    {
        $stringSaida = " ORDER BY ";
        foreach ($listaColunaOrdenacao as $coluna) {
            $stringSaida .= "$coluna ,";
        }
        $stringSaida = substr($stringSaida, 0, -1);
        $stringSaida .= " $direcaoOrdenacao LIMIT :inicio, :quantidade ";

        return $stringSaida;
    }

    private function montarQueryBusca($listaColunas)
    {
        $stringSaida = " AND (";
        $condicoes = [];
        foreach ($listaColunas as $coluna) {
            $condicoes[] = "$coluna LIKE :busca";
        }
        $stringSaida .= implode(" OR ", $condicoes);
        $stringSaida .= ")";

        return $stringSaida;
    }

}