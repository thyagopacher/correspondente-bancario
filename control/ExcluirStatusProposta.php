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
$status = new StatusProposta($conexao);
if (!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == "") {
    $status->codempresa = $_SESSION['codempresa'];
} else {
    $status->codempresa = $_POST["codempresa"];
}

$msg_retorno = "";
$sit_retorno = true;

$qtdproposta = $conexao->comandoArray("select count(1) as qtd from proposta where codstatus = '{$_POST["codstatus"]}'");
if ($qtdproposta["qtd"] > 0) {
    $msg_retorno = "Não pode excluir esse status de proposta pois é usado por {$qtdproposta["qtd"]} propostas!";
    $sit_retorno = false;
} else {
    
    $status2 = $conexao->comandoArray("select nome from statusproposta where codstatus = '{$_POST["codstatus"]}'");
    $status->codstatus = $_POST["codstatus"];
    $res = $status->excluir();

    if ($res === FALSE) {
        $msg_retorno = "Erro ao excluir status! Causado por:" . mysqli_error($conexao->conexao);
        $sit_retorno = false;
    } else {
        $msg_retorno = "Status Proposta foi excluido com sucesso!";
        $log = new Log($conexao);
        $log->codpessoa = $_SESSION['codpessoa'];
        
        $log->acao = "excluir";
//        $log->codpagina = 16;
        $log->observacao = "Excluido status proposta de nome: {$status2["nome"]}";
        $log->inserir($log);
    }
}
echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
