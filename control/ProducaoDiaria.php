<?php

header("Content-type: application/json");
session_start();
include "../model/Conexao.php";
$conexao = new Conexao();

//igual mês atual - qtd_meses meses
$dia_inicial = (int) date("d") - $qtd_dias;
$mes_inicial = date("m");
$ano_inicial = date("Y");
if ($dia_inicial < 0) {//colocado como soma pois o número mês inicial caso caia aqui vira negativo
    $dia_inicial = 30 + $dia_inicial;
    $mes_inicial = $mes_inicial - 1;
}
$dados = array();
if($_SESSION["codnivel"] != 19){
    $and = " and codfuncionario = '{$_SESSION["codpessoa"]}'";
}
$menosDias = date('Y-m-d', strtotime('-10 days'));
//pegando a produção do diaria
$sql = "select IFNULL(sum(valor),0) as valor, dtcadastro 
from baixa where dtcadastro >= '{$menosDias} 00:00:00' 
and dtcadastro <= '".date("Y-m-d H:i:s")."' 
$and 
and codempresa = '{$_SESSION["codempresa"]}'
group by DATE_FORMAT(dtcadastro, '%Y-%m-%d')";
// echo "<pre>{$sql}</pre>";
$resproducao = $conexao->comando($sql);
$qtdproducao = $conexao->qtdResultado($resproducao);
$dados = array();
if($qtdproducao > 0){
    $i = 0;
    $dados[0] = array("Dia", "Produção", "Meta");
    while ($producao = $conexao->resultadoArray($resproducao)) {
        
        $data_cadastro = explode("-", $producao["dtcadastro"]);
        $dia_separador = explode(" ", $data_cadastro[2]);
        $dia           = $dia_separador[0].'/' . $mes_inicial;
        
        if ($i == $qtdproducao - 1) {
            $dados[$i + 1] = array($dia, (double)number_format($producao["valor"], 2, '.', ''), (double)calculaMetaFuncionario($dia_separador[0]));
        } else {
            $dados[$i + 1] = array($dia, (double)number_format($producao["valor"], 2, '.', ''), (double)calculaMetaFuncionario($dia_separador[0]));
        }
        $i++;
    }
    echo json_encode($dados);
}



function calculaMetaFuncionario($baixaAte = 30) {
    global $conexao;
    if($_SESSION["codnivel"] != 19){
        $and = " and codfuncionario = '{$_SESSION["codpessoa"]}'";
    }    
    /*     * pegando o valor total da meta do funcionário */
    $sql = "select sum(valor) as valor from metafuncionario where codempresa = '{$_SESSION['codempresa']}' 
    and dtcadastro >= '" . date("Y-m-") . "01 00:00:00'    
    and dtcadastro <= '" . date("Y-m-") . "30 23:59:59'     $and       
    order by codmeta desc";
//    echo "<pre>{$sql}</pre>";
    $metaFuncionario = $conexao->comandoArray($sql);
    $diasUteis = 0;

    if (isset($metaFuncionario) && $metaFuncionario["valor"] != NULL && $metaFuncionario["valor"] != "") {

        /*         * somatório valor total vendido */
        $baixaTotal = $conexao->comandoArray("select sum(valor) as valor from baixa 
        where codempresa = '{$_SESSION['codempresa']}' {$and}
        and dtcadastro >= '" . date("Y-m-") . "01'    
        and dtcadastro <= '" . date("Y-m-") . $baixaAte."'");

        $ultimo_dia = date("t", mktime(0, 0, 0, date("m"), '01', date("Y")));
        $dia_mes = date("Y-m-");
        $semana = array(
            'Sun' => 'domingo',
            'Mon' => 'segunda',
            'Tue' => 'terca',
            'Wed' => 'quarta',
            'Thu' => 'quinta',
            'Fri' => 'sexta',
            'Sat' => 'sabado'
        );
        for ($i = date("d"); $i <= $ultimo_dia; $i++) {
            if ($i < 10) {
                $dia_mes2 = "0" . $i;
            } else {
                $dia_mes2 = $i;
            }
            $data_selec = $dia_mes . $dia_mes2;
            $sql = "select * from dia where data = '{$data_selec}' and codempresa = '{$_SESSION['codempresa']}'";
            $dia_feriado = $conexao->comandoArray($sql);
            if (isset($dia_feriado) && $dia_feriado["data"] != NULL && $dia_feriado["data"] != "") {
                continue; //tira os feriados
            }

            $dia_semana = date("D", strtotime($data_selec));
//            $sql = "select * from horariofilial where codempresa = '{$_SESSION['codempresa']}' and dia = '{$semana[$dia_semana]}'";
//            $horarioFilial = $conexao->comandoArray($sql);
//            if (isset($horarioFilial["codhorario"]) && $horarioFilial["codhorario"] != NULL && $horarioFilial["codhorario"] != "") {
//                $diasUteis++;
//            }
            if($semana[$dia_semana] == "segunda" || $semana[$dia_semana] == "terca" || $semana[$dia_semana] == "quarta" || $semana[$dia_semana] == "quinta" || $semana[$dia_semana] == "sexta"){
                $diasUteis++;
            }
        }

        if ($diasUteis == 0) {
            $resultadoFinal = 0;
        } else {
            $resultadoFinal = ($metaFuncionario["valor"] - $baixaTotal["valor"]) / $diasUteis;
        }
    }

    return number_format($resultadoFinal, 2, '.', '');
}

