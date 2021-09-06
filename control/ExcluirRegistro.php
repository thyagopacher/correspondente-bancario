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
    if ($sit_retorno) {
        $res = $registro->excluir($_POST["codregistro"]);
        if ($res === FALSE) {
            $msg_retorno = "Erro ao excluir registro! Causado por:" . mysqli_error($conexao->conexao);
            $sit_retorno = false;
        } 
    }

echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
