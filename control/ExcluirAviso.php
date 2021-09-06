<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Aviso.php";
    
    $conexao      = new Conexao();
    $aviso  = new Aviso($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    if(!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == ""){
        $aviso->codempresa = $_SESSION['codempresa'];
    }else{
        $aviso->codempresa = $_POST["codempresa"];
    }    
    $aviso2 = $conexao->comandoArray("select DATE_FORMAT(aviso.dtcadastro, '%d/%m/%Y') as dtcadastro2, pessoa.nome as funcionario 
    from aviso
    inner join pessoa on pessoa.codpessoa = aviso.codfuncionario and pessoa.codempresa = aviso.codempresa
    where aviso.codaviso = '{$_POST["codaviso"]}' and aviso.codempresa = '{$_SESSION['codempresa']}'");
    $descricaoAviso = "Aviso excluida com dtcadastro: {$aviso2["dtcadastro"]} do funcionario {$pessoa["nome"]}";
    $res = $aviso->excluir($_POST["codaviso"]);
    
    if($res === FALSE){
        $msg_retorno =  "Erro ao excluir aviso! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Aviso excluido com sucesso!";
        include("../model/Log.php");
        $log = new Log($conexao);
        $log->codpessoa = $_SESSION['codpessoa'];
        
        $log->acao = "excluir";
        $log->codpagina = 27;
        $log->observacao = $descricaoAviso;
        $log->inserir($log);               
    }    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));