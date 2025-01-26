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
                    <button class="btn btn-danger" type="button" class="remover-veiculo" onclick="removerCampo(${contador})"><i class="bi bi-trash-fill"></i> Remover Veículo</button>
                    <hr>
                </div>
            `);

        $("#veiculosContainer").append(novoCampo);
    }

    function carregarVeiculosExistentes() {
        <?php if (isset($dados['veiculosMorador']) && !empty($dados['veiculosMorador'])) {
            foreach ($dados['veiculosMorador'] as $veiculo) { ?>
                adicionarCampos(
                    "<?= $veiculo->id_tipo_veiculo ?>",
                    "<?= $veiculo->ds_placa_veiculo ?>",
                    "<?= $veiculo->id_cor_veiculo ?>",
                    "<?= $veiculo->id_veiculo ?>"
                );
            <?php }
        } ?>
    }

    function removerCampo(id) {
        // Seleciona o campo a ser removido pelo ID e o remove do DOM
        const campo = document.getElementById(`veiculo_${id}`);
        if (campo) {
            campo.remove();
        }
    }

    function esconderCampoPets() {

        const petsIsChecked = document.getElementById("chkPossuiPets").checked;

        const campoPets = document.querySelectorAll("#qtdPets");

        campoPets.forEach(campo => {
            campo.disabled = !petsIsChecked;
        });

    }

    function esconderCampoAdesivo() {
        const adesivoIsChecked = document.getElementById("chkRecebeuAdesivo").checked;

        const camposAdesivo = document.querySelectorAll("#qtdAdesivos");

        camposAdesivo.forEach(campo => {
            campo.disabled = !adesivoIsChecked;
        });


    }

    $(document).ready(function () {
        // Carregar veículos existentes ao carregar a página
        carregarVeiculosExistentes();

        // Botão para adicionar novo veículo
        $("#adicionarVeiculo").click(function () {
            adicionarCampos();
        });

        // Remover veículo ao clicar no botão "Remover"
        $(document).on("click", ".remover-veiculo", function () {
            const id = $(this).data("id");
            $(`#veiculo_${id}`).remove();
        });


        if ($('#chkLocatario').is(':checked')) {
            $('#divLocatario').show(); // Exibe a div se o checkbox estiver marcado
        } else {
            $('#divLocatario').hide(); // Esconde a div se o checkbox estiver desmarcado
        }

        // Evento para exibir/ocultar a div ao clicar no checkbox
        $('#chkLocatario').on('change', function () {
            if ($(this).is(':checked')) {
                $('#divLocatario').slideDown(); // Exibe a div com animação
            } else {
                $('#divLocatario').slideUp(); // Esconde a div com animação
            }
        });


        if ($('#chkPossuiPets').is(':checked')) {
            $('#divQuantidadePets').show(); // Exibe a div se o checkbox estiver marcado
        } else {
            $('#divQuantidadePets').hide();
        }

        // Evento para exibir/ocultar a div ao clicar no checkbox
        $('#chkPossuiPets').on('change', function () {
            if ($(this).is(':checked')) {
                $('#divQuantidadePets').slideDown(); // Exibe a div com animação
            } else {
                $('#divQuantidadePets').slideUp(); // Esconde a div com animação
            }
        });

        if ($('#chkRecebeuAdesivo').is(':checked')) {
            $('#divQuantidadeAdesivos').show(); // Exibe a div se o checkbox estiver marcado
        } else {
            $('#divQuantidadeAdesivos').hide();
        }

        // Evento para exibir/ocultar a div ao clicar no checkbox
        $('#chkRecebeuAdesivo').on('change', function () {
            if ($(this).is(':checked')) {
                $('#divQuantidadeAdesivos').slideDown(); // Exibe a div com animação
            } else {
                $('#divQuantidadeAdesivos').slideUp(); // Esconde a div com animação
            }
        });


    });
</script>

