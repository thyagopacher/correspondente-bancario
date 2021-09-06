<?php
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);
//
session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
include("../model/Conexao.php");
include "../model/Log.php";
$conexao = new Conexao();
$indiceSemana = 0;
$msg_retorno = "";
$sit_retorno = true;

$resdel   = $conexao->comando("delete from horariofilial where codempresa = '{$_POST["codfilial"]}'");
if ($resdel === FALSE) {
    die(json_encode(array('mensagem' => "Erro ao atualizar horário de filial - 1", 'situacao' => false)));
} elseif (isset($_POST["funcionamento"])) {
    $empresa = $conexao->comandoArray("select razao from empresa where codempresa = '{$_POST["codfilial"]}'");
    foreach ($_POST["funcionamento"] as $key => $funcionamento) {
        $indiceSemana = trocaDiaSemana($funcionamento);
        $sql = "INSERT INTO `horariofilial`(`codfuncionario`, `codempresa`, `dia`, `horainicial`, `horafinal`) 
                    VALUES ('{$_SESSION['codpessoa']}', '{$_POST["codfilial"]}', '{$funcionamento}', '{$_POST["horasini"][$indiceSemana]}', '{$_POST["horasfin"][$indiceSemana]}')";
        $res = $conexao->comando($sql);
        if ($res === FALSE) {
            $msg_retorno .= "<br>Erro ao inserir horário da filial: " . mysqli_error($conexao->conexao);
            $sit_retorno = false;
            break;
        }else{
            $log = new Log($conexao);
            $log->acao = "inserir";
            
            $log->codpagina = 3;
            $log->codpessoa = $_SESSION['codpessoa'];
            
            $log->hora = date('H:i:s');
            $log->observacao = $msg_retorno." - Dia: {$funcionamento} - Horário inicial: {$_POST["horasini"][$indiceSemana]} - Horário Final: {$_POST["horasfin"][$indiceSemana]} - Filial: {$empresa["razao"]}";
            $log->inserir();   
        }
    }
}
if ($sit_retorno == true) {
    $msg_retorno = "Horário Filial salvo com sucesso";
}
echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));

function trocaDiaSemana($dia) {
    switch ($dia) {
        case "segunda":
            $dia = 0;
            break;
        case "terca":
            $dia = 1;
            break;
        case "quarta":
            $dia = 2;
            break;
        case "quinta":
            $dia = 3;
            break;
        case "sexta":
            $dia = 4;
            break;
        case 'sabado':
            $dia = 5;
            break;
        case "domingo":
            $dia = 6;
            break;
    }
    return $dia;
}
