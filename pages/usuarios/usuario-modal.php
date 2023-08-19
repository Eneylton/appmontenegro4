<?php

use App\Entidy\Acesso;
use App\Entidy\Cargo;
use App\Entidy\Cliente;
use App\Entidy\UserCli;
use App\Entidy\Usuario;

require __DIR__ . '../../../vendor/autoload.php';

$dados = "";

$select_cli = "";
$select_cargo = "";
$select_acesso = "";

$selected = "";
$selected_cargo = "";
$selected_acesso = "";

$param = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

$usuario = Usuario::getID('*','usuarios',$param,null,null,null);

$id = $usuario->id;

$nome  = $usuario->nome;

$email = $usuario->email;

$clientes = Cliente::getList('*','clientes');

$cargos = Cargo::getList('*','cargos');

$acessos = Acesso::getList('*','acessos');

foreach ($clientes as $item) {
    
    $userCli = UserCli::getIDUserCli('*','user_cli',$id. ' AND clientes_id= '.$item->id,null,null,null);
 
    if ($userCli != false) {
 
       if ($item->id == $userCli->clientes_id) {
 
          $selected = "selected";
       } else {
 
          $selected = "";
       }
    } else {
       $selected = "";
    }
 
    $select_cli .= '<option value="' . $item->id . '"  ' . $selected . '>' . $item->nome . '</option>';
 }

 foreach ($cargos as $item) {
    
    $userCargo = Usuario::getIDCargo('*','usuarios',$item->id,null,null,null);
 
    if ($userCargo != false) {
 
       if ($item->id == $userCargo->cargos_id) {
 
          $selected_cargo = "selected";
       } else {
 
          $selected_cargo = "";
       }
    } else {
       $selected_cargo = "";
    }
 
    $select_cargo .= '<option value="' . $item->id . '"  ' . $selected_cargo . '>' . $item->nome . '</option>';
 }


 foreach ($acessos as $item) {
    
    $userAcesso = Usuario::getIDAcessos('*','usuarios',$item->id,null,null,null);
 
    if ($userAcesso != false) {
 
       if ($item->id == $userAcesso->cargos_id) {
 
          $selected_acesso = "selected";
       } else {
 
          $selected_acesso = "";
       }
    } else {
       $selected_acesso = "";
    }
 
    $select_acesso .= '<option value="' . $item->id . '"  ' . $selected_acesso . '>' . $item->nivel . '</option>';
 }

 
$dados .= '<input type="hidden" name="id" value="'.$id.'">
           <div class="row">
                <div class="col-6">
                <div class="form-group">
                <label>Nome</label>
                 <input type="text" class="form-control" name="nome" value="'.$nome.'" required>
                </div>
                </div>

                <div class="col-6">
                <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" name="email" value="'.$email.'" required>
                </div>
                </div>
                
                <div class="col-12">
                <div class="form-group">
                <label>Clientes</label>
                <select class="form-control select" style="width: 100%;" name="clientes[]" multiple>
                
                ' . $select_cli . '
                
                </select>
                </div>
                
                </div>
                <div class="col-6">
                <div class="form-group">
                <label>Cargo</label>
                <select class="form-control select" style="width: 100%;" name="cargo_id">

                ' . $select_cargo . '

                </select>

                </div>
                </div>
                <div class="col-6">
                <div class="form-group">
                <label>Nível de Acesso</label>
                <select class="form-control select" style="width: 100%;" name="acessos_id">

                ' . $select_acesso . '

                </select>

                </div>
                </div>

                <div class="col-12">
                <div class="form-group">
                <label>Senha</label>
                <input type="password" placeholder="Senha" id="password" class="form-control" name="senha" required>
                
                </div>
                </div>

                <div class="col-12">
                <div class="form-group">
                <label>Confirma Senha</label>
                <input type="password" placeholder="Senha" id="password" class="form-control" name="senha" required>
                </div>
                </div>
     
            </div>';

$retorna = ['erro' => false, 'dados' => $dados];

echo json_encode($retorna);