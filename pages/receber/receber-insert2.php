<?php

require __DIR__ . '../../../vendor/autoload.php';

use App\Entidy\Boleto;
use App\Entidy\Destinatario;
use App\Entidy\EntregadorQtd;
use App\Entidy\NotaFiscal;
use App\Entidy\Receber;
use App\Session\Login;

Login::requireLogin();

$usuariologado = Login::getUsuarioLogado();

$usuario = $usuariologado['id'];

$soma = 0;
$qtd_entrega = 0;
$contador = 0;
$val = 1;
$disponivel = 0;
$cont_disponivel = 0;
$qtd_disp  = 0;

if (isset($_POST['adicionar'])) {

    $boletoEdit = Boleto::getCodigo('*', 'boletos', "'" . $_POST['codbarra2'] . "'", null, null, null);

    if ($boletoEdit != false) {

        $boletoEdit->entregadores_id = $_POST['entregador_id'];
        $boletoEdit->atualizar();

        header('location: ../receber/entregador-boleto.php?entregador_id=' . $_POST['entregador_id'] . '&receber_id=' . $_POST['receber_id'] . '&qtd=' . $_POST['qtd']);
        exit;
    } else {

        $item2 = new Boleto;
        $item2->data            = $_POST['data'];
        $item2->vencimento      = $_POST['vencimento'];
        $item2->codigo          = $_POST['codbarra2'];
        $item2->destinatario    = $_POST['codbarra2'];
        $item2->tipo            = "BOLETO";
        $item2->status          = 3;
        $item2->ocorrencias_id  = 18;
        $item2->entregadores_id = $_POST['entregador_id'];
        $item2->receber_id      = $_POST['receber_id'];
        $item2->cadastar();

        $receber = Receber::getID('*', 'receber', $_POST['receber_id'], null, null, null);

        $qtd = $receber->qtd;

        $receber_id = $receber->id;

        $cont_total = $qtd + 1;

        $receber->qtd = $cont_total;

        $receber->atualizar();

        $entrQtd = EntregadorQtd::getListEntregadorQTD('*', 'entregador_qtd', 'receber_id=' . $_POST['receber_id'] . ' AND entregadores_id=' . $_POST['entregador_id']);

        $qtd_entrega = $entrQtd->qtd + 1;

        $entrQtd->qtd = $qtd_entrega;

        $entrQtd->atualizar();

        header('location: ../receber/entregador-boleto.php?entregador_id=' . $_POST['entregador_id'] . '&receber_id=' . $_POST['receber_id'] . '&qtd=' . $_POST['qtd']);
        exit;
    }
}

if (isset($_POST['receber_id'])) {

    $boletoEdit = Boleto::getCodigo('*', 'boletos', "'" . $_POST['codbarra2'] . "'", null, null, null);

    if ($boletoEdit != false) {

        header('location: ../lotes/lote-form-list.php?id_param=' . $_POST['receber_id'] . '&status=error');
        exit;
    }

    if (isset($_POST['codbarra2']) == "") {
        $cont = $_POST['qtd'];
    } else {
        $cont = 1;
    }


    $receber = Receber::getID('*', 'receber', $_POST['receber_id'], null, null, null);

    $qtd = $receber->qtd;
    $disp = $receber->disponivel;
    $receber_id = $receber->id;

    $cont_total = $cont + $qtd;
    $cont_disp = $cont + $disp;
}


$cont2 = 0;
$comparar = "COD_CONTRATO ";
$contar = 0;
$arquivos = [];
$arquivo_tmp = $_FILES['arquivo']['tmp_name'];

foreach ($arquivo_tmp as $key) {

    $arquivos  = file($key);

    foreach ($arquivos  as $contar) {

        $linha2 = trim($contar);
        $valor2 = explode(';', $contar);
        $codigo2 = str_replace('"', '', $valor2[0]);


        if ($codigo2 === $comparar) {

            $cont2 = 0;
        } else {
            $cont += 1;
            $contador += 1;
            $cont_disp = ($disp + $contador);
        }
    }
}

