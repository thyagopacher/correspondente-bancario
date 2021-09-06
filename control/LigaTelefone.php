<?php

session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
}


function __autoload($class_name) {
    if (file_exists("../model/" . $class_name . '.php')) {
        include "../model/" . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}

$conexao = new Conexao();
$telefone = new Telefone($conexao);
if (!isset($_POST["telefone"]) || $_POST["telefone"] == NULL || $_POST["telefone"] == "") {
    die(json_encode(array('mensagem' => "Não veio telefone para a classe!!!", 'situacao' => false)));
}
$sql = 'select usuarioMultiBR, senhaMultiBR, keyMultiBR from configuracao where codempresa = ' . $_SESSION["codempresa"];
$configuracao = $conexao->comandoArray($sql);
$telefone->url = str_replace('USERNAME', $configuracao["usuarioMultiBR"], $telefone->url);
$telefone->url = str_replace('PASSWORD', $configuracao["senhaMultiBR"], $telefone->url);
$telefone->url = str_replace('[key]', $configuracao["keyMultiBR"], $telefone->url);
$retorno = $telefone->ligaTelefone($_POST["telefone"]);

if ($retorno->status == 1) {
    die(json_encode(array('mensagem' => 'Aguarde<br>Chamada em Andamento', 'situacao' => true)));
} elseif ($retorno->status == 2) {
    die(json_encode(array('mensagem' => 'Não conseguiu realizar ligação!!!', 'situacao' => false)));
}
