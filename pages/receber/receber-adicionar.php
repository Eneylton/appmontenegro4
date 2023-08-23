<?php

require __DIR__ . '../../../vendor/autoload.php';

use App\Entidy\Boleto;
use App\Entidy\Cliente;
use App\Entidy\Destinatario;
use App\Entidy\EntregadorDetalhe;
use App\Entidy\NotaFiscal;
use App\Entidy\Receber;
use App\Session\Login;

Login::requireLogin();

$usuariologado = Login::getUsuarioLogado();

$usuario = $usuariologado['id'];

date_default_timezone_set('America/Sao_Paulo');

$data = date('Y-m-d H:i:s');

$comparar = "COD_CONTRATO ";

$id_gaiola = 117;

$cont = 0;
$caracter = 0;
$cont2 = 0;
$contar = 0;
$arquivos = [];
$array    = [];

$soma = 0;
$qtd_entrega = 0;
$contador = 0;
$val = 1;
$disponivel = 0;
$cont_disponivel = 0;
$qtd_disp  = 0;
$reslt   = "";
$linha2 = "";
$valor2 = 0;
$codigo2 = 0;

if (isset($_POST['adicionar'])) {

    $boletoEdit = Boleto::getCodigo('*', 'boletos', "'%" . $_POST['codbarra2'] . "%'", null, null, null);

    $id_boleto =  $boletoEdit->id;

    $status = $boletoEdit->status;

    if ($status == 3) {

        $contarSequencia = Boleto::getContar('sequencia', 'boletos', 'receber_id= ' . $_POST['receber_id']);

        $sequencia = $contarSequencia->sequencia;

        $boletoEdit->status = 4;

        $conta = intval($sequencia + 1);

        $boletoEdit->entregadores_id = $_POST['entregador_id'];

        $boletoEdit->sequencia = $conta;
        $boletoEdit->coleta = $data;

        $boletoEdit->atualizar();

        $detalhe = new EntregadorDetalhe;
        $detalhe->data                = $data;
        $detalhe->status              = 2;
        $detalhe->obs                 = "Nenhuma ...";
        $detalhe->ocorrencias_id      = 18;
        $detalhe->entregadores_id     = $_POST['entregador_id'];
        $detalhe->boletos_id          = $id_boleto;
        $detalhe->usuarios_id         = $usuario;
        $detalhe->cadastar();


        header('location: ../receber/entregador-boleto.php?entregador_id=' . $_POST['entregador_id'] . '&receber_id=' . $_POST['receber_id'] . '&qtd=' . $_POST['qtd']);
        exit;
    }

    if ($boletoEdit != false) {

        $boletoEdit->entregadores_id = $_POST['entregador_id'];
        $boletoEdit->coleta = $data;
        $boletoEdit->atualizar();

        header('location: ../receber/entregador-boleto.php?entregador_id=' . $_POST['entregador_id'] . '&receber_id=' . $_POST['receber_id'] . '&qtd=' . $_POST['qtd']);
        exit;
    } else {

        $receber = Receber::getID('*', 'receber', $_POST['receber_id'], null, null, null);

        $cli_id = $receber->clientes_id;

        $cli = Cliente::getID('*', 'clientes', $cli_id, null, null, null);

        $serv_id = $cli->setores_id;

        if ($serv_id != 3) {

            $qtd = $receber->qtd;

            $remessa = $receber->numero;

            $receber_id = $receber->id;

            $data_importacao = $receber->data;

            $data_coleta = $receber->coleta;

            $inicio      = $receber->data_inicio;

            $fim         = $receber->data_fim;

            $cont_total = $qtd + 1;

            $receber->atualizar();

            $nota = NotaFiscal::getIDChave('*', 'notafiscal', "'%" . $_POST['codbarra2'] . "%'", null, null, null);

            $nota_id = $nota->id;
            $serie   = $nota->serie;
            $notafiscal  = $nota->notafiscal;
            $reslt   =  $notafiscal . ' - ' . $serie;
            $cliente = $nota->razaosocial;
            $nota->atualizar();

            $destinatario = Destinatario::getIDNota('*', 'destinatario',"'". $nota_id."'", null, null, null);

            $dest_id =            $destinatario->id;
            $dest_cpf =           $destinatario->cpf;
            $dest_nome =          $destinatario->nome;
            $dest_logradouro =    $destinatario->logradouro;
            $dest_numero =        $destinatario->numero;
            $dest_bairro =        $destinatario->bairro;
            $dest_municipio =     $destinatario->municipio;
            $dest_uf =            $destinatario->uf;
            $dest_cep =           $destinatario->cep;
            $dest_pais =          $destinatario->pais;
            $dest_telefone =      $destinatario->telefone;
            $dest_email =         $destinatario->email;
            $dest_notafiscal_id = $destinatario->notafiscal_id;

            $contarSequencia = Boleto::getQtd('COUNT(*) as qtd', 'boletos', 'receber_id= ' . $_POST['receber_id']);

            $conta = intval($contarSequencia + 1);

            $item2 = new Boleto;
            $item2->data            = $data_importacao;
            $item2->sequencia       = $conta;
            $item2->coleta          = $data_coleta;
            $item2->data_inicio     = $inicio;
            $item2->data_fim        = $fim;
            $item2->vencimento      = $fim;
            $item2->codigo          = $_POST['codbarra2'];
            $item2->cliente         = $cliente;
            $item2->nota            = $reslt;
            $item2->email           = $dest_email;
            $item2->telefone        = $dest_telefone;
            $item2->tipo            = "CAIXA";
            $item2->status          = 4;
            $item2->ocorrencias_id  = 18;
            $item2->entregadores_id = $_POST['entregador_id'];
            $item2->notafiscal_id   = $dest_notafiscal_id;
            $item2->destinatario_id = $dest_id;
            $item2->receber_id      = $_POST['receber_id'];
            $item2->remessa         = $remessa;
            $item2->cadastar();

            $id_boleto = $item2->id;

            $detalhe = new EntregadorDetalhe;
            $detalhe->data                = $data_importacao;
            $detalhe->status              = 2;
            $detalhe->obs                 = "Nenhuma ...";
            $detalhe->ocorrencias_id      = 18;
            $detalhe->entregadores_id     = $_POST['entregador_id'];
            $detalhe->boletos_id          = $id_boleto;
            $detalhe->usuarios_id         = $usuario;
            $detalhe->cadastar();

            header('location: ../receber/entregador-boleto.php?entregador_id=' . $_POST['entregador_id'] . '&receber_id=' . $_POST['receber_id'] . '&qtd=' . $_POST['qtd']);
            exit;
        } else {

            echo "aqui";
        }
    }
}
