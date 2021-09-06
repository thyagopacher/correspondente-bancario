<?php

session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   

include "../model/Conexao.php";
include "../model/StatusPessoa.php";

$conexao = new Conexao();
$status = new StatusPessoa($conexao);
if (!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == "") {
    $status->codempresa = $_SESSION['codempresa'];
} else {
    $status->codempresa = $_POST["codempresa"];
}

$msg_retorno = "";
$sit_retorno = true;

$qtdpessoa = $conexao->comandoArray("select count(1) as qtd from pessoa where codstatus = '{$_POST["codstatus"]}'");
if ($qtdpessoa["qtd"] > 0) {
    $msg_retorno = "Não pode excluir esse status de pessoa pois é usado por {$qtdpessoa["qtd"]} pessoas!";
    $sit_retorno = false;
} else {

    $status2 = $conexao->comandoArray("select nome from statuspessoa where codstatus = '{$_POST["codstatus"]}'");
    $status->codstatus = $_POST["codstatus"];
    $res = $status->excluir();

    if ($res === FALSE) {
        $msg_retorno = "Erro ao excluir status! Causado por:" . mysqli_error($conexao->conexao);
        $sit_retorno = false;
    } else {
        $msg_retorno = "Status Pessoa excluido com sucesso!";
        include("../model/Log.php");
        $log = new Log($conexao);
        $log->codpessoa = $_SESSION['codpessoa'];
        
        $log->acao = "excluir";
//        $log->codpagina = 16;
        $log->observacao = "Excluido status pessoa de nome: {$status2["nome"]}";
        $log->inserir($log);
    }
}
echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
