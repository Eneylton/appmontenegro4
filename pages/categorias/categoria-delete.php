<?php 

require __DIR__.'../../../vendor/autoload.php';

use \App\Entidy\Categoria;
use   \App\Session\Login;


Login::requireLogin();



if(!isset($_GET['id']) or !is_numeric($_GET['id'])){
 
    header('location: index.php?status=error');

    exit;
}

$value = Categoria::getID('*','categorias',$_GET['id'],null,null);

if(!$value instanceof Categoria){
    header('location: index.php?status=error');

    exit;
}



if(!isset($_POST['excluir'])){
    
 
    $value->excluir();

    header('location: categoria-list.php?status=del');

    exit;
}

