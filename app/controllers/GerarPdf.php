<?php

require_once APP . '/libraries/fpdf.php';

class GerarPdf extends Controller
{
    private $modelMorador;
    private $usuarioModel;

    //Construtor do model do Usuário que fará o acesso ao banco
    public function __construct()
    {   
        $permissoes = [ADMINISTRADOR, SINDICO, MORADOR];
        $this->verificaSeEstaLogadoETemPermissao($permissoes);

        $this->modelMorador = $this->model("MoradorModel");
        $this->usuarioModel = $this->model("UsuarioModel");
    }

    public function gerarPdfMorador($id){

        if($this->podeGerarPdf($id)){
            $this->gerar($id);
        } else {
            Redirecionamento::redirecionar('Paginas/paginaErro');
        }

    }

    public function gerar($id)
    {
        date_default_timezone_set('America/Sao_Paulo');

        $pdf = new FPDF();
        $pdf->AddPage(); // Adiciona uma página
        $pdf->SetFont('Arial', 'B', 16); // Define a fonte

        // Título do documento
        $pdf->Cell(0, 10, 'Cadastro do Morador' , 0, 1, 'C');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 7, 'Gerado Em: ' . date('d/m/Y H:i:s') , 0, 1, 'C');
        $pdf->Cell(0, 7, 'Gerado Por: ' . $_SESSION['ds_nome_usuario'] , 0, 1, 'C');

        $morador = $this->modelMorador->retornarMoradorPorId($id);
        $moradorConvertido = $this->converterObjetoMorador($morador);

        $pdf->Ln();

        $pdf->SetFont('Arial', '', 10);
        foreach ($moradorConvertido as $campo => $valor) {

            if($campo === 'Detalhes'){
                $pdf->Cell(0, 7, utf8_decode($campo) . ':', 1);

                $pdf->Ln(); // Quebra de linha
                $pdf->MultiCell(0, 6, $valor, 1);
                // $pdf->Cell(0, 50, utf8_decode($valor), 1, 1); 
            } else {
                $pdf->Cell(50, 7, utf8_decode($campo) . ':', 1);
                $pdf->Cell(0, 7, utf8_decode($valor), 1, 1);
            }
            
        }

        // Nome do arquivo PDF
        $arquivo = 'ficha_morador_' . date('d/m/Y H:i:s') .'.pdf';

        // Enviando para o navegador
        $pdf->Output('I', $arquivo);

       
    }

    public function converterObjetoMorador($objeto){

        $novoObjeto = [

            'Numero Casa' => $objeto->ds_numero_casa,

            'Nome Morador' => ucfirst($objeto->nm_morador),
            'Documento Morador' => $objeto->documento_morador,
            'Data Nascimento Morador' => date('d/m/Y', strtotime($objeto->dt_nascimento_morador)),
            'Email Morador' => $objeto->email_morador,
            'Telefone 1' => $objeto->tel_um_morador,
            'Telefone 2' => $objeto->tel_dois_morador,
            'Telefone Emergência' => $objeto->tel_emergencia,

            'Tem Locatário' => $objeto->flag_locatario,

            'Nome Locatário' => ucfirst($objeto->nm_locatario),
            'Documento Locatário' => $objeto->documento_locatario,
            'Data Nascimento Locatário' => date('d/m/Y', strtotime($objeto->dt_nascimento_locatario)),
            'Email Locatário' => $objeto->email_locatario,
            'Telefone 1' => $objeto->tel_um_locatario,
            'Telefone 2' => $objeto->tel_dois_locatario,
            
            'Tem Pet' => $objeto->flag_tem_pet,
            'Quantidade Pets' => $objeto->qtd_pets,

            'Recebeu Adesivos Carro' => $objeto->flag_adesivo,
            'Quantidade Adesivos' => $objeto->qtd_adesivos,

            'Detalhes' => $objeto->ds_detalhes

        ];

        return $novoObjeto;

    }


    private function podeGerarPdf($id)
    {
        
        $morador = $this->modelMorador->retornarMoradorPorId($id);

        $usuario = $this->usuarioModel->lerUsuarioPorIdComCasas($_SESSION['id_usuario']);

        if(empty($usuario) || empty($morador) || $morador->fk_casa != $usuario->fk_casa){
           return false;
        } else {
            return true;
        }

    }

}