if (!isset($arquivo_tmp)) {

    $id_rec  = $receber->id;
    $receber->data            = $_POST['data'];
    $receber->vencimento      = $_POST['vencimento'];
    $receber->disponivel      = $cont_disp;
    $receber->qtd             = $cont_total;
    $receber->setores_id      = 3;
    $receber->numero          = $_POST['numero'];
    $receber->servicos_id     = 1;
    $receber->clientes_id     = $_POST['clientes_id'];
    $receber->usuarios_id     = $usuario;
    $receber->remessa         = 'RM-940119';
    $receber->gaiolas_id      = $id_gaiola;
    $receber->atualizar();



    $numero_de_bytes = 18;
    $restultado_bytes = random_bytes($numero_de_bytes);
    $codigo = bin2hex($restultado_bytes);

    $nota = new NotaFiscal;
    $nota->valoricms            = 1;
    $nota->data                 = $_POST['data'];
    $nota->chave                = $codigo;
    $nota->autorizacao          = 478512422;
    $nota->notafiscal           = 47852;
    $nota->serie                = 77;
    $nota->cnpj                 = 74558277800012;
    if ($emit_xFant != "") {
        $nota->razaosocial = 'MonteNEgro';
    } else {
        $nota->razaosocial = 'MonteNEgro';
    }
    $nota->inscricaoestadual    = 8788481;
    $nota->bcicms               = 0;
    $nota->totalproduto         = 0;
    $nota->frete                = 1;
    $nota->desconto             = 1;
    $nota->totalipi             = 5;
    $nota->totalnota            = 78;
    $nota->usuarios_id          = $usuario;
    $nota->receber_id           = $id_rec;

    $nota->cadastar();

    $notaID = $nota->id;

    $dest = new Destinatario;
    $dest->cpf            = 62878452157;
    $dest->nome           = 'MonteNegro Express';
    $dest->logradouro     = 'Rua 25';
    $dest->numero         = 'Nº 25';
    $dest->bairro         = 'Calhau';
    $dest->municipio      = 'São Luís';
    $dest->uf             = 'Ma';
    $dest->cep            = '65404511';
    $dest->pais           = 'Brasil';
    $dest->telefone       = '(98) 9158-4758';
    $dest->email          = 'montenegroexpress@gmail.com';
    $dest->notafiscal_id  = $notaID;
    $dest->cadastar();

    $destID = $dest->id;

    $item2 = new Boleto;
    $item2->data             = $_POST['data'];
    $item2->vencimento       = $_POST['vencimento'];
    $item2->codigo           = $_POST['codbarra2'];
    $item2->tipo             = 'BOLETOS';
    $item2->nota             = 45588 - 52;
    $item2->destinatario     = 'MonteNegro Express';
    $item2->status           = 3;
    $item2->entregadores_id  = 195;
    $item2->ocorrencias_id   = 18;
    $item2->destinatario_id  = $destID;
    $item2->remessa         = 'RM-940119';
    $item2->receber_id       = $receber_id;

    $item2->cadastar();

    header('location: ../lotes/lote-form-list.php?id_param=' . $receber_id);
    exit;
} else {


    $receber->data            = $_POST['data'];
    $receber->vencimento      = $_POST['vencimento'];
    $receber->qtd             = $cont;
    $receber->disponivel      = $cont_disp;
    $receber->setores_id      = 3;
    $receber->numero          = $_POST['numero'];
    $receber->servicos_id     = 3;
    $receber->clientes_id     = $_POST['clientes_id'];
    $receber->usuarios_id     = $usuario;
    $receber->gaiolas_id      = 117;
    $receber->atualizar();

    foreach ($arquivo_tmp as $key) {

        $dados  = file($key);

        foreach ($dados  as $linha) {


            $linha = trim($linha);
            $valor = explode(';', $linha);

            $codigo = str_replace('"', '', $valor[0]);
            $codigo2 = str_replace('"', '', $valor[1]);
            $destinatario = str_replace('"', '', $valor[2]);
            $numero = str_replace('"', '', $valor[5]);
            $endereco1 = str_replace('"', '', $valor[4]);
            $endereco2 = iconv('UTF-8', 'ISO-8859-1', $endereco1);
            $logradouro = str_replace('"', '', $valor[6]);
            $logradouro2 = iconv('UTF-8', 'ISO-8859-1', $logradouro);
            $bairro = str_replace('"', '', $valor[7]);
            $municipio = str_replace('"', '', $valor[8]);
            $estado = str_replace('"', '', $valor[9]);
            $cep = str_replace('"', '', $valor[10]);
            $tipo = str_replace('"', '', $valor[11]);
            $status = 3;

            if ($codigo === $comparar) {

                echo "";
            } else {

                $item2 = new Boleto;
                $item2->data            = $_POST['data'];
                $item2->vencimento      = $_POST['vencimento'];
                $item2->codigo          = $codigo;
                $item2->destinatario    = $destinatario;
                $item2->tipo            = $tipo;
                $item2->status          = $status;
                $item2->ocorrencias_id  = 18;
                $item2->entregadores_id = 195;
                $item2->receber_id      = $receber_id;

                $item2->cadastar();
            }
        }
    }



    header('location: ../lotes/lote-list.php?');
    exit;
}
