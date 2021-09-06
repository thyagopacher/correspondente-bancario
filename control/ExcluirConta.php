<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Conta.php";
    
    $conexao = new Conexao();
    $conta = new Conta($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $conta->$key = $value;
    }    
    if(isset($_POST["codempresa"]) && $_POST["codempresa"] != NULL && $_POST["codempresa"] != ""){
        $conta->codempresa = $_POST["codempresa"];
    }else{
        $conta->codempresa = $_SESSION['codempresa'];
    }    
    $conta2 = $conexao->comandoArray("select nome, valor, movimentacao from conta where codempresa = '{$_SESSION['codempresa']}' and codconta = '{$_POST["codconta"]}'");

    $descricaoConta = $conta2["nome"] ." de valor R$ ". number_format($conta2["valor"], 2, ",", "");
    $res = $conta->excluir($_POST["codconta"]);
    
    if($res === FALSE){
        $msg_retorno =  "Erro ao excluir conta! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Conta excluida com sucesso!";
        include("../model/Log.php");
        $log = new Log($conexao);
        $log->codpessoa = $_SESSION['codpessoa'];
        
        $log->acao = "excluir";
        $log->observacao = $descricaoConta;
        $log->inserir($log);           
    }    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));