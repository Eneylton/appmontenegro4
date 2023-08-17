<?php

require __DIR__ . '../../../vendor/autoload.php';

use App\Entidy\NotaFiscal;
use App\Session\Login;

Login::requireLogin();

if (!isset($_GET['id']) or !is_numeric($_GET['id'])) {

    header('location: index.php?status=error');

    exit;
}

$value = NotaFiscal::getID('*', 'notafiscal', $_GET['id'], null, null);

if (!$value instanceof NotaFiscal) {
    header('location: index.php?status=error');

    exit;
}

if (!isset($_POST['excluir'])) {


    $value->excluir();

    header('location: xml-list.php?status=del');

    exit;
}