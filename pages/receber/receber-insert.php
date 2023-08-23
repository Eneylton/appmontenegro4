<?php

require __DIR__ . '../../../vendor/autoload.php';

use App\Entidy\Boleto;
use App\Entidy\Destinatario;
use App\Entidy\NotaFiscal;
use App\Entidy\Receber;
use App\Session\Login;

Login::requireLogin();

$usuariologado = Login::getUsuarioLogado();

$usuario = $usuariologado['id'];

$comparar = "COD_CONTRATO ";

$id_gaiola = 117;

$cont = 0;
$cont2 = 0;
$contar = 0;
$arquivos = [];
$array    = [];
$arquivo_tmp = $_FILES['arquivo']['tmp_name'];

$soma = 0;
$qtd_entrega = 0;
$contador = 0;
$val = 1;
$disponivel = 0;
$cont_disponivel = 0;
$qtd_disp  = 0;
$reslt   = "";
$linha2 = "";
$valor2 = 0;
$codigo2 = 0;

foreach ($arquivo_tmp as $key) {

    $arquivos  = file($key);

    if ($arquivos != false) {
        foreach ($arquivos  as $contar) {

            $linha2 = trim($contar);
            $valor2 = explode(';', $contar);
            $codigo2 = str_replace('"', '', $valor2[0]);


            if ($codigo2 === $comparar) {

                $cont2 = 0;
            } else {
                $cont += 1;
            }
        }
    }
}

if ($arquivos != false) {


    $item = new Receber;
    $item->data            = $_POST['data'];
    $item->vencimento      = $_POST['vencimento'];
    $item->disponivel      = $cont;
    $item->qtd             = $cont;
    $item->setores_id      = 3;
    $item->numero          = $_POST['numero'];
    $item->servicos_id     = 3;
    $item->clientes_id     = $_POST['clientes_id'];
    $item->usuarios_id     = $usuario;
    $item->gaiolas_id      = $id_gaiola;
    $item->cadastar();

    $receber_id = $item->id;


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
                $nota->receber_id           = $receber_id;

                $nota->cadastar();

                $notaID = $nota->id;

                $dest = new Destinatario;
                $dest->cpf            = 62878452157;
                $dest->nome           = $destinatario;
                $dest->logradouro     = $logradouro2;
                $dest->numero         = $numero;
                $dest->bairro         = $bairro;
                $dest->municipio      = $municipio;
                $dest->uf             = $estado;
                $dest->cep            = $cep;
                $dest->pais           = 'Brasil';
                $dest->telefone       = '(98) 9158-4758';
                $dest->email          = 'montenegroexpress@gmail.com';
                $dest->notafiscal_id  = $notaID;
                $dest->cadastar();

                $destID = $dest->id;

                $item2 = new Boleto;
                $item2->data             = $_POST['data'];
                $item2->vencimento       = $_POST['vencimento'];
                $item2->codigo           = $codigo;
                $item2->tipo             = 'BOLETOS';
                $item2->nota             =  45588 - 52;
                $item2->destinatario     = 'MonteNegro Express';
                $item2->status           = 3;
                $item2->entregadores_id  = 195;
                $item2->ocorrencias_id   = 18;
                $item2->destinatario_id  = $destID;
                $item2->remessa         = 'RM-940119';
                $item2->receber_id       = $receber_id;

                $item2->cadastar();
            }
        }
    }

    header('location: ../lotes/lote-list.php?');
    exit;
} else {

    if ($_POST['qtd'] != "") {

        $cont = $_POST['qtd'];

        $item = new Receber;
        $item->data            = $_POST['data'];
        $item->vencimento      = $_POST['vencimento'];
        $item->disponivel      = $cont;
        $item->qtd             = $cont;
        $item->setores_id      = 3;
        $item->numero          = $_POST['numero'];
        $item->servicos_id     = 3;
        $item->clientes_id     = $_POST['clientes_id'];
        $item->usuarios_id     = $usuario;
        $item->gaiolas_id      = $id_gaiola;
        $item2->remessa        = 'RM-940119';
        $item->cadastar();
        $receber_id = $item->id;

        for ($i = 0; $i < $cont; $i++) {

            $numero_de_bytes = 6;
            $restultado_bytes = random_bytes($numero_de_bytes);
            $codigo23 = bin2hex($restultado_bytes);

            $nota = new NotaFiscal;
            $nota->valoricms            = 1;
            $nota->data                 = $_POST['data'];
            $nota->chave                = $codigo23;
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
            $nota->receber_id           = $receber_id;

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

            $numero_de_bytes = 20;
            $restultado_bytes = random_bytes($numero_de_bytes);
            $codigo44 = bin2hex($restultado_bytes);

            $item2 = new Boleto;
            $item2->data             = $_POST['data'];
            $item2->vencimento       = $_POST['vencimento'];
            $item2->codigo           = $codigo44;
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
        }

        header('location: ../lotes/lote-list.php?');
        exit;
    } else {

        $item = new Receber;
        $item->data            = $_POST['data'];
        $item->vencimento      = $_POST['vencimento'];
        $item->disponivel      = 1;
        $item->qtd             = 1;
        $item->setores_id      = 3;
        $item->numero          = $_POST['numero'];
        $item->servicos_id     = 3;
        $item->clientes_id     = $_POST['clientes_id'];
        $item->usuarios_id     = $usuario;
        $item->gaiolas_id      = $id_gaiola;
        $item->cadastar();
        $receber_id = $item->id;

        $numero_de_bytes4 = 14;
        $restultado_bytes4 = random_bytes($numero_de_bytes4);
        $codigo4 = bin2hex($restultado_bytes4);


        $nota = new NotaFiscal;
        $nota->valoricms            = 1;
        $nota->data                 = $_POST['data'];
        $nota->chave                = $codigo4;
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
        $nota->receber_id           = $receber_id;

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
        $item2->codigo           = $_POST['codbarra'];
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
    }
}

header('location: ../lotes/lote-list.php?');
exit;

// FIM