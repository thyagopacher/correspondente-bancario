<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Log.php";
    
    $conexao = new Conexao();  
    $log   = new Log($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;
    if(!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == ""){
        
    }else{
        $log->codempresa = $_POST["codempresa"];
    }
    
    $log2 = $conexao->comandoArray("select DATE_FORMAT(log.data, '%d/%m/%Y') as data2, hora, log.codempresa, empresa.razao
    from log
    inner join empresa on empresa.codempresa = log.codempresa
    where log.codempresa = '{$log->codempresa}' and log.codlog = '{$_POST["codigo"]}'");
    $res = $log->excluir($_POST["codigo"]);

    if($res === FALSE){
        $msg_retorno =  "Erro ao excluir log! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Log excluido com sucesso!";
        include("../model/Log.php");
        $log = new Log($conexao);
        $log->codpessoa = $_SESSION['codpessoa'];
        
        $log->acao = "excluir";
        $log->codpagina = 28;
        $log->observacao = "Log de sistema excluido da data {$log2["data2"]} na hora: {$log2["hora"]} e na empresa: {$log2["codempresa"]} e de nome: {$log2["razao"]}";
        $log->inserir($log);         
    }    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));