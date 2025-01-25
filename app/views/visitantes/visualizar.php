<div class="container py-5">

    <?= Alertas::mensagem('visitante') ?>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= URL . 'Paginas/index' ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Visitantes</li>
        </ol>
    </nav>

    <div class="card">

        <div class="card-header">

            <h5 class="tituloIndex">Visitantes
                <div style="float: right;">
                    <a href="<?= URL . 'Visitantes/cadastrar' ?>" class="btn btn-primary"><i
                            class="bi bi-plus-circle-fill"></i> Novo</a>
                </div>
            </h5>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabela" class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <th scope="col">Documento</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    var urlSistema = <?= URL ?>;
    var params = {
        url: urlSistema,
        tabela: 'tb_visitante',
        colunas_pesquisa: ['nm_visitante', 'documento_visitante'],
        colunas_ordenacao: ['nm_visitante'],
        joins: [],
        columns: [{
            "data": "nm_visitante",
            "render": function (data, type, row) {
                var maxLength = 50; // Define o número máximo de caracteres
                if (data.length > maxLength) {
                    return data.substring(0, maxLength) + '...';
                }
                return data;
            }
        },
        {
            "data": "documento_visitante"
        },
        {
            "data": null, // Define como null pois será preenchido manualmente
            "orderable": false, // Impede ordenação para esta coluna
            "render": function (data, type, row) {
                // Retorna o HTML para os botões de ação, com href dinâmico
                return `
                    <a href="${urlSistema}Visitantes/editarVisitante/${row.id_visitante}" class="btn btn-warning">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>
                    <a href="${urlSistema}Visitas/carregarTelaCadastroVisita/${row.id_visitante}" class="btn btn-success">
                        <i class="bi bi-arrow-up-circle-fill"></i> Entrada Visita
                    </a>`;
            }
        }
        ]
    };

    // Chama a função passando o ID da tabela e os parâmetros configurados
    initDataTable('tabela', params);
</script>