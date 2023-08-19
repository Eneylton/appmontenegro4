<?php 

require __DIR__.'../../../vendor/autoload.php';

use App\Entidy\UserCli;
use App\Entidy\Usuario;
use App\Session\Login;

$alertaCadastro = '';

define('TITLE','Editar Usuários');
define('BRAND','Editar Usuários');

Login::requireLogin();

if(!isset($_GET['id']) or !is_numeric($_GET['id'])){
 
    header('location: index.php?status=error');

    exit;
}

$usuarios = Usuario::getID('*','usuarios',$_GET['id'],null,null,null);


if(!$usuarios instanceof Usuario){
    header('location: index.php?status=error');

    exit;
}

foreach ($_GET['clientes'] as $item) {


    $listaClientes = UserCli::getIDCli('*', 'user_cli ', $_GET['id'], null, null);

    if ($listaClientes != false) {

        foreach ($listaClientes as $item) {

            $delete = UserCli::getID('*', 'user_cli ', $item->id, null, null);

            $delete->excluir();
        }
    }
}

foreach ($_GET['clientes'] as $item) {
     
    $usecli = new UserCli;
    $usecli->usuarios_id  = $_GET['id'];
    $usecli->clientes_id  = $item;
    $usecli->cadastar();
}




if(isset($_GET['nome'])){

    $usuarios->nome           = $_GET['nome'];
    $usuarios->email          = $_GET['email'];
    $usuarios->acessos_id     = $_GET['acessos_id'];
    $usuarios->cargos_id      = $_GET['cargo_id'];
    $usuarios->senha          = password_hash($_GET['senha'], PASSWORD_DEFAULT);
    $usuarios->atualizar();

    header('location: usuario-list.php?status=edit');
    exit;
    
}


