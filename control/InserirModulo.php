<?php
session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
function __autoload($class_name) {
    if(file_exists("../model/".$class_name . '.php')){
        include "../model/".$class_name . '.php';
    }elseif(file_exists("../visao/".$class_name . '.php')){
        include "../visao/".$class_name . '.php';
    }elseif(file_exists("./".$class_name . '.php')){
        include "./".$class_name . '.php';
    }
}

$conexao = new Conexao();
$modulo = new Modulo($conexao);

$msg_retorno = "";
$sit_retorno = true;
$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
foreach ($variables as $key => $value) {
    $modulo->$key = $value;
}
$modulo2 = $conexao->comandoArray("select codmodulo from modulo where nome = '{$modulo->nome}'");
if (isset($modulo2) && isset($modulo2["codmodulo"])) {
    $msg_retorno = "Já tem um módulo inserido com esse nome!";
    $sit_retorno = false;
} else {
    if (!isset($modulo->nome) || $modulo->nome == NULL || $modulo->nome == "") {
        $msg_retorno = "Não pode inserir sem nome !";
        $sit_retorno = false;
    } else {
        $res = $modulo->inserir($modulo);

        if ($res === FALSE) {
            $msg_retorno = "Erro ao inserir modulo! Causado por:" . mysqli_error($conexao->conexao);
            $sit_retorno = false;
        } else {
            $msg_retorno = "Modulo inserido com sucesso!";
        }
    }
}
echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
