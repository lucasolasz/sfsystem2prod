<div class="col-xl-4 col-md-6 mx-auto p-5">
    <div class="card">
        <div class="card-body">
            <h2>Cadastro de Usuário</h2>
            <p class="mb-3 text-muted">Preencha o formulário abaixo para cadastrar um novo usuário</p>

            <form name="cadastrar" method="POST" action="<?= URL . 'Usuarios/cadastrar' ?>">
                <div class="mb-3">
                    <label for="txtNome" class="form-label">Nome: *</label>
                    <input type="text" class="form-control <?= $dados['nome_erro'] ? 'is-invalid' : '' ?>"
                        name="txtNome" id="txtNome" value="<?= $dados['txtNome'] ?>">
                    <!-- Div para exibir o erro abaixo do campo -->
                    <div class="invalid-feedback"><?= $dados['nome_erro'] ?></div>
                </div>
                <div class="mb-3">
                    <label for="txtSobreNome" class="form-label">Sobrenome: *</label>
                    <input type="text" class="form-control <?= $dados['sobrenome_erro'] ? 'is-invalid' : '' ?>"
                        name="txtSobreNome" id="txtSobreNome" value="<?= $dados['txtSobreNome'] ?>">
                    <!-- Div para exibir o erro abaixo do campo -->
                    <div class="invalid-feedback"><?= $dados['sobrenome_erro'] ?></div>
                </div>
                <div class="mb-3">
                    <label for="txtEmail" class="form-label">E-mail: *</label>
                    <input type="text" class="form-control <?= $dados['email_erro'] ? 'is-invalid' : '' ?>"
                        name="txtEmail" id="txtEmail" value="<?= $dados['txtEmail'] ?>">
                    <div class="invalid-feedback"><?= $dados['email_erro'] ?></div>
                </div>

                <div class="mb-3">
                    <label for="txtEmail" class="form-label">Cargo usuário: *</label>
                    <select class="form-select <?= $dados['tipoCargo_erro'] ? 'is-invalid' : '' ?>"
                        name="cboCargoUsuario" id="cboCargoUsuario">
                        <option value="NULL"></option>
                        <?php foreach ($dados['cargoUsuario'] as $cargoUsuario) {
                            //Resgata valor do select 
                            $cargoSelected = '';
                            if ($cargoUsuario->id_cargo == $dados['cboCargoUsuario']) {
                                $cargoSelected = 'selected';
                            }
                            ?>
                            <option <?= $cargoSelected ?> value="<?= $cargoUsuario->id_cargo ?>">
                                <?= $cargoUsuario->ds_cargo ?>
                            </option>
                            <?php
                        } ?>
                    </select>
                    <div class="invalid-feedback"><?= $dados['tipoCargo_erro'] ?></div>
                </div>
                <div class="mb-3">
                    <label for="txtSenha" class="form-label">Senha: *</label>
                    <input type="password" class="form-control <?= $dados['senha_erro'] ? 'is-invalid' : '' ?>"
                        name="txtSenha" id="txtSenha" value="<?= $dados['txtSenha'] ?>">
                    <div class="invalid-feedback"><?= $dados['senha_erro'] ?></div>
                </div>
                <div class="mb-3">
                    <label for="txtConfirmaSenha" class="form-label">Confirmar Senha: *</label>
                    <input type="password" class="form-control <?= $dados['confirma_senha_erro'] ? 'is-invalid' : '' ?>"
                        name="txtConfirmaSenha" id="txtConfirmaSenha">
                    <div class="invalid-feedback"><?= $dados['confirma_senha_erro'] ?></div>
                </div>
                <div class="d-flex">
                    <div class="p-2">
                        <input type="submit" value="Cadastrar" class="btn btn-primary">
                    </div>
                    <div class="p-2">
                        <a class="btn btn-secondary" href="<?= URL . 'Usuarios/visualizarUsuarios' ?>"
                            role="button">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>