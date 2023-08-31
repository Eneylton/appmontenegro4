<?php

namespace App\Funcao;

use App\Entidy\Boleto;
use App\Entidy\EntregadorQtd;

$qtd = 0;

class CalcularQtd
{
    public static function calcularQtd($entregador, $receber, $status)
    {

        switch ($status) {
            case '2':
                $qtd_recebeda = EntregadorQtd::getIDList('*', 'entregador_qtd', 'receber_id=' . $receber . ' AND entregadores_id=' . $entregador . '');
                return $qtd = $qtd_recebeda->qtd;
            case '3':
                $qtd_recebeda = EntregadorQtd::getIDList('*', 'entregador_qtd', 'receber_id=' . $receber . ' AND entregadores_id=' . $entregador . '');
                return $qtd = $qtd_recebeda->qtd;
            case '4':
                $qtd_recebeda = EntregadorQtd::getIDList('*', 'entregador_qtd', 'receber_id=' . $receber . ' AND entregadores_id=' . $entregador . '');
                return $qtd = $qtd_recebeda->qtd;

            default:

                $qtd_recebeda = EntregadorQtd::getIDList('*', 'entregador_qtd', 'receber_id=' . $receber . ' AND entregadores_id=' . $entregador . '');

                if ($qtd_recebeda == false) {
                    $calculo = false;
                    return $calculo;
                } else {
                    $qtd = $qtd_recebeda->qtd;

                    $calculo = $qtd - 1;

                    $qtd_recebeda->qtd = $calculo;

                    $qtd_recebeda->atualizar();

                    $calculo = true;

                    return $calculo;
                }
        }
    }


    public static function getQtdSoma($receber, $entregador)
    {
        $boleto = Boleto::getBoletosID('count(*) as total', 'boletos', $receber . ' AND entregadores_id=' . $entregador, null, null, null);

        $soma_bol =  $boleto->total;

        $qtd_total = EntregadorQtd::getRecebID('qtd as total2', 'entregador_qtd', $receber . ' AND entregadores_id=' . $entregador, null, null, null);

        $soma_rec = $qtd_total->total2;

        $soma =  intval($soma_bol) - intval($soma_rec);

        if ($soma != 0) {

            return  '<span class="info-box-icon bg-danger"><i class="fa fa-motorcycle" aria-hidden="true"></i></span>';
        } else {
            return '<span class="info-box-icon bg-success"><i class="fa fa-check"></i></span>';
        }
    }
    
    public static function getMudarEntregador($receber, $entreNovo)
    {
    
        $qtdEntrega = EntregadorQtd ::getIDReceber('*','entregador_qtd',$receber,null,null,null);

        $entregadorId = $qtdEntrega->entregadores_id;

        if($entregadorId != $entreNovo){

            return true;
        }else{

            return false;
        }
    }
}