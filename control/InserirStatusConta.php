<?php
    
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/StatusConta.php";

    $conexao   = new Conexao();
    $status    = new StatusConta($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true; 
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $status->$key = $value;
    }    
    $status2 = $conexao->comandoArray("select codstatus from statusconta where nome = '{$status->nome}'");
    if(isset($status2) && isset($status2["nome"])){
        $msg_retorno =  "Já tem um status com esse nome!";
        $sit_retorno = false;        
    }else{
        if(!isset($status->nome) || $status->nome == NULL || $status->nome == ""){
            $msg_retorno =  "Não pode inserir sem nome !";
            $sit_retorno = false;
        } else{   
            $res = $status->inserir($status);

            if($res === FALSE){
                $msg_retorno =  "Erro ao inserir status! Causado por:". mysqli_error($conexao->conexao);
                $sit_retorno = false;
            }else{
                $msg_retorno =  "Status inserido com sucesso!";
            }
        }    
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));