<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Pagina.php";
    
    $conexao = new Conexao();
    $pagina = new Pagina($conexao);
    if(!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == ""){
        $pagina->codempresa = $_SESSION['codempresa'];
    }else{
        $pagina->codempresa = $_POST["codempresa"];
    }   
    
    $msg_retorno = "";
    $sit_retorno = true; 
    $conexao->comando("delete from nivelpagina where codpagina = '{$_POST["codpagina"]}'");
    $pagina2 = $conexao->comandoArray("select nome from pagina where codpagina = '{$_POST["codpagina"]}'");
    
    $qtd_nivel = $conexao->comandoArray("select count(1) as qtd from nivelpagina where codpagina = '{$_POST["codpagina"]}'");
    if($qtd_nivel["qtd"] > 0){
        $msg_retorno =  "Não pode excluir página pois está vinculado a um nível de acesso no sistema!";
        $sit_retorno = false;        
    }else{
        $res = $pagina->excluir($_POST["codpagina"]);
        if($res === FALSE){
            $msg_retorno =  "Erro ao excluir pagina! Causado por:". mysqli_error($conexao->conexao);
            $sit_retorno = false;
        }else{
            $msg_retorno =  "Pagina excluida com sucesso!";
            include("../model/Log.php");
            $log = new Log($conexao);
            $log->codpessoa = $_SESSION['codpessoa'];
            
            $log->acao = "excluir";
    //        $log->codpagina = 12;
            $log->observacao = "Excluido funcionalidade do sistema de código {$_POST["codpagina"]} e nome: {$pagina2["nome"]}";
            $log->inserir($log);                     
        }    
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));