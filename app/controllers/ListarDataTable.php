<?php

class ListarDataTable extends Controller
{
    private $model;
    //Construtor do model do Usuário que fará o acesso ao banco
    public function __construct()
    {
        $this->model = $this->model("DataTablesModel");
    }

    public function listarRegistrosDataTable()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        // Recebe parâmetros do DataTables
        $inicio = $data['start'];
        $quantidade = $data['length'];
        $valorBusca = $data['search'];
        $colunaOrdenacao = $data['order'][0]['column'];
        $direcaoOrdenacao = $data['order'][0]['dir'];

        $tabela = $data['tabela'];
        $listaColunasPesquisa = $data['colunas_pesquisa'];
        $listaColunaOrdenacao = $data['colunas_ordenacao'];

        $joins = isset($data['joins']) ? $data['joins'] : [];

        // Consulta total de registros sem filtros
        $totalRegistros = $this->model->contarRegistros($tabela);

        // Consulta com filtros e ordenação
        $visitantes = $this->model->obterRegistrosPaginados(
            $inicio,
            $quantidade,
            $valorBusca,
            $colunaOrdenacao,
            $direcaoOrdenacao,
            $tabela,
            $listaColunasPesquisa,
            $listaColunaOrdenacao,
            $joins
        );

        $totalRegistrosFiltrados = $this->model->obterRegistrosComFiltro(
            $valorBusca,
            $colunaOrdenacao,
            $tabela,
            $direcaoOrdenacao,
            $listaColunasPesquisa,
            $listaColunaOrdenacao,
            $joins
        );

        // Formata a resposta
        $resposta = [
            "draw" => intval($data['draw']),
            "recordsTotal" => $totalRegistros,
            "recordsFiltered" => count($totalRegistrosFiltrados), // Pode ajustar conforme os filtros
            "data" => $visitantes
        ];

        // Envia como JSON
        header('Content-Type: application/json');
        echo json_encode($resposta);
    }

}