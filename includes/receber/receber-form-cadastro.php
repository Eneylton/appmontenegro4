<?php

use App\Entidy\EntregadorQtd;
use App\Entidy\Receber;
use App\Funcao\CalcularQtd;

$status = "";

$dt_inico = "";
$dt_fim = "";
$soma = 0;
date_default_timezone_set('America/Sao_Paulo');
$hoje = date('Y-m-d H:i:s');
$qtd_res = 0;

if (isset($_GET['id_item'])) {

    $qtd_res = intval($_GET['qtd']);

    $receber = Receber::getID('*', 'receber', $_GET['id_item'], null, null, null);

    if ($receber != false) {
        $dt_inico = $receber->data;
        $dt_fim = $receber->vencimento;
        $soma = intval($receber->qtd) - intval($_GET['qtd']);
    }
}

if (isset($_GET['status'])) {

    $status .= '<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-ban"></i> Atenção!</h5>
    QUANTIDADE MAIOR DO QUE ESPERADO.
</div>';
} else {
    $status .= '';
}


$resultados = '';
$listarEntregador = '';

foreach ($qtd_entregador as $item) {

    $cor = CalcularQtd::getQtdSoma($_GET['id_item'], $item->entregador_id);

    $data = $item->data_ini;
    $vencimento = $item->vencimento;

    $listarEntregador .= '
    
    <div class="col-md-3 col-sm-6 col-12">
            <a href="entregador-boleto.php?entregador_id=' . $item->entregador_id . '&receber_id=' . $item->id . '&qtd=' .  $qtd_res  . ' &data=' . $item->data_ini . '&vencimento=' . $item->vencimento . '">
            <div class="info-box">
              ' . $cor . '

              <div class="info-box-content">
                <span class="info-box-text"style="text-transform:uppercase"> ' . $item->entregador . '</span>
                <span class="info-box-number">Qtd : ' . $item->qtd . '</span>
              </div>
           
            </div>
            </a>
          
          </div>
    
    ';
}

?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">

                <?= $status ?>

                <div class="card back-black">
                    <div class="card-header">
                        <a href="../receber/receber-list.php">
                            <button accesskey="q" title="ALT+K" type="submit" class="btn btn-warning"
                                data-toggle="modal" data-target="#modal-default"> <i style="font-size: 27px;"
                                    class="fa fa-arrow-circle-left" aria-hidden="true"></i> &nbsp;
                                <span style="font-size: 27px;">E-COMMERCE</span></button>
                        </a>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        <a href="../producao/producao-list.php">
                            <button accesskey="p" title="ALT+P" type="submit" class="btn btn-danger" data-toggle="modal"
                                data-target="#modal-default"> <i style="font-size: 27px;" class="fa fa-truck"
                                    aria-hidden="true"></i> &nbsp;
                                <span style="font-size: 27px;">PRODUÇÃO</span></button>
                        </a>
                    </div>
                    <!-- /.card-header -->

                    <form action="./receber-item-cadastro.php" method="POST">
                        <input type="hidden" value="<?= $id ?>" name="receber_id">
                        <input type="hidden" value="<?= $data ?>" name="data_inicio">
                        <input type="hidden" value="<?= $vencimento ?>" name="vencimento">

                        <div class="modal-body">

                            <div class="row">
                                <div class="col-6">

                                    <div class="form-group">
                                        <label>Data da coleta</label>
                                        <input value="<?=
                                                        $hoje  ?>" type="datetime-local" class="form-control"
                                            name="data_inicio" required>
                                    </div>
                                </div>
                                <div class="col-6">

                                    <div class="form-group">
                                        <label>Prazo de Entrega</label>
                                        <input value="<?=
                                                        $dt_fim ?>" type="datetime-local" class="form-control"
                                            name="data_fim" required>
                                    </div>
                                </div>


                            </div>

                            <div class="row">

                                <div class="col-6">
                                    <label>Entregadores</label>
                                    <select class="form-control select2" style="width: 100%;" name="entregador_id"
                                        id="entregadores" onchange="rotas(this.value);" required>
                                        <option value=""> Selecione um entregador </option>
                                        <?php

                                        foreach ($entregadores as $item) {
                                            echo '<option style="text-transform: uppercase;" value="' . $item->id . '">' . $item->apelido . '</option>';
                                        }
                                        ?>

                                    </select>

                                </div>

                                <div class="col-6">
                                    <label>Rotas</label>
                                    <select class="form-control" name="rotas_id" id="rota" required></select>

                                </div>

                            </div>
                            <br>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">

                                        <label>Status</label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-control">
                                                    <input type="radio" name="setores" value="1" checked> E-commerce
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-6">
                                    <div class="form-group">

                                        <label>Tipo de Volume</label>
                                        <div>

                                            <div class="form-check form-check-inline">
                                                <label class="form-control">
                                                    <input type="radio" name="servicos" value="7" checked> Caixa
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>


                            <br>

                            <div class="row">

                                <div class="col-6">
                                    <label class="vermelho">Quantidade para entrega</label>
                                    <input type="text" name="qtd" value="" class="form-control menor" required>

                                </div>
                                <div class="col-6">
                                    <label style="color:#fff ;">ok</label>
                                    <button name="enviar" class="form-control btn btn-dark float-right" type="submit"
                                        <?= $block ?>> ADICIONAR </button>

                                </div>

                            </div>

                        </div>


                    </form>

                </div>

            </div>

            <div class="col-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">LISTA DE ENTREGADORES PARA ESSA REMESSA...</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="display: block;">

                        <div class="row">

                            <?= $listarEntregador ?>

                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

    </div>

</section>