<script>
    let contador = 0; // Contador para rastrear o número de campos

    function adicionarCampos() {
        contador++;

        // Seleciona a div onde os novos campos serão adicionados
        const container = document.getElementById("veiculosContainer");

        // Cria o container dos novos campos
        const novoCampo = document.createElement("div");
        novoCampo.classList.add("campo-veiculo");
        novoCampo.setAttribute("id", `veiculo_${contador}`);

        novoCampo.innerHTML = `
            <div class="row">
                <div class="mb-3 col-sm-6">
                    <label>Tipo de Veículo:</label>
                    <select class="form-select" name="tipo_veiculo_${contador}">
                        <?php foreach ($dados['listaTiposVeiculos'] as $tipo): ?>
                                <option value="<?= $tipo->id_tipo_veiculo ?>"><?= $tipo->ds_tipo_veiculo ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3 col-sm-6">
                    <label class="form-label" >Placa do Veículo:</label>
                    <input class="form-control" type="text" name="placa_veiculo_${contador}" placeholder="Digite a placa do veículo">
                </div>
                <div class="mb-3 col-sm-6">
                    <label>Cor do Veículo:</label>
                    <select class="form-select" name="cor_veiculo_${contador}">
                        <?php foreach ($dados['listaCoresVeiculos'] as $cor): ?>
                                <option value="<?= $cor->id_cor_veiculo ?>"><?= $cor->ds_cor_veiculo ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mt-4 col-sm-6">
                    <button class="btn btn-danger" type="button" onclick="removerCampo(${contador})"><i class="bi bi-trash-fill"></i>  Remover Veículo</button>
                </div>
            </div>
            <hr>                
            `;

        // Adiciona o novo conjunto de campos ao container
        container.appendChild(novoCampo);
    }

    function removerCampo(id) {
        // Seleciona o campo a ser removido pelo ID e o remove do DOM
        const campo = document.getElementById(`veiculo_${id}`);
        if (campo) {
            campo.remove();
        }
    }
</script>
<div class="row">
    <div class="col-sm-10 mx-auto p-5">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URL . 'Visitantes/visualizarVisitantes' ?>">Visitantes</a></li>
                <li class="breadcrumb-item active" aria-current="page">Novo visitante</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <h2>Cadastro de Visitante</h2>
                <p class="mb-3 text-muted">Preencha o formulário abaixo para cadastrar um novo visitante</p class="mb-3 text-muted">

                <form name="cadastrar" method="POST" action="<?= URL . 'Visitantes/cadastrar' ?>">

                    <input type="hidden" name="acao" id="acao" value="salvar">

                    <div class="row">
                        <div class="mb-3 col-sm-6">
                            <label for="txtNome" class="form-label">Nome: *</label>
                            <input type="text" class="form-control <?= $dados['nome_erro'] ? 'is-invalid' : '' ?>"
                                name="txtNome" id="txtNome" value="<?= $dados['txtNome'] ?>" maxlength="255">
                            <div class="invalid-feedback"><?= $dados['nome_erro'] ?></div>
                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="txtDocumento" class="form-label">Documento: * <span style="color: gray;">(apenas
                                    números)</span></label>
                            <input type="text" class="form-control <?= $dados['documento_erro'] ? 'is-invalid' : '' ?>"
                                name="txtDocumento" id="txtDocumento" value="<?= $dados['txtDocumento'] ?>" maxlength="11">
                            <div class="invalid-feedback"><?= $dados['documento_erro'] ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-sm-6">
                            <label for="txtTelefoneUm" class="form-label">Telefone 1: <span style="color: gray;">(apenas
                                    números)</span></label>
                            <input type="text" class="form-control" name="txtTelefoneUm" id="txtTelefoneUm"
                                value="<?= $dados['txtTelefoneUm'] ?>" maxlength="11">

                        </div>
                        <div class="mb-3 col-sm-6">
                            <label for="txtTelefoneDois" class="form-label">Telefone 2: <span style="color: gray;">(apenas
                                    números)</span></label>
                            <input type="text" class="form-control" name="txtTelefoneDois" id="txtTelefoneDois"
                                value="<?= $dados['txtTelefoneDois'] ?>" maxlength="11">

                        </div>
                    </div>

                    <button class="btn btn-secondary mb-3" type="button" onclick="adicionarCampos()"><i
                            class="bi bi-plus-circle"></i> Adicionar
                        Veículo</button>


                    <div class="mb-3 " id="veiculosContainer">
                        <!-- Os novos campos aparecerão aqui -->
                    </div>


                    <div class="d-flex">
                        <div class="p-2">
                            <button type="submit" onclick="document.getElementById('acao').value = 'OPERACAO_SALVAR'"
                                class="btn btn-primary">Cadastrar
                            </button>
                        </div>
                        <div class="p-2">
                            <button type="submit" class="btn btn-success" name="btnEntradaVisita" id="btnEntradaVisita"
                                onclick="document.getElementById('acao').value = 'OPERACAO_SALVAR_E_ENTRAR'">
                                <i class="bi bi-arrow-up-circle-fill"></i> Salvar e Entrada Visita
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