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
                    <button class="btn btn-primary"
                        onclick="window.location.href='<?= URL . 'Moradores/cadastrarMoradorPorIdUsuario/' . $_SESSION['id_usuario'] ?>'"
                        <?= $dados['novoDisabled'] ?>>
                        <i class="bi bi-plus-circle-fill"></i> Novo
                    </button>
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
                            <th scope="col">Nome Locatário</th>
                            <th scope="col">Telefone Locatário</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dados['morador'])) { ?>

                            <tr>
                                <td colspan="7" class="align-middle">Nenhum cadastro encontrado</td>
                            </tr>

                        <?php } ?>

                        <?php foreach ($dados['morador'] as $morador) { ?>
                            <tr>
                                <td><?= ucfirst($morador->nm_morador) ?></td>
                                <td><?= ucfirst($morador->ds_numero_casa) ?></td>
                                <td><?= ucfirst($morador->tel_um_morador) ?></td>
                                <td><?= ucfirst($morador->tel_emergencia) ?></td>
                                <td><?= ucfirst($morador->nm_locatario) ?></td>
                                <td><?= ucfirst($morador->tel_um_locatario) ?></td>

                                <td>
                                    <a href="<?= URL . 'Moradores/editarMoradorPorIdMorador/' . $morador->id_morador ?>"
                                        class="btn btn-warning"><i class="bi bi-pencil-square"></i> Editar</a>

                                    <a href="<?= URL . 'Moradores/deletarMoradorPorIdUsuario/' . $morador->id_morador ?>"
                                        class="btn btn-danger"><i class="bi bi-trash-fill"></i> Exlcuir</a>
                                    <a href="<?= URL . 'GerarPdf/gerarPdfMorador/' . $morador->id_morador ?>" class="btn btn-secondary" target="_blank">
                                        <i class="bi bi-printer"></i> Imprimir
                                    </a>    
                                </td>
                            <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>