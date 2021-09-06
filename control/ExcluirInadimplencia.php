<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Inadimplencia.php";
    
    $conexao      = new Conexao();
    $inadimplencia  = new Inadimplencia($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    if(!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == ""){
        $inadimplencia->codempresa = $_SESSION['codempresa'];
    }else{
        $inadimplencia->codempresa = $_POST["codempresa"];
    }    
    $inadimplencia2 = $conexao->comandoArray("select DATE_FORMAT(inadimplencia.dtcadastro, '%d/%m/%Y') as dtcadastro2, pessoa.nome as funcionario 
    from inadimplencia
    inner join pessoa on pessoa.codpessoa = inadimplencia.codfuncionario and pessoa.codempresa = inadimplencia.codempresa
    where inadimplencia.codinadimplencia = '{$_POST["codinadimplencia"]}' and inadimplencia.codempresa = '{$_SESSION['codempresa']}'");
    $descricaoInadimplencia = "Inadimplência excluida com dtcadastro: {$inadimplencia2["dtcadastro"]} do funcionario {$pessoa["nome"]}";
    $res = $inadimplencia->excluir($_POST["codinadimplencia"]);
    
    if($res === FALSE){
        $msg_retorno =  "Erro ao excluir Inadimplência! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Inadimplência excluido com sucesso!";
        include("../model/Log.php");
        $log = new Log($conexao);
        $log->codpessoa = $_SESSION['codpessoa'];
        
        $log->acao = "excluir";
        $log->codpagina = 27;
        $log->observacao = $descricaoInadimplencia;
        $log->inserir($log);               
    }    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));