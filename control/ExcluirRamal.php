<?php
    
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Ramal.php";
    
    $conexao = new Conexao();
    $ramal    = new Ramal($conexao);
    if(!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == ""){
        $ramal->codempresa = $_SESSION['codempresa'];
    }else{
        $ramal->codempresa = $_POST["codempresa"];
    }    
    
    $msg_retorno = "";
    $sit_retorno = true; 
    
    if($ramal->externo == "s" && $_SESSION["codnivel"] != 1){
        die(json_encode(array('mensagem' => "Somente a equipe GestCCon pode excluir telefones externos!!!", 'situacao' => false)));
    }
    
    $ramal2 = $conexao->comandoArray("select ramal, local, telefone, DATE_FORMAT(dtcadastro, '%d/%m/%Y') as data2 
    from ramal where codramal = '{$ramal->codramal}' and codempresa = '{$ramal->codempresa}'");
    
    $res = $ramal->excluir($_POST["codramal"]);
    
    if($res === FALSE){
        $msg_retorno =  "Erro ao excluir ramal! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Ramal excluido com sucesso!";
        include("../model/Log.php");
        $log = new Log($conexao);
        $log->codpessoa = $_SESSION['codpessoa'];
        
        $log->acao = "excluir";
        $log->observacao = "Ramal excluido no sistema -ramal: {$ramal2["ramal"]} no telefone {$ramal2["telefone"]} e foi cadastrado na data: {$ramal2["data2"]}";
//        $log->codpagina = 4;
        $log->inserir($log);                 
    }    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));