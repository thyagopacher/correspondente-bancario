<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Nivel.php";
    
    $conexao = new Conexao();  
    $nivel   = new Nivel($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true; 
    
    $qtdnivel    = $conexao->comandoArray("select count(1) as qtd from pessoa where codnivel = '{$_POST["codnivel"]}' and status = 'a' and codempresa = '{$_SESSION['codempresa']}'");
    
    
    if($qtdnivel["qtd"] > 0){
        $msg_retorno =  "Não pode excluir esse nível pois o mesmo tem {$qtdnivel["qtd"]} pessoas cadastradas nele!";
        $sit_retorno = false;        
    }else{
        $sql = "select count(1) as qtd from nivelpagina where codnivel = '{$_POST["codnivel"]}'";
        $qtdnivelp = $conexao->comandoArray($sql);
        if(isset($qtdnivelp) && $qtdnivelp["qtd"] > 0){
            die(json_encode(array('mensagem' => "Tem permissões abertas ao nivel, antes retire tudo!", 'situacao' => false)));
        }
        $nivel->codempresa = $_SESSION['codempresa'];
        $res = $nivel->excluir($_POST["codnivel"]);
        
        if($res === FALSE){
            $msg_retorno =  "Erro ao excluir nivel! Causado por:". mysqli_error($conexao->conexao);
            $sit_retorno = false;
        }else{
            $msg_retorno =  "Nivel excluido com sucesso!";
            include("../model/Log.php");
            $log = new Log($conexao);
            $log->codpessoa = $_SESSION['codpessoa'];
            
            $log->acao = "excluir";
    //        $log->codpagina = 12;
            $log->observacao = "Excluido nivel do sistema de código {$_POST["codnivel"]}";
            $log->inserir($log);              
        }    
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));