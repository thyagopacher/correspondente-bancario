<?php
    session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }       
    include "../model/Conexao.php";
    include "../model/Modulo.php";
    
    $conexao = new Conexao();
    $modulo = new Modulo($conexao);
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $modulo->$key = $value;
    }  
    
    $msg_retorno = "";
    $sit_retorno = true;        
    $res = $modulo->atualizar($modulo);

    if($res === FALSE){
        $msg_retorno = "Erro ao atualizar modulo! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno = "Modulo atualizado com sucesso!";
    }
    include "../model/Log.php";
    $log = new Log($conexao);
    
    
    $log->acao       = "procurar";
    $log->observacao = "Atualizado módulo {$modulo->nome} - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();       
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));