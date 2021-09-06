<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Conteudo.php";
    
    $conexao      = new Conexao();
    $conteudo  = new Conteudo($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    if(!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == ""){
        $conteudo->codempresa = $_SESSION['codempresa'];
    }else{
        $conteudo->codempresa = $_POST["codempresa"];
    }    
    $conteudo2 = $conexao->comandoArray("select DATE_FORMAT(conteudo.dtcadastro, '%d/%m/%Y') as dtcadastro2, pessoa.nome as pessoa 
    from conteudo
    inner join pessoa on pessoa.codpessoa = conteudo.codpessoa
    where codconteudo = '{$_POST["codconteudo"]}'");
    $descricaoConteudo = "Conteúdo excluida com dtcadastro: {$conteudo2["dtcadastro"]} da pessoa {$pessoa["nome"]}";
    $res = $conteudo->excluir($_POST["codconteudo"]);
    
    if($res === FALSE){
        $msg_retorno =  "Erro ao excluir conteúdo! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Conteúdo excluido com sucesso!";
        include("../model/Log.php");
        $log = new Log($conexao);
        $log->codpessoa = $_SESSION['codpessoa'];
        
        $log->acao = "excluir";
        $log->codpagina = 27;
        $log->observacao = $descricaoConteudo;
        $log->inserir($log);               
    }    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));