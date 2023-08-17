<?php

require __DIR__ . '../../../vendor/autoload.php';

use App\Entidy\Boleto;
use App\Session\Login;

Login::requireLogin();
$usuariologado = Login::getUsuarioLogado();
$usuarios_nome = $usuariologado['nome'];
$usuarios_email = $usuariologado['email'];

$data = "";

if ($chave == "") {

    $var1 = "";
} else {

    $var1 =  $chave;
}

$res = "";

$listar = Boleto::getList(
    'b.id as id,b.coleta as data,b.nota as nota,b.codigo as codigo, b.remessa as remessa, b.cliente as cliente ',
    'boletos as b  Where b.codigo like '."'%". $var1."%'",
    null,
    null,
    null
);

foreach ($listar as $item) {

    $data = $item->data;

    $res .= '   <tr>
    <td style="text-align:left">' . $item->id . '</td>
    <td style="text-align:left">' .  date('d/m/Y', strtotime($item->data))  . '</td>
    <td style="text-align:left">' . $item->nota . '</td>
    <td style="text-align:left">' . $item->codigo . '</td>
    <td style="text-transform:uppercase;text-align:left">' . $item->remessa . '</td>
    <td style="text-transform:uppercase;text-align:left">R$ ' . $item->cliente . '</td>
  
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
        margin: 70px 0;
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

    <title>Receber itens</title>
</head>

<body>

    <table style="margin-top: -40px;">
        <tbody>
            <tr style="background-color: #fff; color:#000">

                <td style="text-align: left; width:260px; border:1px solid #fff; ">
                    <span style="margin-left:110px; margin-top: -50px; font-size:small">Montenegro express </span><br>
                    <span style="margin-left:110px; margin-top: -30px; font-size:xx-small ">Email:&nbsp;
                        <?= $usuarios_email  ?> </span><br>
                    <span style="margin-left:110px; margin-top: -30px; font-size:xx-small">Atendente:&nbsp;
                        <?= $usuarios_nome  ?> </span><br>
                    <img style="width:108px; height:40px; float:left;margin-top:-50px; padding:10px; margin-left:-12px;"
                        src="../../02.jpeg">
                    <br />
                    <br />

                </td>
                <td style="text-align:center; font-weight:600; font-size:16px; border:1px solid #fff;">• BUSCAR NOTA FISCAL ELETRÔNOCA
                     •</td>
                <td style="text-align:right; border:1px solid #fff;margin-left:-10">
                    <?php echo "Data: " . date('d/m/Y', strtotime($data));
                    echo " á " . date('d/m/Y', strtotime($data))  ?><br></td>

            </tr>
        </tbody>
    </table>


    <table style="margin-top:-10px;">
        <tbody>

        
            <tr style="background-color:#d10d0d; color:#fff;border-color:1px solid #fff;">
                <td style="text-align: center; text-transform:uppercase; border-color:1px solid #fff;" colspan="6">Nota Fiscal Eletrônica</td>
            </tr>

            <tr>
                <td style="text-align: left; width:80px"> CÓDIGO </td>
                <td style="text-align:left"> DATA</td>
                <td style="text-align:left"> Nº NOTA</td>
                <td style="text-align:left"> CHAVE</td>
                <td style="text-align:left"> REMESSA</td>
                <td style="text-align:left"> CLIENTE </td>

            </tr>

            <?= $res ?>

        </tbody>
    </table>

</body>

</html>