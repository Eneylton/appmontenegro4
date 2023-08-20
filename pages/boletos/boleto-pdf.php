<?php

require __DIR__ . '../../../vendor/autoload.php';

use App\Entidy\Boleto;
use App\Entidy\Entregador;
use App\Session\Login;

$usuariologado = Login::getUsuarioLogado();
$usuarios_nome = $usuariologado['nome'];
$usuarios_email = $usuariologado['email'];

Login::requireLogin();

$contador = 0;

$buscar = "buscar";

$res = "";
$nome = "";

if ($_GET['item_id'] == "<br />") {

    $id_param = "";
} else {
    $id_param = $_GET['item_id'];
}



if (!isset($_GET['status'])) {

    $status  = "";

    $condicoes = [
        strlen($buscar) ? '
                           
                           b.status LIKE "%' . str_replace(' ', '%', $status) . '%"' : null
    ];
} else {
    $status  = $_GET['status'];

    if ($status == 4) {

        $status = 'b.status NOT IN (3)';

        $condicoes = [
            strlen($buscar) ? 'b.status NOT IN (3)' : null
        ];
    } else {

        $condicoes = [
            strlen($buscar) ? '
                               
                               b.status LIKE "%' . str_replace(' ', '%', $status) . '%"' : null
        ];
    }
}



$condicoes = array_filter($condicoes);

$where = implode(' AND ', $condicoes);

