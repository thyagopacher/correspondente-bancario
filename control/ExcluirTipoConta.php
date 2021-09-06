<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/TipoConta.php";
    
    $conexao = new Conexao();
    $tipoconta    = new TipoConta($conexao);
    if(!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == ""){
        $tipoconta->codempresa = $_SESSION['codempresa'];
    }else{
        $tipoconta->codempresa = $_POST["codempresa"];
    }   
    
    $msg_retorno = "";
    $sit_retorno = true; 
    $tipoconta2 = $conexao->comandoArray("select nome from conta where codconta = '{$_POST["codtipo"]}' and codempresa = '{$tipoconta->codempresa}'");
    $res        = $tipoconta->excluir($_POST["codtipo"]);
    
    if($res === FALSE){
        $msg_retorno =  "Erro ao excluir tipo! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Tipo conta excluido com sucesso!";
        include("../model/Log.php");
        $log = new Log($conexao);
        $log->codpessoa = $_SESSION['codpessoa'];
        
        $log->acao = "excluir";
//        $log->codpagina = 16;
        $log->observacao = "Excluido tipo conta de nome: {$tipoconta2["nome"]}";
        $log->inserir($log);         
    }    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));