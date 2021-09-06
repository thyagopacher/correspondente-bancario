<?php

session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
}
include "../model/Conexao.php";
include "../model/Empresa.php";
$conexao = new Conexao();
$empresa = new Empresa($conexao);

$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
foreach ($variables as $key => $value) {
    $empresa->$key = $value;
}

if ($empresa->codplano == '') {
    die(json_encode(array('mensagem' => 'Escolha um plano!!!', 'situacao' => false)));
}

$msg_retorno = "";
$sit_retorno = true;

$planop = $conexao->comandoArray("select nome from plano where codplano = {$empresa->codplano}  ");
$empresap = $conexao->comandoArray("select razao from empresa where codempresa = '{$_SESSION["empresa"]}'");

$empresa->codempresa = $_SESSION['codempresa'];
$res = $empresa->atualizar();

if ($res === FALSE) {
    $msg_retorno = "Erro ao atualizar empresa! Causado por:" . mysqli_error($conexao->conexao);
    mail("thyago.pacher@gmail.com", "Erro sistema de condominios - control/AtualizarEmpresa.php", $msg_retorno);
    $sit_retorno = false;
} else {
    $msg_retorno = "Plano atualizado com sucesso!";
    include("../model/Log.php");
    $log = new Log($conexao);
    $log->codpessoa = $_SESSION['codpessoa'];
    
    $log->acao = "atualizar";
    $log->observacao = "Atualizar da Empresa - {$empresap["razao"]} para o plano:{$planop["nome"]}";
    $log->codpagina = "11";
    $log->inserir($log);
}

echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
