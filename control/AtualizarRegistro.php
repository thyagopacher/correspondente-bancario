<?php
session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   

include "../model/Conexao.php";
include "../model/Registro.php";

$conexao = new Conexao();
$registro = new Registro($conexao);

$msg_retorno = "";
$sit_retorno = true;

$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_REQUEST;
foreach ($variables as $key => $value) {
    $registro->$key = $value;
}
$sql = "delete from procedimentoregistro where codregistro = '{$_POST["codregistro"]}'";
$res = $conexao->comando($sql);
if($res == FALSE){
    die(json_encode(array('mensagem' => "Erro ao atualizar registro de procedimentos!", 'situacao' => false)));
}
if(isset($_POST["procedimento"]) && $_POST["procedimento"] != NULL){
    foreach ($_POST["procedimento"] as $key => $value) {
        $sql = "INSERT INTO `procedimentoregistro`(`codregistro`, `codigo`, `dtcadastro`) VALUES ('{$_POST["codregistro"]}', '{$value}', '".date("Y-m-d H:i:s")."')";
        $res = $conexao->comando($sql);
        if($res == FALSE){
            $msg_retorno = "Erro ao inserir demais procedimentos para o registro de cirurgia!";
            break;
        }
    }
}
if (!isset($registro->paciente) || $registro->paciente == NULL || $registro->paciente == "") {
    $msg_retorno = ("Não pode atualizar registro sem paciente!");
    $sit_retorno = false;
} else {
    if ($sit_retorno) {
        $registro->codregistro = $_POST["codregistro"];
        $res = $registro->atualizar($registro);
        
        if ($res === FALSE) {
            $msg_retorno = "Erro ao atualizar registro! Causado por:" . mysqli_error($conexao->conexao);
            $sit_retorno = false;
        } 
    }
}

include "../model/Log.php";
$log = new Log($conexao);


$log->acao       = "procurar";
$log->observacao = "Atualizado registro: {$registro->valor} - em ". date('d/m/Y'). " - ". date('H:i');
$log->codpagina  = "0";

$log->hora = date('H:i:s');
$log->inserir();   

echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
