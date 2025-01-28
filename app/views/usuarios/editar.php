<div class="col-xl-4 col-md-6 mx-auto p-5">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= URL . 'Usuarios/visualizarUsuarios' ?>">Usuários</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= ucfirst($dados['usuario']->ds_nome_usuario) ?>
            </li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-body">
            <h2>Editar Usuário</h2>
            <small>Preencha o formulário abaixo para editar o usuário</small>

            <form name="editar" method="POST"
                action="<?= URL . 'Usuarios/editarUsuario/' . $dados['usuario']->id_usuario ?>">
                <div class="mb-3 mt-4">
                    <label for="txtNome" class="form-label">Nome: *</label>
                    <input type="text" class="form-control <?= $dados['nome_erro'] ? 'is-invalid' : '' ?>"
                        name="txtNome" id="txtNome" value="<?= $dados['usuario']->ds_nome_usuario ?>">
                    <!-- Div para exibir o erro abaixo do campo -->
                    <div class="invalid-feedback"><?= $dados['nome_erro'] ?></div>
                </div>
                <div class="mb-3 mt-4">
                    <label for="txtSobreNome" class="form-label">Sobrenome: *</label>
                    <input type="text" class="form-control <?= $dados['sobrenome_erro'] ? 'is-invalid' : '' ?>"
                        name="txtSobreNome" id="txtSobreNome" value="<?= $dados['usuario']->ds_sobrenome_usuario ?>">
                    <!-- Div para exibir o erro abaixo do campo -->
                    <div class="invalid-feedback"><?= $dados['sobrenome_erro'] ?></div>
                </div>
                <div class="mb-3">
                    <label for="txtEmail" class="form-label">E-mail: *</label>
                    <input type="text" class="form-control <?= $dados['email_erro'] ? 'is-invalid' : '' ?>"
                        name="txtEmail" id="txtEmail" value="<?= $dados['usuario']->ds_email_usuario ?>">
                    <div class="invalid-feedback"><?= $dados['email_erro'] ?></div>
                </div>

                <div class="mb-3">
                    <label for="cboCargoUsuario" class="form-label">Cargo usuário: *</label>
                    <select class="form-select <?= $dados['tipoCargo_erro'] ? 'is-invalid' : '' ?>"
                        name="cboCargoUsuario" id="cboCargoUsuario">
                        <option value="NULL"></option>

                        <?php 
                        
                        if(isset($dados['cboCargoUsuario'])){

                            foreach ($dados['cargoUsuario'] as $cargoUsuario) {
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
                            } 

                        } else {
                            foreach ($dados['cargoUsuario'] as $cargoUsuario) {
                                //Resgata valor do select 
                                $cargoSelected = '';
                                if ($cargoUsuario->id_cargo == $dados['usuario']->fk_cargo) {
                                    $cargoSelected = 'selected';
                                }
                                ?>
                                <option <?= $cargoSelected ?> value="<?= $cargoUsuario->id_cargo ?>">
                                    <?= $cargoUsuario->ds_cargo ?>
                                </option>
                                <?php
                            } 
                        }
                        ?>
                        
                        
                    </select>
                    <div class="invalid-feedback"><?= $dados['tipoCargo_erro'] ?></div>
                </div>

                <div class="mb-3 mt-4">
                    <label for="txtCasaCadastrada" class="form-label">Casa Cadastrada: </label>
                    <input type="text" class="form-control <?= $dados['sobrenome_erro'] ? 'is-invalid' : '' ?>"
                            id="txtCasaCadastrada" value="<?= $dados['usuario']->ds_numero_casa ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="cboCasa" class="form-label">Alterar N° Casa: *</label>
                    <select class="form-select <?= $dados['cboCasa_erro'] ? 'is-invalid' : '' ?>" name="cboCasa" id="cboCasa">
                        <option label="Selecione uma casa"></option>
                        <?php foreach ($dados['casas'] as $casa) {
                            //Resgata valor do select 
                            $casaSelected = '';
                            if ($casa->id_casa == $dados['usuario']->fk_casa) {
                                $casaSelected = 'selected';
                            }
                        ?>
                            <option <?= $casaSelected ?> value="<?= $casa->id_casa ?>"><?= $casa->ds_numero_casa ?></option>
                        <?php } ?>
                    </select>
                    <div class="invalid-feedback"><?= $dados['cboCasa_erro'] ?></div>
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
                        <input type="submit" value="Salvar" class="btn btn-primary">
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

<script>
    $(document).ready(function() {
        // Função para mostrar ou esconder o select de casas
        function toggleCasaSelect() {
            if ($('#cboCargoUsuario').val() == '3') {
                $('#cboCasa').parent().show();  // Mostra o campo de casa
            } else {
                $('#cboCasa').parent().hide();  // Esconde o campo de casa
            }
        }

        // Executa a verificação ao carregar a página
        toggleCasaSelect();

        // Adiciona o evento change no select "cboCargoUsuario"
        $('#cboCargoUsuario').change(function() {
            toggleCasaSelect();
        });
    });
</script>