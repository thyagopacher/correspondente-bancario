<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Conteudo.php";
    $conexao = new Conexao();
    $conteudo = new Conteudo($conexao);
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $conteudo->$key = $value;
    }    
    
    $msg_retorno = "";
    $sit_retorno = true; 
    
    $conteudo->codpessoa = $_SESSION['codpessoa'];
    $conteudo->codempresa = $_SESSION['codempresa'];
    $res = $conteudo->inserir($conteudo);

    if($res === FALSE){
        $msg_retorno =  "Erro ao cadastrar conteúdo! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Conteúdo cadastrado com sucesso!";             
    }  
    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));