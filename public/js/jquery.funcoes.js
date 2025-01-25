function initDataTable(tableId, params) {
    $(document).ready(function () {
        $('#' + tableId).DataTable({
            "lengthMenu": [10, 15, 25],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.5/i18n/pt-BR.json"
            },
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "/ListarDataTable/listarRegistrosDataTable",
                "type": "POST",
                "data": function (d) {
                    // Monta o objeto a ser enviado
                    var requestData = {
                        tabela: params.tabela,
                        colunas_pesquisa: params.colunas_pesquisa,
                        colunas_ordenacao: params.colunas_ordenacao,
                        start: d.start,
                        length: d.length,
                        search: d.search.value,
                        order: d.order,
                        draw: d.draw,
                        joins: params.joins
                    };
                    return JSON.stringify(requestData); // Converte para JSON
                },
                "contentType": "application/json; charset=utf-8", // Define o tipo de conteúdo
                "dataType": "json" // Espera receber JSON
            },
            "columns": params.columns,
            "responsive": true
        });
    });
}