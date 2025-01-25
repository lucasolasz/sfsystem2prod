<?php

class Controller
{
    public function model($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model;
    }

    public function view($view, $dados = [])
    {
        $arquivo = ('../app/views/' . $view . '.php');
        include APP . '/views/topo.php';

        if (file_exists($arquivo)) {
            require_once $arquivo;
        } else {
            die('O arquivo de view não existe!');
        }
        include APP . '/views/rodape.php';
    }

    public function verificaSeEstaLogado()
    {
        if (!IsLoged::estaLogado()) {
            Redirecionamento::redirecionar('Paginas/paginaErro');
        }
    }

    public function temPermissao($permissoes)
    {
        foreach ($permissoes as $permissao) {
            foreach ($_SESSION['permissoes'] as $obj) {
                if ($obj->cod_cargo === $permissao) {
                    return;
                }
            }
        }

        Redirecionamento::redirecionar('Paginas/paginaErro');
    }

    public function verificaSeEstaLogadoETemPermissao($permissoes)
    {
        $this->verificaSeEstaLogado();
        $this->temPermissao($permissoes);
    }
}
