<?php

class Paginas extends Controller
{
    public function index()
    {

        //Parâmetros enviados para o método do controller VIEW
        $dados = [
            'tituloPagina' => 'Página Inicial',
        ];

        //Chamada do novo objeto PAGINAS 
        $this->view('paginas/home', $dados);
    }

    public function paginaErro()
    {

        //Parâmetros enviados para o método do controller VIEW
        $dados = [
            'mensagem' => 'Você não tem permissão de acesso a esta página',
            'paragrafo' => 'Realize o login e tente novamente'
        ];

        //Chamada do novo objeto PAGINAS 
        $this->view('paginas/paginaErro', $dados);
    }
}