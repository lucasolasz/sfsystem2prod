<div class="container py-5">

    <?= Alertas::mensagem('morador') ?>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= URL . 'Paginas/index' ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Moradores</li>
        </ol>
    </nav>

    <div class="card">

        <div class="card-header">

            <h5 class="tituloIndex">Moradores
                <div style="float: right;">
                    <a href="<?= URL . 'Moradores/cadastrar' ?>" class="btn btn-primary"><i class="bi bi-plus-circle-fill"></i> Novo</a>
                </div>
            </h5>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabela" class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nome Morador</th>
                            <th scope="col">N° Casa</th>
                            <th scope="col">Telefone</th>
                            <th scope="col">Telefone Emergência</th>
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
    var urlSistema = '<?= URL ?>';
    var params = {
        url: urlSistema,
        tabela: 'tb_morador', //tabela que será utilizada para pesquisa
        colunas_pesquisa: ['nm_morador'], //coluna para a busca dentro da tabela. Input Search
        colunas_ordenacao: ['nm_morador'], //colunas utilizadas para ordenação
        joins: [{
            tabela: 'tb_casa',
            condicao: 'tb_morador.fk_casa = tb_casa.id_casa'
        }], // Joins se necessário
        columns: [{
                "data": "nm_morador",
                "render": function(data, type, row) {
                    var maxLength = 50; // Define o número máximo de caracteres
                    if (data.length > maxLength) {
                        return data.substring(0, maxLength) + '...';
                    }
                    return data;
                }
            },
            {
                "data": "ds_numero_casa"
            },
            {
                "data": "tel_um_morador"
            },
            {
                "data": "tel_emergencia"
            },
            {
                "data": null, // Define como null pois será preenchido manualmente
                "orderable": false, // Impede ordenação para esta coluna
                "render": function(data, type, row) {
                    // Retorna o HTML para os botões de ação, com href dinâmico
                    return `
                    <a href="${urlSistema}Moradores/editarMorador/${row.id_morador}" class="btn btn-warning">
                        <i class="bi bi-pencil-square"></i> Editar
                    </a>
                    <a href="${urlSistema}Moradores/deletarMorador/${row.id_morador}" class="btn btn-danger">
                        <i class="bi bi-trash-fill"></i> Excluir
                    </a>`;
                }
            }
        ] // Colunas que irão se adequar as colunas definidas no html
    };

    // Chama a função passando o ID da tabela e os parâmetros configurados
    initDataTable('tabela', params);
</script>