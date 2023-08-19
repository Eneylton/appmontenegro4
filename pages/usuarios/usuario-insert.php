<?php 

require __DIR__.'../../../vendor/autoload.php';

define('TITLE','Novo Usuário');
define('BRAND','Cadastrar Usuário');

use App\Entidy\UserCli;
use App\Entidy\Usuario;
use App\Session\Login;


$usuariologado = Login:: getUsuarioLogado();

$usuario = $usuariologado['id'];

Login::requireLogin();

if(isset($_POST['nome'],$_POST['email'],$_POST['senha'])){

    $userEmail = Usuario:: getUsuariosEmail('*','usuarios',$_POST['email'],null,null);

    if($userEmail instanceof Usuario){

        header('location: usuario-list.php?status=mail');
        exit;
        
    }else{

        $user = new Usuario;
        $user->nome = $_POST['nome'];
        $user->email = $_POST['email'];
        $user->cargos_id = $_POST['cargos_id'];
        $user->acessos_id = $_POST['acessos_id'];
        $user->senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    
        $user->cadastar();

        $id = $user->id;

        
if (isset($_POST['clientes'])) {

    foreach ($_POST['clientes'] as $key) {
        
        $usercli = new UserCli;
        $usercli->usuarios_id = $id;
        $usercli->clientes_id = $key;
        $usercli->cadastar();
    }
}

        header('location: usuario-list.php?status=success');
        exit;
    }
  
} 
