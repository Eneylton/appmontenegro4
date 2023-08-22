<?php

require __DIR__ . '../../../vendor/autoload.php';

use App\Entidy\Boleto;
use App\Entidy\NotaFiscal;
use App\Entidy\Produto;
use App\Entidy\Receber;
use App\Session\Login;

Login::requireLogin();

$id_gaiola = 0;
$qtd = 0;
$qtd_baia = 0;
$total_baia = 0;
$listBoletos = false;

if (!isset($_GET['id']) or !is_numeric($_GET['id'])) {

    header('location: index.php?status=error');

    exit;
}

$value = Receber::getID('*', 'receber', $_GET['id'], null, null);

$id = $value->id;

$listBoletos = Boleto::getBoletosListID('*', 'boletos', $_GET['id'], null, null, null);

if ($listBoletos != "") {

    foreach ($listBoletos as $item) {
        $boletos = Boleto::getID('*', 'boletos', $item->id, null, null, null);
        $boletos->excluir();
        $notafiscal = NotaFiscal::getID('*', 'notafiscal', $item->notafiscal_id, null, null, null);
        $notafiscal->excluir();
        $produtos = Produto::getIDProdutos('*', 'produtos', $item->notafiscal_id, null, null, null);

        foreach ($produtos as $item) {
            $res = Produto::getID('*', 'produtos', $item->id, null, null);
            $res->excluir();
        }
    }
}


    $notafiscal = NotaFiscal::getIDNotaFiscal('*', 'notafiscal',"'". $_GET['id']."'", null, null, null);
    
    foreach ($notafiscal as $res) {

        $cod = NotaFiscal::getID('*','notafiscal',$res->id,null,null,null);
        $cod->excluir();
    }

    $produtos = Produto::getIDProdutos('*', 'produtos', $res->id, null, null, null);

    foreach ($produtos as $item) {
        $res = Produto::getID('*', 'produtos', $item->id, null, null);
        $res->excluir();
    }  
    
    $value->excluir();

    header('location: receber-list.php?');
    exit;
