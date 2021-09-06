<?php

session_start();
include "../model/Conexao.php";
$conexao = new Conexao();

/**pegando o valor total da meta do funcionário*/
$sql = "select valor from metafuncionario where codempresa = '{$_SESSION['codempresa']}' and codfuncionario = '{$_SESSION['codpessoa']}'";
$metaFuncionario = $conexao->comandoArray($sql);
$diasUteis = 0;

if (isset($metaFuncionario) && $metaFuncionario["valor"] != NULL && $metaFuncionario["valor"] != "") {

    /**somatório valor total vendido*/
    $baixaTotal = $conexao->comando("select sum(valor) as valor from baixa 
        where codempresa = '{$_SESSION['codempresa']}' 
        and dtcadastro >= '" . date("Y-m-") . "01'    
        and dtcadastro <= '" . date("Y-m-") . "30'    
        and codfuncionario = '{$_SESSION['codpessoa']}'");
        
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
    for ($i = 1; $i <= $ultimo_dia; $i++) {
        if($i < 10){
            $dia_mes2 = "0". $i;
        }else{
            $dia_mes2 = $i;
        }
        $data_selec    = $dia_mes . $dia_mes2;
        $dia_feriado   = $conexao->comando("select * from dia where data = '{$data_selec}' and codempresa = '{$_SESSION['codempresa']}'");
        if(isset($dia_feriado) && $dia_feriado["data"] != NULL && $dia_feriado["data"] != ""){
            continue;
        }
        $dia_semana    = date("D", $data_selec);
        $horarioFilial = $conexao->comandoArray("select * from horariofilial where codempresa = '{$_SESSION['codempresa']}' and dia = '{$semana[$dia_semana]}'");
        if(isset($horarioFilial["codhorario"]) && $horarioFilial["codhorario"] != NULL && $horarioFilial["codhorario"] != ""){
            $diasUteis++;
        }
    }
    $resultadoFinal = ($metaFuncionario["valor"] - $baixaTotal["valor"]) / $diasUteis;
    die(json_encode(array('meta' => $resultadoFinal, 'situacao' => false)));
} else {
    die(json_encode(array('mensagem' => "Funcionário sem meta cadastrada", 'situacao' => false)));
}
    
    
    