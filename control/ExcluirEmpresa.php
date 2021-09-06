<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Empresa.php";
    
    $conexao = new Conexao();
    $empresa = new Empresa($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    $qtd = $conexao->comandoArray("select count(1) as qtd from pessoa where codempresa = '{$_POST["codempresa"]}' and status = 'a'");
    if(isset($qtd) && $qtd["qtd"] > 0){
        $msg_retorno = ("Empresa possui usuários cadastrados nela, se realmente quiser excluir, deve apagar antes usuários dela!");
    }
    if(!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == ""){
        $empresa->codempresa = $_SESSION['codempresa'];
    }else{
        $empresa->codempresa = $_POST["codempresa"];
    }    
    $qtdpessoa = $conexao->comandoArray("select count(1) as qtd from pessoa where codempresa = '{$empresa->codempresa}' and status = 'a'");
    if($qtdpessoa["qtd"] > 0){
        $msg_retorno =  "Não pode excluir a empresa pois a mesma possui ({$qtdpessoa["qtd"]}) pessoas cadastradas nela!";
        $sit_retorno = false;
    }else{
        $empresa2  = $conexao->comandoArray("select razao, email, telefone from empresa where codempresa = '{$empresa->codempresa}'");
        $res       = $empresa->excluir($_POST["codempresa"]);
        if($res === FALSE){
            $msg_retorno =  "Erro ao excluir empresa! Causado por:". mysqli_error($conexao->conexao);
            $sit_retorno = false;
        }else{
            $msg_retorno =  "Empresa excluida com sucesso!";
            include("../model/Log.php");
            $log = new Log($conexao);
            $log->codpessoa = $_SESSION['codpessoa'];
            
            $log->acao = "excluir";
            $log->observacao = "A empresa foi excluida {$empresa2["razao"]} de email: {$empresa2["email"]} e telefone: {$empresa2["telefone"]}";
            $log->codpagina = 11;
            $log->inserir($log);          
        }    
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));