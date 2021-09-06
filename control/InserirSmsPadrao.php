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

    if(!isset($smspadrao->texto) || $smspadrao->texto == NULL || $smspadrao->texto == ""){
        $msg_retorno =  "Não pode inserir sem texto !";
        $sit_retorno = false;
    }else{
        $smspadrao->codempresa =  $_SESSION['codempresa'];
        $smspadrao->codfuncionario  = $_SESSION['codpessoa'];
        $res = $smspadrao->inserir($smspadrao);

        if($res === FALSE){
            $msg_retorno =  "Erro ao inserir sms padrão! Causado por:". mysqli_error($conexao->conexao);
            $sit_retorno = false;
        }else{
            $msg_retorno =  "Sms padrão inserido com sucesso!";
        }
    }    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));