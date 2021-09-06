<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Inadimplencia.php";
    $conexao = new Conexao();
    $inadimplencia = new Inadimplencia($conexao);
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $inadimplencia->$key = $value;
    }    
    
    $msg_retorno = "";
    $sit_retorno = true; 
    $inadimplencia->codfuncionario = $_SESSION['codpessoa'];
    $inadimplencia->codempresa = $_SESSION['codempresa'];
    $res = $inadimplencia->inserir($inadimplencia);

    if($res === FALSE){
        $msg_retorno =  "Erro ao inserir inadimplência! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Inadimplência inserido com sucesso!";             
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));