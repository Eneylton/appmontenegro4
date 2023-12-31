<?php

use App\Entidy\NotaFiscal;
use App\Entidy\UserCli;
use App\Session\Login;


$usuariologado = Login::getUsuarioLogado();

$acesso = $usuariologado['acessos_id'];

$usuario_id = $usuarios_id = $usuariologado['id'];

$usuario    = $usuarios_id = $usuariologado['nome'];

$mensagem = "";

$cliuser = UserCli::getIDCliUser('uc.id as id,c.id as cli_id,c.nome as nome', 'user_cli AS uc
INNER JOIN
clientes AS c ON (c.id = uc.clientes_id) ', "'" . $usuario_id . "'", null, null, null);

$cli_id = 0;

if ($acesso != 1) {
    if ($cliuser != false) {
        $cli_id = $cliuser->cli_id;
        $cli_nome = $cliuser->nome;
    } else {
        $mensagem = '
        
        <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Atenção!</h5>
        Você nâo tem Permissão para utilizar esse modilo !!!!!!.
    </div>';
    }
}

$chave            = "";
$chave            = "";
$cUF              = "";
$cNF              = "";
$natOp            = "";
$indPag           = "";
$mod              = "";
$serie            = "";
$nNF              = "";
$tpNF =            "";
$cMunFG =          "";
$tpImp =           "";
$tpEmis =          "";
$cDV =             "";
$tpAmb =           "";
$emit_CPF =        "";
$emit_CNPJ =       "";
$emit_xNome =      "";
$emit_xFant =      "";
$emit_xLgr =       "";
$emit_nro =        "";
$emit_xBairro =    "";
$emit_cMun =       "";
$emit_xMun =       "";
$emit_UF =         "";
$emit_CEP =        "";
$emit_cPais =      "";
$emit_xPais =      "";
$emit_fone =       "";
$emit_IE =         "";
$emit_IM =         "";
$emit_CNAE =       "";
$dest_cnpj =       "";
$dest_xNome =      "";
$dest_xLgr =       "";
$dest_nro =        "";
$dest_xBairro =    "";
$dest_cMun =       "";
$dest_xMun =       "";
$dest_UF =         "";
$dest_CEP =        "";
$dest_cPais =      "";
$dest_xPais =      "";
$dest_IE =         "";
$vBC =             "";
$vBC =             "";
$vICMS =           "";
$vICMS =           "";
$vBCST =           "";
$vBCST =           "";
$vST =             "";
$vST =             "";
$vProd =           "";
$vProd =           "";
$vNF =             "";
$vNF =             "";
$vFrete =          "";
$vSeg =            "";
$vDesc =           "";
$vIPI =            "";
$nProt =           "";
$un     =          "";
$resultados = '';
$contador  = 0;

if ($acesso != 1) {
    $listar = NotaFiscal::getList('n.id as id,
    n.data as data,
    n.notafiscal as notafiscal,
    n.serie as serie,
    n.razaosocial as razaosocial,
    n.chave as chave', 'notafiscal AS n
    INNER JOIN
    receber AS r ON (n.receber_id = r.id) WHERE r.clientes_id IN (SELECT 
    uc.clientes_id AS id_cliente
    FROM
    user_cli AS uc
        INNER JOIN
    clientes AS c ON (c.id = uc.clientes_id)
        INNER JOIN
    usuarios u ON (u.id = ' . $usuario_id . '))', null, null, 'n.id desc');
} else {
    $listar = NotaFiscal::getList(' n.id as id,
n.data as data,
n.notafiscal as notafiscal,
n.serie as serie,
n.razaosocial as razaosocial,
n.chave as chave', ' notafiscal AS n
INNER JOIN
receber AS r ON (n.receber_id = r.id)', null, null, 'id desc');
}

foreach ($listar as $item) {

    $contador += 1;

    $resultados .= '<tr>
        <td>' . $contador . '</td>
        <td>' . date('d/m/Y  Á\S  H:i:s', strtotime($item->data)) . '</td>
        <td>' . $item->notafiscal . ' - ' . $item->serie . '</td>
        <td style="text-transform:uppercase">' . $item->razaosocial . '</td>
        <td style="text-transform:uppercase">' . $item->chave . '</td>
        <td style="text-align:center"><i style="color:#28a745" class="fas fa-check"></i></td>
      
        <td style="text-align: center;">
          
         <a href="xml-delete.php?id=' . $item->id . '">
         <button disabled type="button" class="btn btn-danger"> <i class="fas fa-trash"></i></button>
         </a>
        </td>
        </tr>

        ';
}

$resultados = strlen($resultados) ? $resultados : '<tr>
                                       <td colspan="7
                                       " class="text-center" > Nenhum registro cadastrado !!!!</td>
                                       </tr>';


?>
<?= $mensagem ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card ">
                    <div class="card bg-dark">
                        <div class="modal-body">
                            <form id="form1" action="xml-list.php" method="post" enctype="multipart/form-data">
                                <div class="row">

                                    <div class="col-6">

                                        <input type="file" name="arquivo[]" class="form-control" value="" id="imagem" name="arquivo" multiple>


                                    </div>

                                    <div class=" col-6">

                                        <button type="submit" class="btn btn-primary">CARREGAR XML</button>

                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>


<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="table-responsive">

                        <table id="example" class="table table-bordered table-bordered table-hover table-striped">
                            <thead>

                                <tr>
                                    <th style="text-align: left; width:80px"> Nº</th>
                                    <th> DATA </th>
                                    <th> Nº DA NOTA FISCAL</th>
                                    <th> RAZÃO SOCIAL</th>
                                    <th> CHAVE</th>
                                    <th style="text-align:center"> STATUS</th>

                                    <th style="text-align: center; width:200px"> AÇÃO </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?= $resultados ?>
                            </tbody>

                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>