if ($_GET['entregadores_id'] != 0) {

    if (isset($_GET['entregadores_id'])) {
        if ($_GET['entregadores_id'] != 0) {

            $id_entregador = $_GET['entregadores_id'];

            $entregador = Entregador::getID('*', 'entregadores', $id_entregador, null, null, null);

            $nome_entregador = $entregador->apelido;
            $nome = $entregador->nome;
        } else {
            $id_entregador = "";
            $nome_entregador = "RELATÓRIO GERAL DE ENTREGADORES...";
        }
    }


    $listar = Boleto::getList('b.id,b.data_fim as vencimento,b.data as data_inicio,
    b.nota,
    b.sequencia,
    b.codigo,
    b.cliente,
    b.status,
    b.receber_id,
    b.ocorrencias_id,
    b.entregadores_id,
    b.notafiscal_id,
    b.destinatario_id,
    b.tipo,
    b.ocorrencias_id AS ocorrencias_id,
    o.nome AS ocorrencia,e.id as entregadores_id,
    e.apelido,
    d.nome,
    d.cpf,
    d.logradouro,
    d.numero,
    d.bairro,
    d.municipio,
    d.uf,
    d.cep,
    d.flag,
    d.pais,
    d.telefone,
    d.email', ' boletos AS b
    INNER JOIN
    destinatario AS d ON (b.destinatario_id = d.id)
    INNER JOIN
    ocorrencias AS o ON (o.id = b.ocorrencias_id)
    INNER JOIN
    entregadores AS e ON (b.entregadores_id = e.id)', ' b.receber_id=' . $id_param . ' AND b.entregadores_id=' . $_GET['entregadores_id'], null, 'b.sequencia ASC', null);
} else if ($_GET['id_entregador'] != 0) {


    if (isset($_GET['id_entregador'])) {

        if ($_GET['id_entregador'] != 0) {

            $id_entregador = $_GET['id_entregador'];

            $entregador = Entregador::getID('*', 'entregadores', $id_entregador, null, null, null);

            $nome_entregador = $entregador->apelido;
            $nome = $entregador->nome;
        } else {

            $id_entregador = "";
            $nome_entregador = "RELATÓRIO GERAL DE ENTREGADORES...";
        }
    }

    $listar = Boleto::getList('b.id,b.data_fim as vencimento,b.data as data_inicio,
    b.nota,
    b.sequencia,
    b.codigo,
    b.cliente,
    b.status,
    b.receber_id,
    b.ocorrencias_id,
    b.tipo,
    b.entregadores_id,
    b.notafiscal_id,
    b.destinatario_id,
    b.ocorrencias_id AS ocorrencias_id,
    o.nome AS ocorrencia,e.id as entregadores_id,
    e.apelido,
    d.nome,
    d.cpf,
    d.logradouro,
    d.numero,
    d.bairro,
    d.municipio,
    d.uf,
    d.cep,
    d.flag,
    d.pais,
    d.telefone,
    d.email', ' boletos AS b
    INNER JOIN
    destinatario AS d ON (b.destinatario_id = d.id)
    INNER JOIN
    ocorrencias AS o ON (o.id = b.ocorrencias_id)
    INNER JOIN
    entregadores AS e ON (b.entregadores_id = e.id)', ' b.receber_id=' . $id_param . ' AND b.entregadores_id=' . $_GET['id_entregador'], null, 'b.sequencia ASC', null);
} else {

    $nome_entregador = "RELATÓRIO GERAL DE ENTREGADORES...";

    $listar = Boleto::getList('b.id,b.data_fim as vencimento,b.data as data_inicio,
    b.nota,
    b.sequencia,
    b.codigo,
    b.cliente,
    b.status,
    b.receber_id,
    b.ocorrencias_id,
    b.entregadores_id,
    b.notafiscal_id,
    b.destinatario_id,
    b.tipo,
    b.ocorrencias_id AS ocorrencias_id,
    o.nome AS ocorrencia,e.id as entregadores_id,
    e.apelido,
    d.nome,
    d.cpf,
    d.logradouro,
    d.numero,
    d.bairro,
    d.municipio,
    d.uf,
    d.cep,
    d.flag,
    d.pais,
    d.telefone,
    d.email', ' boletos AS b
    INNER JOIN
    destinatario AS d ON (b.destinatario_id = d.id)
    INNER JOIN
    ocorrencias AS o ON (o.id = b.ocorrencias_id)
    INNER JOIN
    entregadores AS e ON (b.entregadores_id = e.id)', 'b.receber_id=' . $id_param, null, 'b.sequencia ASC', null);
}



foreach ($listar as $item) {

    $vencimento = $item->data_inicio;

    $contador += 1;


    if ($item->status == 1) {

        $cores = '<span style="color:green"> ENTREGUE</span>';
    } else if ($item->status == 2) {

        $cores = '<span style="color:#ff0000"> DEVOLVIDO</span>';
    } else if ($item->status == 3) {

        $cores = '<span style="color:#e3a800"> AGUARDANDO</span>';
    } else if ($item->status == 4) {

        $cores = '<span style="color:#0c77b1">EM ROTA</span>';
    } else {

        $cores = '<span style="color:#e3a800"> --- </span>';
    }

    if ($item->obs == "") {

        $texto = "Nenhuma ....";
    } else {
        $texto = $item->obs;
    }

    $res .= '   <tr>
                        <td style="text-transform:uppercase;font-size:8px;text-align:center;width:50px">'   . $contador . '</td>
                        <td style="text-transform:uppercase;font-size:8px;text-align:left">'  . substr($item->codigo, -50)  . '</td>
                        <td style="text-transform:uppercase;font-size:8px;text-align:left;;width:40px">'  . $item->nota  . '</td>
                        <td style="text-transform:uppercase;font-size:8px;text-align:left;width:150px">' . date('d/m/Y  Á\S  H:i:s', strtotime($item->data_inicio)) . ' | '  . date('d/m/Y  Á\S  H:i:s', strtotime($item->vencimento)) . '</td>
                        <td style="text-transform:uppercase;font-size:8px;text-align:left;width:150px">' . substr($item->nome, 0) . '</td>
                        <td style="text-transform:uppercase;font-size:8px;text-align:left;width:150px">' . $item->logradouro . '
                        - ' . $item->numero . ' - ' . $item->bairro . '- ' . $item->municipio . ' - ' . $item->uf . '</td>
                        <td style="text-transform:uppercase;font-size:8px;;width:80px">' . $item->tipo . '</td>
                        <td style="text-transform:uppercase;font-size:8px;text-align:left;;width:160px">'  . $item->ocorrencia . '</td>
                        <td style="text-transform:uppercase;font-size:8px;text-align:left;width:50px">'  . $cores . '</td>
                       
                </tr>
                ';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @page {
            margin: 20px 0;
            margin-left: 0px;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "Open Sans", sans-serif;
        }

        .header {
            position: fixed;
            top: -70px;
            left: 0;
            right: 0;
            width: 100%;
            text-align: center;
            background-color: #555555;
            padding: 10px;
        }

        .header img {
            width: 160px;
        }

        .footer {
            bottom: -27px;
            left: 0;
            width: 100%;
            padding: 5px 10px 10px 10px;
            text-align: center;
            background: #555555;
            color: #fff;
        }

        .footer .page:after {
            content: counter(page);

        }

        table {
            width: 100%;
            border: 1px solid #555555;
            margin: 0;
            padding: 0;
        }

        th {
            text-transform: uppercase;
        }

        table,
        th,
        td {
            font-size: xx-small;
            border: 1px solid #555555;
            border-collapse: collapse;
            text-align: center;
            padding: 5px;

        }

        tr:nth-child(2n+0) {
            background: #eeeeee;
        }

        p {
            color: #888888;
            margin: 0;
            text-align: center;
        }

        h2 {
            text-align: center;

        }
    </style>

    <title>Controle de Envio</title>
</head>

<body>

    <table style="margin-top: 0px;">
        <tbody>
            <tr>

                <td colspan="4" style="text-align:left; border:1px solid #fff;"> <span style="margin-left:110px; margin-top: -50px; font-size:small">Montenegro express </span><br>
                    <span style="margin-left:110px; margin-top: -30px; font-size:xx-small ">Email:&nbsp;
                        <?= $usuarios_email  ?> </span><br>
                    <span style="margin-left:110px; margin-top: -30px; font-size:xx-small">Atendente:&nbsp;
                        <?= $usuarios_nome  ?> </span><br>
                    <img style="width:108px; height:40px; float:left;margin-top:-50px; padding:10px; margin-left:-12px;" src="../../02.jpeg">
                    <br />
                    <br />
                </td>
                <td colspan="3" style="font-size:13px;border:1px solid #fff;text-align:left;font-weight:bold;">
                    RELATÓRIO
                    GERAL - CONTROLE DE ENVIO</td>

                <td colspan="2" style="font-size:11px;border:1px solid #fff;text-align:center;">
                    Vencimento: <?= date('d/m/Y', strtotime($vencimento)) ?></td>

            </tr>

        </tbody>
    </table>

    <table style="margin-right:50px;margin-left:-10">
        <tbody>
            <tr>
                <td colspan="2">
                    <span>Entregador :</span>
                </td>
                <td colspan="7" style="text-transform:uppercase; text-align:left">
                    <span style="text-transform: capitalize;"><?= $nome ?></span>

                </td>
            </tr>

            <tr style="background-color: #000; color:#fff">

                <td style="text-align:center;font-size:8px"> Nº</td>
                <td style="text-align:left;font-size:8px;"> CHAVE</td>
                <td style="text-align:left;font-size:8px;"> Nº NOTA</td>
                <td style="text-align:left;font-size:8px"> PRAZO ENTREGA</td>
                <td style="text-align:left;font-size:8px"> CONSULTOR</td>
                <td style="text-align:left;font-size:8px"> ENDEREÇO</td>
                <td style="text-align:left;font-size:8px"> TIPO</td>
                <td style="text-align:left;font-size:8px"> OCORRÊNCIA</td>
                <td style="text-align:left;font-size:8px"> STATUS</td>

            </tr>

            <?= $res ?>

        </tbody>
        <tr>


            <td colspan="6" style="border-color:#fff"></td>
            <td colspan="3" style="text-align: left; height: 70px;border-color:#fff">

                ____________________________________________________
                <p><span style="text-transform: uppercase;margin-right:0px;"><?= $nome_entregador ?></span></p>
            </td>
        </tr>


    </table>

</body>

</html>