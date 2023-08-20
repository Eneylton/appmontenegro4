<?php

require __DIR__ . '../../../vendor/autoload.php';

use App\Entidy\Boleto;
use App\Entidy\BoletoProducao;
use App\Entidy\Devolucao;
use App\Entidy\Entrega;
use App\Entidy\EntregaDevolucao;
use App\Entidy\EntregadorDetalhe;
use App\Entidy\Producao;
use App\Funcao\CalcularQtd;
use App\Session\Login;

Login::requireLogin();

$usuariologado = Login::getUsuarioLogado();

$usuario = $usuariologado['id'];

$flag = "";

date_default_timezone_set('America/Sao_Paulo');
$data_cadastra = date('Y-m-d H:m:s');


if (!isset($_GET['id']) or !is_numeric($_GET['id'])) {

    header('location: index.php?status=error');

    exit;
} else {


    $resultado = CalcularQtd::calcularQtd($_GET['entregador_id'], $_GET['receber_id'], $_GET['status']);


    switch ($_GET['status']) {
        case '3':

            $value = Boleto::getID('*', 'boletos', $_GET['id'], null, null, null);
            $value->data             = $data_cadastra;
            $value->status           = 3;
            $value->obs              = $_GET['obs'];
            $value->atualizar();

            $detalhe = new EntregadorDetalhe;
            $detalhe->data             = $data_cadastra;
            $detalhe->status           = 5;
            $detalhe->ocorrencias_id   = 29;
            $detalhe->obs              = $_GET['obs'];
            $detalhe->entregadores_id  = $_GET['entregador_id'];
            $detalhe->boletos_id       = $_GET['id'];
            $detalhe->usuarios_id      = $usuario;
            $detalhe->cadastar();

            header('location: boleto-list.php?id_item=' . $_GET['receber_id']);
            exit;


        case '4':

            $detalhe = new EntregadorDetalhe;
            $detalhe->data                = $data_cadastra;
            $detalhe->status              = 2;
            $detalhe->ocorrencias_id      = 29;
            $detalhe->obs                 = $_GET['obs'];
            $detalhe->entregadores_id     = $_GET['entregador_id'];
            $detalhe->boletos_id          = $_GET['id'];
            $detalhe->usuarios_id         = $usuario;
            $detalhe->cadastar();

            $value = Boleto::getID('*', 'boletos', $_GET['id'], null, null, null);
            $value->data             = $data_cadastra;
            $value->obs              = $_GET['obs'];
            $value->status           = 4;
            $value->ocorrencias_id   = 29;
            $value->atualizar();

            header('location: boleto-list.php?id_item=' . $_GET['receber_id']);
            exit;

        case '1':

            $detalhe = new EntregadorDetalhe;
            $detalhe->data             = $data_cadastra;
            $detalhe->status           = 3;
            $detalhe->ocorrencias_id   = 29;
            $detalhe->obs              = $_GET['obs'];
            $detalhe->entregadores_id  = $_GET['entregador_id'];
            $detalhe->boletos_id       = $_GET['id'];
            $detalhe->usuarios_id      = $usuario;
            $detalhe->cadastar();

            $producao = Producao::getReceberID('*', 'producao', $_GET['receber_id'] . ' AND entregadores_id = ' . $_GET['entregador_id'], null, null, null);
            $id_prod =  $producao->id;

            $verificaboleto = Entrega::getEntregaBoletoID('*', 'entrega', $_GET['id'], null, null, null);
            $boleto_id = $verificaboleto->id;

            if ($verificaboleto == false) {


                $entrega = new Entrega;
                $entrega->boletos_id             = $_GET['id'];
                $entrega->data                   = $_GET['data'];
                $entrega->producao_id            = $id_prod;
                $entrega->entregadores_id        = $_GET['entregador_id'];
                $entrega->qtd                    = 1;
                $entrega->cadastar();

                $estatistica = new EntregaDevolucao;
                $estatistica->boletos_id         = $_GET['id'];
                $estatistica->receber_id         = $_GET['receber_id'];
                $estatistica->data               = $_GET['data'];
                $estatistica->entrega            = 1;
                $estatistica->devolucao          = 0;
                $estatistica->entregadores_id    = $_GET['entregador_id'];
                $estatistica->producao_id        = $id_prod;
                $estatistica->cadastar();

                $boleto = new BoletoProducao;
                $boleto->boletos_id              = $_GET['id'];
                $boleto->data                    = $_GET['data'];
                $boleto->codigo                  = $_GET['codigo'];
                $boleto->destinatario            = $_GET['destinatario'];
                $boleto->status                  = $_GET['status'];
                $boleto->ocorrencias_id          = 29;
                $boleto->entregadores_id         = $_GET['entregador_id'];
                $boleto->receber_id              = $_GET['receber_id'];
                $boleto->cadastar();

                $producao_qtd                    = $producao->qtd - 1;
                $id_producao                     = $producao->id;
                $producao->qtd                   = $producao_qtd;
                $producao->atualizar();

                $value = Boleto::getID('*', 'boletos', $_GET['id'], null, null, null);
                $quantidade = Boleto::getQtdSequencia('count(b.sequencia) as total', 'boletos AS b', null, null, null);

                $qtd = $quantidade->total + 1;

                $value->data             = $data_cadastra;
                $value->status           = $_GET['status'];
                $value->sequencia        = $qtd;
                $value->ocorrencias_id   = 29;
                $value->obs              = $_GET['obs'];
                $value->atualizar();

                header('location: boleto-list.php?id_item=' . $_GET['receber_id']);
                exit;
            } else {

                $devolvido = Devolucao::getIDProducao('*', 'devolucao', $id_prod . null, null, null);
                $qtdDevolvidaid =  $devolvido->id;
                $excluirEntrega = Devolucao::getID('*', 'devolucao', $qtdDevolvidaid, null, null, null);
                $excluirEntrega->excluir();
            }

        default:

            $detalhe = new EntregadorDetalhe;
            $detalhe->data                  = $data_cadastra;
            $detalhe->status                = 4;
            $detalhe->ocorrencias_id        = $_GET['ocorrencias_id'];
            $detalhe->obs                   = $_GET['obs'];
            $detalhe->entregadores_id       = $_GET['entregador_id'];
            $detalhe->boletos_id            = $_GET['id'];
            $detalhe->usuarios_id           = $usuario;
            $detalhe->cadastar();

            $producao = Producao::getReceberID('*', 'producao', $_GET['receber_id'] . ' AND entregadores_id = ' . $_GET['entregador_id'], null, null, null);
            $id_prod =  $producao->id;

            $devolucao = new Devolucao;
            $devolucao->boletos_id          = $_GET['id'];
            $devolucao->data                = $_GET['data'];
            $devolucao->producao_id         = $id_prod;
            $devolucao->ocorrencias_id      = $_GET['ocorrencias_id'];
            $devolucao->entregadores_id     = $_GET['entregador_id'];
            $devolucao->qtd                 = 1;
            $devolucao->cadastar();

            $estatistica = new EntregaDevolucao;
            $estatistica->boletos_id         = $_GET['id'];
            $estatistica->receber_id         = $_GET['receber_id'];
            $estatistica->data               = $_GET['data'];
            $estatistica->entrega            = 0;
            $estatistica->devolucao          = 1;
            $estatistica->entregadores_id    = $_GET['entregador_id'];
            $estatistica->producao_id        = $id_prod;
            $estatistica->cadastar();

            $boleto = new BoletoProducao;
            $boleto->boletos_id         = $_GET['id'];;
            $boleto->data               = $_GET['data'];
            $boleto->codigo             = $_GET['codigo'];
            $boleto->destinatario       = $_GET['destinatario'];
            $boleto->status             = $_GET['status'];
            $boleto->ocorrencias_id     = 29;
            $boleto->entregadores_id    = $_GET['entregador_id'];
            $boleto->receber_id         = $_GET['receber_id'];
            $boleto->cadastar();

            $id_producao     = $producao->id;
            $producao->qtd   = $producao_qtd;
            $producao->atualizar();

            $value = Boleto::getID('*', 'boletos', $_GET['id'], null, null, null);

            $quantidade = Boleto::getQtdSequencia('count(b.sequencia) as total', 'boletos AS b', null, null, null, null);

            $qtd = $quantidade->total + 1;

            $value->data             = $_GET['data'];
            $value->status           = $_GET['status'];
            $value->ocorrencias_id   = $_GET['ocorrencias_id'];
            $value->obs              = $_GET['obs'];
            $value->atualizar();

            header('location: boleto-list.php?id_item=' . $_GET['receber_id']);
            exit;
    }
}
