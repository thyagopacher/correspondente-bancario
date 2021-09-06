<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/ArquivoConta.php";
    
    $conexao = new Conexao();
    $arquivo = new ArquivoConta($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $arquivo->$key = $value;
    }    
    $arquivo->codempresa = $_SESSION['codempresa'];   

    $res = $arquivo->excluir($_POST["codarquivo"]);
    
    if($res === FALSE){
        $msg_retorno =  "Erro ao excluir arquivo conta! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Arquivo Conta excluido com sucesso!";
        include("../model/Log.php");
        $log = new Log($conexao);
        $log->codpessoa = $_SESSION['codpessoa'];
        
        $log->acao = "excluir";
        $log->observacao = "Arquivo de conta excluido";
        $log->inserir();           
    }    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));