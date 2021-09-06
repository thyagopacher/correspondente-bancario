<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Conta.php";
    include "../visao/Utilitario.php";
    
    $conexao     = new Conexao();
    $emailpadrao   = new EmailPadrao($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true; 
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $emailpadrao->$key = $value;
    }  

    $emailpadrao->codempresa =  $_SESSION['codempresa'];
    $res = $emailpadrao->excluir($emailpadrao->codemailpadrao);

    if($res === FALSE){
        $msg_retorno =  "Erro ao excluir email padrão! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Email padrão excluido com sucesso!";
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));