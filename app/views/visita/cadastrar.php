<div class="row">
    <div class="col-sm-10 mx-auto p-5">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URL . 'Visitas/visualizarVisitasEmAndamento' ?>">Visitas em Andamento</a></li>
                <li class="breadcrumb-item active" aria-current="page">Nova visita</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-body">
                <h2>Nova visita</h2>
                <p class="mb-3 text-muted">Preencha o formulário abaixo para cadastrar uma nova visita</p class="mb-3 text-muted">

                <form name="cadastrar" method="POST" action="<?= URL . 'Visitas/cadastrar/' . $dados['idVisitante'] ?>">

                    <div class="row">
                        <div class="mb-3 col-sm-6">
                            <label for="txtNome" class="form-label">Nome Visita:</label>
                            <input type="text" class="form-control" name="txtNome" id="txtNome"
                                value="<?= $dados['visitante']->nm_visitante ?>" readonly>
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="txtDocumento" class="form-label">Documento:</label>
                            <input type="text" class="form-control" name="txtDocumento" id="txtDocumento"
                                value="<?= $dados['visitante']->documento_visitante ?>" maxlength="11" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-sm-6">
                            <label for="numQtdPessoasCarro" class="form-label">Quantidade pessoas no carro:</label>
                            <input type="text" class="form-control" name="numQtdPessoasCarro" id="numQtdPessoasCarro"
                                maxlength="20">
                        </div>

                        <div class="mb-3 col-sm-6">
                            <label for="cboPlacaVeiculo" class="form-label">Placa visitante:</label>
                            <select class="form-select" name="cboPlacaVeiculo" id="cboPlacaVeiculo">
                                <option value="NULL"></option>
                                <?php foreach ($dados['veiculos'] as $veiculo) { ?>
                                    <option value="<?= $veiculo->id_veiculo ?>">
                                        <?= $veiculo->ds_placa_veiculo ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-sm-6">
                            <label for="cboTipoVisita" class="form-label <?= $dados['cboTipoVisita_erro'] ? 'is-invalid' : '' ?>">Tipo Visita: *</label>
                            <select class="form-select" name="cboTipoVisita" id="cboTipoVisita">
                                <option value=""></option>
                                <?php foreach ($dados['tipoVisita'] as $tipoVisita) { ?>
                                    <option value="<?= $tipoVisita->id_tipo_visita ?>">
                                        <?= $tipoVisita->ds_tipo_visita ?></option>
                                <?php } ?>
                            </select>
                            <div class="invalid-feedback"><?= $dados['cboTipoVisita_erro'] ?></div>
                        </div>

                        <div class="mb-3 col-sm-6">
                            <label for="cboCasas" class="form-label <?= $dados['cboCasas_erro'] ? 'is-invalid' : '' ?>">N° casa a ser visitada: *</label>
                            <select class="form-select" name="cboCasas" id="cboCasas">
                                <option value=""></option>
                                <?php foreach ($dados['listaCasas'] as $casa) { ?>
                                    <option value="<?= $casa->id_casa ?>">
                                        <?= $casa->ds_numero_casa ?></option>
                                <?php } ?>
                            </select>
                            <div class="invalid-feedback"><?= $dados['cboCasas_erro'] ?></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-group col-md-12">
                            <label for="txtObervacao">Obervação</label>
                            <textarea class="form-control" name="txtObervacao" id="txtObervacao" rows="3"
                                placeholder="Digite a observação"></textarea>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="p-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-check-square-fill"></i> Registrar
                                Entrada
                            </button>
                        </div>
                        <div class="p-2">
                            <a class="btn btn-secondary" href="<?= URL . 'Visitantes/visualizarVisitantes' ?>"
                                role="button">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>