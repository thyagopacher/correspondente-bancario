<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/SmsPadrao.php";
    include "../visao/Utilitario.php";
    
    $conexao     = new Conexao();
    $smspadrao   = new SmsPadrao($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true; 
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $smspadrao->$key = $value;
    }  

    $smspadrao->codempresa =  $_SESSION['codempresa'];
    $res = $smspadrao->excluir($smspadrao->codsmspadrao);

    if($res === FALSE){
        $msg_retorno =  "Erro ao excluir sms padrão! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Sms padrão excluido com sucesso!";
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));