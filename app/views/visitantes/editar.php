<script>
    let contador = 0; // Para novos campos de veículos

    function adicionarCampos(tipo = "", placa = "", cor = "", idVeiculo = "") {
        contador++;

        const novoCampo = $(`
                <div class="campo-veiculo" id="veiculo_${contador}">
                    <input type="hidden" name="veiculo_id_${contador}" value="${idVeiculo}">
                    <div class="mb-3">
                        <label class="form-label" >Tipo de Veículo:</label>
                        <select class="form-select" name="tipo_veiculo_${contador}">
                            <?php foreach ($dados['listaTiposVeiculos'] as $tipoVeiculo): ?>
                                        <option value="<?= $tipoVeiculo->id_tipo_veiculo ?>" 
                                            ${tipo == <?= $tipoVeiculo->id_tipo_veiculo ?> ? "selected" : ""}>
                                            <?= $tipoVeiculo->ds_tipo_veiculo ?>
                                        </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" >Placa do Veículo:</label>
                        <input class="form-control" type="text" name="placa_veiculo_${contador}" value="${placa}" placeholder="Digite a placa do veículo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" >Cor do Veículo:</label>
                        <select class="form-select" name="cor_veiculo_${contador}">
                            <?php foreach ($dados['listaCoresVeiculos'] as $corVeiculo): ?>
                                        <option value="<?= $corVeiculo->id_cor_veiculo ?>" 
                                            ${cor == <?= $corVeiculo->id_cor_veiculo ?> ? "selected" : ""}>
                                            <?= $corVeiculo->ds_cor_veiculo ?>
                                        </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button class="btn btn-danger" type="button" class="remover-veiculo" onclick="removerCampo(${contador})"><i class="bi bi-trash-fill"></i> Remover</button>
                    <hr>
                </div>
            `);

        $("#veiculosContainer").append(novoCampo);
    }

    function carregarVeiculosExistentes() {
        <?php if (isset($dados['veiculosVisitante']) && !empty($dados['veiculosVisitante'])) {
            foreach ($dados['veiculosVisitante'] as $veiculo) { ?>
                adicionarCampos(
                    "<?= $veiculo->id_tipo_veiculo ?>",
                    "<?= $veiculo->ds_placa_veiculo ?>",
                    "<?= $veiculo->id_cor_veiculo ?>",
                    "<?= $veiculo->id_veiculo ?>"
                );
        <?php }
        }; ?>
    }

    function removerCampo(id) {
        // Seleciona o campo a ser removido pelo ID e o remove do DOM
        const campo = document.getElementById(`veiculo_${id}`);
        if (campo) {
            campo.remove();
        }
    }

    $(document).ready(function() {
        // Carregar veículos existentes ao carregar a página
        carregarVeiculosExistentes();

        // Botão para adicionar novo veículo
        $("#adicionarVeiculo").click(function() {
            adicionarCampos();
        });

        // Remover veículo ao clicar no botão "Remover"
        $(document).on("click", ".remover-veiculo", function() {
            const id = $(this).data("id");
            $(`#veiculo_${id}`).remove();
        });
    });
</script>

<div class="col-sm-10 mx-auto p-5">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= URL . 'Visitantes/visualizarVisitantes' ?>">Visitantes</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= ucfirst($dados['visitante']->nm_visitante) ?>
            </li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-body">
            <h2>Editar Visitante</h2>
            <small>Preencha o formulário abaixo para editar o visitante</small>

            <form name="editar" method="POST"
                action="<?= URL . 'Visitantes/editarVisitante/' . $dados['visitante']->id_visitante ?>">
                <div class="mb-3 mt-4">
                    <label for="txtNome" class="form-label">Nome: *</label>
                    <input type="text" class="form-control <?= $dados['nome_erro'] ? 'is-invalid' : '' ?>"
                        name="txtNome" id="txtNome" value="<?= trim($dados['visitante']->nm_visitante) ?>">
                    <div class="invalid-feedback"><?= $dados['nome_erro'] ?></div>
                </div>
                <div class="mb-3">
                    <label for="txtDocumento" class="form-label">Documento: * <span style="color: gray;">(apenas
                            números)</span></label>
                    <input type="text" class="form-control <?= $dados['documento_erro'] ? 'is-invalid' : '' ?>"
                        name="txtDocumento" id="txtDocumento"
                        value="<?= trim($dados['visitante']->documento_visitante) ?>" maxlength="11">
                    <div class="invalid-feedback"><?= $dados['documento_erro'] ?></div>
                </div>
                <div class="mb-3">
                    <label for="txtTelefoneUm" class="form-label">Telefone 1: <span style="color: gray;">(apenas
                            números)</span></label>
                    <input type="text" class="form-control" name="txtTelefoneUm" id="txtTelefoneUm"
                        value="<?= trim($dados['visitante']->telefone_um_visitante) ?>" maxlength="11">

                </div>
                <div class="mb-4">
                    <label for="txtTelefoneDois" class="form-label">Telefone 2: <span style="color: gray;">(apenas
                            números)</span></label>
                    <input type="text" class="form-control" name="txtTelefoneDois" id="txtTelefoneDois"
                        value="<?= trim($dados['visitante']->telefone_dois_visitante) ?>" maxlength="11">

                </div>

                <h2>Veículos Cadastrados</h2>

                <div id="veiculosContainer">
                    <!-- Carregar veículos já existentes aqui -->
                </div>
                <button class="btn btn-secondary mb-3" type="button" onclick="adicionarCampos()"><i
                        class="bi bi-plus-circle"></i> Adicionar
                    Veículo</button>


                <div class="d-flex">
                    <div class="p-2">
                        <input type="submit" value="Salvar" class="btn btn-primary">
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