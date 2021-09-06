<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/StatusPessoa.php";
    
    $conexao   = new Conexao();
    $status      = new StatusPessoa($conexao);
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $status->$key = $value;
    }    
    
    $msg_retorno = "";
    $sit_retorno = true;   

    $res = $status->atualizar($status);
    
    if($res === FALSE){
        $msg_retorno =  "Erro ao atualizar status! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Status Pessoa atualizado com sucesso!";
    }    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));