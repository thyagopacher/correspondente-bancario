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
$agenda = new Agenda($conexao);

$msg_retorno = "";
$sit_retorno = true;

$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
foreach ($variables as $key => $value) {
    $agenda->$key = $value;
}
$agenda->codempresa = $_SESSION['codempresa'];
$agenda->data = implode("-", array_reverse(explode("/", $agenda->data)));
$res = $agenda->atualizar();
if ($res !== FALSE) {
    $msg_retorno = "Agenda atualizada com sucesso!!!";
} else {
    $msg_retorno = "Erro ao agendar causado por:" . mysqli_error($conexao->conexao);
    $sit_retorno = false;
}

$log = new Log($conexao);
$log->acao = "atualizar";
$log->observacao = "Atualizado agenda - em " . date('d/m/Y') . " - " . date('H:i');
$log->codpagina = "0";
$log->inserir();
echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
