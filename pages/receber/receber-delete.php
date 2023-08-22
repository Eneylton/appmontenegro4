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

if (!isset($_GET['id']) or !is_numeric($_GET['id'])) {

    header('location: index.php?status=error');

    exit;
}

$value = Receber::getID('*', 'receber', $_GET['id'], null, null);

$id = $value->id;

$listBoletos = Boleto::getBoletosListID('*', 'Boletos', $_GET['id'], null, null, null);

if ($listBoletos != "") {

    foreach ($listBoletos as $item) {
        $boletos = Boleto::getID('*', 'boletos', $item->id, null, null, null);
        $notafiscal = NotaFiscal::getID('*', 'notafiscal', $item->notafiscal_id, null, null, null);
        $boletos->excluir();
        $notafiscal->excluir();
        $produtos = Produto::getIDProdutos('*', 'produtos', $item->notafiscal_id, null, null, null);

        foreach ($produtos as $item) {
            $res = Produto::getID('*', 'produtos', $item->id, null, null);
            $res->excluir();
        }
    }
}

if (!isset($_POST['excluir'])) {
    $value->excluir();
    header('location: receber-list.php?status=del');

    exit;
}
