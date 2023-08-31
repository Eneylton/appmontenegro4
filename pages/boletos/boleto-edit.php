<?php

require __DIR__ . '../../../vendor/autoload.php';

use App\Entidy\Boleto;
use App\Entidy\BoletoProducao;
use App\Entidy\Devolucao;
use App\Entidy\Entrega;
use App\Entidy\EntregaDevolucao;
use App\Entidy\EntregadorDetalhe;
use App\Entidy\EntregadorQtd;
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

            $entregNovo = CalcularQtd::getMudarEntregador($_GET['receber_id'],$_GET['entregador_id']);

            if($entregNovo == true){

                $qtdEntrega = EntregadorQtd ::getRecebID('*','entregador_qtd',$_GET['rcb_qtd'].
                ' AND entregadores_id='.$_GET['ent_qtd'],null,null.null);

                $entid = $qtdEntrega->id;
                $dataInicio = $qtdEntrega->data_ini;
                $vencimento = $qtdEntrega->vencimento;
                $entregadorId = $qtdEntrega->entregadores_id;
                $entregadorqtd = $qtdEntrega->qtd;
                $qtdEntrega->qtd = $entregadorqtd - 1;
                $qtdEntrega->atualizar();

                $qtdEntregatual = EntregadorQtd ::getRecebID('*','entregador_qtd',$_GET['receber_id'].
                ' AND entregadores_id='.$_GET['entregador_id'],null,null.null);

                if($qtdEntregatual != false){
                    $entid = $qtdEntregatual->id;
                    $dataInicio = $qtdEntregatual->data_ini;
                    $vencimento = $qtdEntregatual->vencimento;
                    $entregadorId = $qtdEntregatual->entregadores_id;
                    $entreqtd = $qtdEntregatual->qtd;
                    $qtdEntregatual->qtd = intval($entreqtd) + 1;
                    $qtdEntrega->atualizar();
                }else{
                    $entregador = new EntregadorQtd;
                    $entregador->data_ini            = $dataInicio;
                    $entregador->vencimento          = $vencimento;
                    $entregador->qtd                 = 1;
                    $entregador->entregadores_id     = $_GET['entregador_id'];
                    $entregador->receber_id          = $_GET['receber_id'];
                    $entregador->cadastar();
                }

               
                $boletos = Boleto::getBoletosID('*','boletos',$_GET['rcb_qtd'].' AND entregadores_id='.$_GET['ent_qtd'], null,null,null);
                $boletos->entregadores_id  = $_GET['entregador_id'];
                $boletos->status           = $_GET['status'];
                if($_GET['ocorrencias_id'] != 18){

                    $boletos->ocorrencias_id   = $_GET['ocorrencias_id'];
                }else{
                    $boletos->ocorrencias_id   = 18;
                }
                $boletos->obs              = $_GET['obs'];
                $boletos->atualizar(); 

                $detalhe = new EntregadorDetalhe;
                $detalhe->data             = $data_cadastra;
                $detalhe->status           = $_GET['status'];
                $detalhe->ocorrencias_id   = 29;
                $detalhe->obs              = $_GET['obs'];
                $detalhe->entregadores_id  = $_GET['entregador_id'];
                $detalhe->boletos_id       = $_GET['id'];
                $detalhe->usuarios_id      = $usuario;
                $detalhe->cadastar();

                header('location: boleto-list.php?id_item=' . $_GET['receber_id']);
                exit;


            }else{
                
                $qtdEntrega = EntregadorQtd ::getRecebID('*','entregador_qtd',$_GET['rcb_qtd'].
                ' AND entregadores_id='.$_GET['ent_qtd'],null,null.null);

                $entid = $qtdEntrega->id;
                $dataInicio = $qtdEntrega->data_ini;
                $vencimento = $qtdEntrega->vencimento;
                $entregadorId = $qtdEntrega->entregadores_id;
                $entregadorqtd = $qtdEntrega->qtd;
                $qtdEntrega->qtd = $entregadorqtd - 1;
                $qtdEntrega->atualizar();

                $qtdEntregatual = EntregadorQtd ::getRecebID('*','entregador_qtd',$_GET['receber_id'].
                ' AND entregadores_id='.$_GET['entregador_id'],null,null.null);

                $qtd = $qtdEntregatual->qtd;
                $qtdEntregatual->qtd = intval($qtd) + 1;
                $qtdEntregatual->atualizar();

                $boletos = Boleto::getBoletosID('*','boletos',$_GET['rcb_qtd'].' AND entregadores_id='.$_GET['ent_qtd'], null,null,null);
                $boletos->entregadores_id  = $_GET['entregador_id'];
                $boletos->status           = $_GET['status'];
                if($_GET['ocorrencias_id'] != 18){

                    $boletos->ocorrencias_id   = $_GET['ocorrencias_id'];

                }else{
                    
                    $boletos->ocorrencias_id   = 18;
                }
                $boletos->obs              = $_GET['obs'];
                $boletos->atualizar(); 

                $detalhe = new EntregadorDetalhe;
                $detalhe->data             = $data_cadastra;
                $detalhe->status           = $_GET['status'];
                if($_GET['ocorrencias_id'] != 18){
                    
                    $detalhe->ocorrencias_id   = $_GET['ocorrencias_id'];
                }else{

                    $detalhe->ocorrencias_id   = 18;
                }
                $detalhe->obs              = $_GET['obs'];
                $detalhe->entregadores_id  = $_GET['entregador_id'];
                $detalhe->boletos_id       = $_GET['id'];
                $detalhe->usuarios_id      = $usuario;
                $detalhe->cadastar();


            header('location: boleto-list.php?id_item=' . $_GET['receber_id']);
            exit;
            }


        case '4':

            $entregNovo = CalcularQtd::getMudarEntregador($_GET['receber_id'],$_GET['entregador_id']);

            if($entregNovo == true){

                $qtdEntrega = EntregadorQtd ::getRecebID('*','entregador_qtd',$_GET['rcb_qtd'].
                ' AND entregadores_id='.$_GET['ent_qtd'],null,null.null);

                $entid = $qtdEntrega->id;
                $dataInicio = $qtdEntrega->data_ini;
                $vencimento = $qtdEntrega->vencimento;
                $entregadorId = $qtdEntrega->entregadores_id;
                $entregadorqtd = $qtdEntrega->qtd;
                $qtdEntrega->qtd = $entregadorqtd - 1;
                $qtdEntrega->atualizar();

                $qtdEntregatual = EntregadorQtd ::getRecebID('*','entregador_qtd',$_GET['receber_id'].
                ' AND entregadores_id='.$_GET['entregador_id'],null,null.null);

                if($qtdEntregatual != false){
                    $entid = $qtdEntregatual->id;
                    $dataInicio = $qtdEntregatual->data_ini;
                    $vencimento = $qtdEntregatual->vencimento;
                    $entregadorId = $qtdEntregatual->entregadores_id;
                    $entreqtd = $qtdEntregatual->qtd;
                    $qtdEntregatual->qtd = intval($entreqtd) + 1;
                    $qtdEntrega->atualizar();
                }else{
                    $entregador = new EntregadorQtd;
                    $entregador->data_ini            = $dataInicio;
                    $entregador->vencimento          = $vencimento;
                    $entregador->qtd                 = 1;
                    $entregador->entregadores_id     = $_GET['entregador_id'];
                    $entregador->receber_id          = $_GET['receber_id'];
                    $entregador->cadastar();
                }

               
                $boletos = Boleto::getBoletosID('*','boletos',$_GET['rcb_qtd'].' AND entregadores_id='.$_GET['ent_qtd'], null,null,null);
                $boletos->entregadores_id  = $_GET['entregador_id'];
                $boletos->status           = $_GET['status'];
                if($_GET['ocorrencias_id'] != 18){

                    $boletos->ocorrencias_id   = $_GET['ocorrencias_id'];
                }else{
                    $boletos->ocorrencias_id   = 18;
                }
                $boletos->obs              = $_GET['obs'];
                $boletos->atualizar(); 

                $detalhe = new EntregadorDetalhe;
                $detalhe->data             = $data_cadastra;
                $detalhe->status           = $_GET['status'];
                $detalhe->ocorrencias_id   = 29;
                $detalhe->obs              = $_GET['obs'];
                $detalhe->entregadores_id  = $_GET['entregador_id'];
                $detalhe->boletos_id       = $_GET['id'];
                $detalhe->usuarios_id      = $usuario;
                $detalhe->cadastar();

                header('location: boleto-list.php?id_item=' . $_GET['receber_id']);
                exit;


            }else{
                
                $qtdEntrega = EntregadorQtd ::getRecebID('*','entregador_qtd',$_GET['rcb_qtd'].
                ' AND entregadores_id='.$_GET['ent_qtd'],null,null.null);

                $entid = $qtdEntrega->id;
                $dataInicio = $qtdEntrega->data_ini;
                $vencimento = $qtdEntrega->vencimento;
                $entregadorId = $qtdEntrega->entregadores_id;
                $entregadorqtd = $qtdEntrega->qtd;
                $qtdEntrega->qtd = $entregadorqtd - 1;
                $qtdEntrega->atualizar();

                $qtdEntregatual = EntregadorQtd ::getRecebID('*','entregador_qtd',$_GET['receber_id'].
                ' AND entregadores_id='.$_GET['entregador_id'],null,null.null);

                $qtd = $qtdEntregatual->qtd;
                $qtdEntregatual->qtd = intval($qtd) + 1;
                $qtdEntregatual->atualizar();

                $boletos = Boleto::getBoletosID('*','boletos',$_GET['rcb_qtd'].' AND entregadores_id='.$_GET['ent_qtd'], null,null,null);
                $boletos->entregadores_id  = $_GET['entregador_id'];
                $boletos->status           = $_GET['status'];
                if($_GET['ocorrencias_id'] != 18){

                    $boletos->ocorrencias_id   = $_GET['ocorrencias_id'];

                }else{

                    $boletos->ocorrencias_id   = 18;
                }
                $boletos->obs              = $_GET['obs'];
                $boletos->atualizar(); 

                $detalhe = new EntregadorDetalhe;
                $detalhe->data             = $data_cadastra;
                $detalhe->status           = $_GET['status'];
                if($_GET['ocorrencias_id'] != 18){
                    
                    $detalhe->ocorrencias_id   = $_GET['ocorrencias_id'];
                }else{

                    $detalhe->ocorrencias_id   = 18;
                }
                $detalhe->obs              = $_GET['obs'];
                $detalhe->entregadores_id  = $_GET['entregador_id'];
                $detalhe->boletos_id       = $_GET['id'];
                $detalhe->usuarios_id      = $usuario;
                $detalhe->cadastar();


            header('location: boleto-list.php?id_item=' . $_GET['receber_id']);
            exit;

            }
           
        case '1':

            $detalhe = new EntregadorDetalhe;
            $detalhe->data             = $data_cadastra;
            $detalhe->status           = $_GET['status'];
            if($_GET['ocorrencias_id'] != 18){
                    
                $detalhe->ocorrencias_id   = $_GET['ocorrencias_id'];
            }else{

                $detalhe->ocorrencias_id   = 18;
            }
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