<div class="row">
    <div class="col-sm-10 mx-auto p-5">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="<?= URL . 'Moradores/visualizarMoradorPorIdUsuario/' . $dados['morador']->fk_usuario ?>">Moradores</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Casa número:
                    <?= ucfirst($dados['morador']->ds_numero_casa) ?>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <h2>Editar Morador Casa: <?= ucfirst($dados['morador']->ds_numero_casa) ?></h2>
                <p class="mb-3 text-muted">Preencha o formulário abaixo para editar o morador</p>

                <hr>
                <h3>Dados do proprietário</h3>

                <form name="editar" method="POST"
                    action="<?= URL . 'Moradores/editarMoradorPorIdMorador/' . $dados['idMorador'] ?>">
                    <div class="row">
                        <div class="mb-3 col-sm-6">
                            <label for="txtNomeProprietario" class="form-label">Nome Completo do proprietário: *</label>
                            <input type="text"
                                class="form-control <?= $dados['nomeProprietario_erro'] ? 'is-invalid' : '' ?>"
                                name="txtNomeProprietario" id="txtNomeProprietario"
                                value="<?= trim($dados['morador']->nm_morador) ?>" maxlength="255">
                            <!-- Div para exibir o erro abaixo do campo -->
                            <div class="invalid-feedback"><?= $dados['nomeProprietario_erro'] ?></div>
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="txtDocumentoProprietario" class="form-label">Documento do proprietário:
                                *</label>
                            <input type="text" placeholder="Somente números"
                                class="form-control <?= $dados['documentoProprietario_erro'] ? 'is-invalid' : '' ?>"
                                name="txtDocumentoProprietario" id="txtDocumentoProprietario"
                                value="<?= trim($dados['morador']->documento_morador) ?>" maxlength="11">
                            <!-- Div para exibir o erro abaixo do campo -->
                            <div class="invalid-feedback"><?= $dados['documentoProprietario_erro'] ?></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-sm-6">
                            <label for="dateNascimentoProprietario" class="form-label">Data Nascimento do Proprietário:
                                *</label>
                            <input type="date"
                                class="form-control <?= $dados['dataNascimentoProprieratio_erro'] ? 'is-invalid' : '' ?>"
                                name="dateNascimentoProprietario" id="dateNascimentoProprietario"
                                value="<?= trim($dados['morador']->dt_nascimento_morador) ?>">
                            <!-- Div para exibir o erro abaixo do campo -->
                            <div class="invalid-feedback"><?= $dados['dataNascimentoProprieratio_erro'] ?></div>
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="txtEmailProprietario" class="form-label">E-mail do Proprietário: *</label>
                            <input type="text"
                                class="form-control <?= $dados['emailProprietario_erro'] ? 'is-invalid' : '' ?>"
                                name="txtEmailProprietario" id="txtEmailProprietario"
                                value="<?= trim($dados['morador']->email_morador) ?>" maxlength="100">
                            <div class="invalid-feedback"><?= $dados['emailProprietario_erro'] ?></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-sm-6">
                            <label for="txtTelefoneUmProprietario" class="form-label">Telefone 1: *</label>
                            <input type="text" placeholder="Somente números"
                                class="form-control <?= $dados['telefone_um_proprietario_erro'] ? 'is-invalid' : '' ?>"
                                name="txtTelefoneUmProprietario" id="txtTelefoneUmProprietario"
                                value="<?= trim($dados['morador']->tel_um_morador) ?>" maxlength="11">
                            <div class="invalid-feedback"><?= $dados['telefone_um_proprietario_erro'] ?></div>
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="txtTelefoneDoisProprietario" class="form-label">Telefone 2:</label>
                            <input type="text" placeholder="Somente números" class="form-control"
                                name="txtTelefoneDoisProprietario" id="txtTelefoneDoisProprietario"
                                value="<?= trim($dados['morador']->tel_dois_morador) ?>" maxlength="11">

                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-sm-6">
                            <label for="txtTelefoneEmergenciaProprietario" class="form-label">Telefone
                                Emergência:</label>
                            <input type="text" placeholder="Somente números" class="form-control"
                                name="txtTelefoneEmergenciaProprietario" id="txtTelefoneEmergenciaProprietario"
                                value="<?= trim($dados['morador']->tel_emergencia) ?>" maxlength="11">
                        </div>

                        <div class="mb-3 col-sm-6">
                            <label for="txtNumeroCasa" class="form-label">N° Casa: </label>
                            <input type="text" class="form-control " name="txtNumeroCasa" id="txtNumeroCasa"
                                value="<?= $dados['morador']->ds_numero_casa ?>" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="txtDetalhes">Outros Detalhes. Por exemplo: Dados de pessoas que moram na residência:</label>
                        <textarea class="form-control" id="txtDetalhes" name="txtDetalhes" style="height: 100px" maxlength="2500"><?= trim($dados['morador']->ds_detalhes) ?></textarea>
                    </div>

                    <hr>

                    <div class="row mt-4">
                        <div class="mb-3 col-sm-6">
                            <div class="form-check">
                                <?php
                                $checkedLocatario = '';

                                if (!empty($dados['chkLocatario'])) {
                                    if ($dados['chkLocatario'] == 'S') {
                                        $checkedLocatario = 'checked';
                                    }
                                }

                                if ($dados['morador']->flag_locatario == 'S') {
                                    $checkedLocatario = 'checked';
                                }
                                ?>
                                <input class="form-check-input" type="checkbox" value="S" id="chkLocatario"
                                    name="chkLocatario" <?= $checkedLocatario ?>>
                                <label class="form-check-label" for="chkLocatario">
                                    Imóvel Locado?
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="divLocatario">

                        <h3>Dados do locatário</h3>

                        <div class="row">
                            <div class="mb-3 col-sm-6">
                                <label for="txtNomeLocatario" class="form-label">Nome Completo do locatário: *</label>
                                <input type="text"
                                    class="form-control <?= $dados['nomeLocatario_erro'] ? 'is-invalid' : '' ?>"
                                    name="txtNomeLocatario" id="txtNomeLocatario"
                                    value="<?= trim($dados['morador']->nm_locatario) ?>" maxlength="255">
                                <!-- Div para exibir o erro abaixo do campo -->
                                <div class="invalid-feedback"><?= $dados['nomeLocatario_erro'] ?></div>
                            </div>
                            <div class="mb-3 col-sm-6">
                                <label for="txtDocumentoLocatario" class="form-label">Documento do proprietário:
                                    *</label>
                                <input type="text" placeholder="Somente números"
                                    class="form-control <?= $dados['documentoLocatario_erro'] ? 'is-invalid' : '' ?>"
                                    name="txtDocumentoLocatario" id="txtDocumentoLocatario"
                                    value="<?= trim($dados['morador']->documento_locatario) ?>" maxlength="11">
                                <!-- Div para exibir o erro abaixo do campo -->
                                <div class="invalid-feedback"><?= $dados['documentoLocatario_erro'] ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-sm-6">
                                <label for="dateNascimentoLocatario" class="form-label">Data Nascimento do Locatário:
                                    *</label>
                                <input type="date"
                                    class="form-control <?= $dados['dataNascimentoLocatario_erro'] ? 'is-invalid' : '' ?>"
                                    name="dateNascimentoLocatario" id="dateNascimentoLocatario"
                                    value="<?= trim($dados['morador']->dt_nascimento_locatario) ?>">
                                <!-- Div para exibir o erro abaixo do campo -->
                                <div class="invalid-feedback"><?= $dados['dataNascimentoLocatario_erro'] ?></div>
                            </div>
                            <div class="mb-3 col-sm-6">
                                <label for="txtEmailLocatario" class="form-label">E-mail do Locatário: *</label>
                                <input type="text"
                                    class="form-control <?= $dados['emailLocatario_erro'] ? 'is-invalid' : '' ?>"
                                    name="txtEmailLocatario" id="txtEmailLocatario"
                                    value="<?= trim($dados['morador']->email_locatario) ?>" maxlength="100">
                                <div class="invalid-feedback"><?= $dados['emailLocatario_erro'] ?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-sm-6">
                                <label for="txtTelefoneUmLocatario" class="form-label">Telefone 1: *</label>
                                <input type="text" placeholder="Somente números"
                                    class="form-control <?= $dados['telefone_um_locatario_erro'] ? 'is-invalid' : '' ?>"
                                    name="txtTelefoneUmLocatario" id="txtTelefoneUmLocatario"
                                    value="<?= trim($dados['morador']->tel_um_locatario) ?>" maxlength="11">
                                <div class="invalid-feedback"><?= $dados['telefone_um_locatario_erro'] ?></div>
                            </div>
                            <div class="mb-3 col-sm-6">
                                <label for="txtTelefoneDoisLocatario" class="form-label">Telefone 2:</label>
                                <input type="text" placeholder="Somente números" class="form-control"
                                    name="txtTelefoneDoisLocatario" id="txtTelefoneDoisLocatario"
                                    value="<?= trim($dados['morador']->tel_dois_locatario) ?>" maxlength="11">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h3>Pets</h3>

                    <div class="row">
                        <div class="mb-3 col-sm-6">
                            <div class="form-check">
                                <?php
                                $checkedPet = '';
                                if (!empty($dados['chkPossuiPets'])) {
                                    if ($dados['chkPossuiPets'] == 'S') {
                                        $checkedPet = 'checked';
                                    }
                                }

                                if ($dados['morador']->flag_tem_pet == 'S') {
                                    $checkedPet = 'checked';
                                }
                                ?>
                                <input class="form-check-input" type="checkbox" value="S" id="chkPossuiPets"
                                    <?= $checkedPet ?> name="chkPossuiPets">
                                <label class="form-check-label" for="chkPossuiPets">
                                    Possui pet?
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="divQuantidadePets">
                        <div class="row">
                            <div class="mb-3 col-sm-4">
                                <label for="qtdPets" class="form-label">Quantidade de pets: </label>
                                <input type="text" class="form-control" name="qtdPets" id="qtdPets" maxlength="1"
                                    placeholder="Somente números"
                                    value="<?= trim(string: $dados['morador']->qtd_pets) ?>">
                                <div class="text-danger"><?= $dados['quantidade_pets_erro'] ?></div>
                            </div>
                        </div>
                    </div>


                    <hr>
                    <h3>Veículo(s) da residência</h3>

                    <div class="row">
                        <div class="mb-3 col-sm-6">
                            <div class="form-check">
                                <?php
                                $checkedAdesivo = '';

                                if (!empty($dados['chkRecebeuAdesivo'])) {
                                    if ($dados['chkRecebeuAdesivo'] == 'S') {
                                        $checkedAdesivo = 'checked';
                                    }
                                }

                                if ($dados['morador']->flag_adesivo == 'S') {
                                    $checkedAdesivo = 'checked';
                                }
                                ?>
                                <input class="form-check-input" type="checkbox" value="S" id="chkRecebeuAdesivo"
                                    <?= $checkedAdesivo ?> name="chkRecebeuAdesivo">
                                <label class="form-check-label" for="chkRecebeuAdesivo">
                                    Recebeu Adesivo?
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="divQuantidadeAdesivos">
                        <div class="row">
                            <div class="mb-3 col-sm-4">
                                <label for="qtdAdesivos" class="form-label">Quantidade de adesivos: </label>
                                <input type="text" class="form-control" name="qtdAdesivos" id="qtdAdesivos"
                                    maxlength="1" placeholder="Somente números"
                                    value="<?= trim($dados['morador']->qtd_adesivos) ?>">
                                <div class="text-danger"><?= $dados['quantidade_adesivos_erro'] ?></div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-secondary mb-3 mt-3" type="button" onclick="adicionarCampos()"><i
                            class="bi bi-plus-circle"></i> Adicionar
                        Veículo</button>


                    <div class="mb-3" id="veiculosContainer">
                        <!-- Os novos campos aparecerão aqui -->
                    </div>


                    <div class="d-flex">
                        <div class="p-2">
                            <input type="submit" value="Salvar" class="btn btn-primary">
                        </div>
                        <div class="p-2">
                            <a class="btn btn-secondary"
                                href="<?= URL . 'Moradores/visualizarMoradorPorIdUsuario/' . $dados['morador']->fk_usuario ?>"
                                role="button">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>