<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Oswald:400,300,700" rel="stylesheet" type="text/css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.css" />

    <!-- Custom Styles -->
    <link href="<?php echo URL . 'public/css/estilos.css' ?>" rel="stylesheet">

    <title><?php echo APP_NOME; ?></title>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.js"></script>

    <!-- Custom Scripts -->
    <script src="<?php echo URL . 'public/js/jquery.funcoes.js' ?>"></script>
</head>

<body>

    <?php function temPermissao($listaPermissao)
    {
        foreach ($listaPermissao as $codPermissao) {

            foreach ($_SESSION['permissoes'] as $obj) {

                if ($obj->cod_cargo === $codPermissao) {
                    return true;
                }
            }
        }
    }

    ?>
    <header class="bg-light">

        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="<?= URL ?>"><?= APP_NOME ?></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <!-- <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="<?= URL ?>">Home</a>
                            </li> -->
                            <?php if (isset($_SESSION['id_usuario'])) { ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                                        aria-expanded="false">Cadastro</a>
                                    <ul class="dropdown-menu">
                                        <?php if (temPermissao([ADMINISTRADOR, SINDICO])) { ?>
                                            <li>
                                                <a class="dropdown-item" aria-current="page"
                                                    href="<?= URL . 'Usuarios/visualizarUsuarios' ?>">Usuário</a>
                                            </li>
                                        <?php } ?>
                                        <?php if (temPermissao([PORTEIRO, SINDICO])) { ?>
                                            <li class="nav-item">
                                                <a class="dropdown-item"
                                                    href="<?= URL . 'Visitantes/visualizarVisitantes' ?>">Visitante</a>
                                            </li>
                                        <?php } ?>
                                        <?php if (temPermissao([ADMINISTRADOR, SINDICO])) { ?>
                                            <li class="nav-item">
                                                <a class="dropdown-item"
                                                    href="<?= URL . 'Moradores/visualizarMoradores' ?>">Moradores</a>
                                            </li>
                                        <?php } ?>
                                        <?php if (temPermissao([MORADOR])) { ?>
                                            <li class="nav-item">
                                                <a class="dropdown-item"
                                                    href="<?= URL . 'Moradores/visualizarMoradorPorIdUsuario/' . $_SESSION['id_usuario'] ?>">Morador</a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php if (temPermissao([PORTEIRO, SINDICO])) { ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?= URL . 'Visitas/visualizarVisitasEmAndamento' ?>"><i
                                                class="bi bi-layout-text-sidebar"></i> Visitas em Andamento</a>
                                    </li>
                                <?php } ?>
                            <?php } ?>

                        </ul>
                        <div class="d-flex align-items-center">
                            <?php if (isset($_SESSION['id_usuario'])) { ?>
                                <div class="me-2" style="width: 300px;">
                                    <p class="mb-0">Olá, <?= ucfirst($_SESSION['ds_nome_usuario']); ?>, Seja bem vindo(a)
                                    </p>
                                </div>
                                <div>
                                    <a class="btn btn-sm btn-danger" href="<?= URL . 'Login/sair' ?>">Sair</a>
                                </div>
                            <?php } else { ?>
                                <div style="width: 30px">
                                    <a class="btn btn-primary" href="<?= URL . 'Login/login' ?>">Entrar</a>
                                </div>
                            <?php } ?>
                        </div>

                    </div>
                </div>
            </nav>
        </div>

    </header>