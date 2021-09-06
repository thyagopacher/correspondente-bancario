<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Acesso.php";
    $conexao = new Conexao();
    $acesso  = new Acesso($conexao);
    
    if(!isset($_POST["codempresa"]) && $_POST["codempresa"] != NULL && $_POST["codempresa"] != ""){
        $acesso->codempresa = $_SESSION['codempresa'];
    }else{
        $acesso->codempresa = $_POST["codempresa"];
    }
    
    
    $msg_retorno = "";
    $sit_retorno = true;     
    $acesso2     = $conexao->comandoArray("select DATE_FORMAT(data, '%d/%m/%Y') as data2, enderecoip from acesso where codempresa = '{$_SESSION['codempresa']}'");
    $descricaoAcesso = "Acesso excluido endereço IP: {$acesso2["enderecoip"]} que foi na data: {$acesso2["data2"]} com quantidade: {$acesso2["quantidade"]}";
    $res = $acesso->excluir($_POST["codacesso"]);
    
    if($res === FALSE){
        $msg_retorno = "Erro ao excluir acesso! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno = "Acesso excluido com sucesso!";
        include("../model/Log.php");
        $log = new Log($conexao);
        $log->codpessoa = $_SESSION['codpessoa'];
        
        $log->acao = "excluir";
        $log->codpagina = 22;
        $log->observacao = $descricaoAcesso;
        $log->inserir($log);        
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));