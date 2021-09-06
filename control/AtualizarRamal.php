<?php
    
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Ramal.php";
    
    $conexao   = new Conexao();
    $ramal      = new Ramal($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $ramal->$key = $value;
        $dadosAtualizacao .= "$key = $value;";
    }    
    
    $numero = $ramal->soNumero($ramal->telefone);
    if(strlen($numero) == 10){
        $ramal->telefone = $ramal->mask($numero);
    }else{
        $ramal->telefone = $ramal->mask($numero, "(##)#####-####");
    }    
    
    $ramal->codempresa = $_SESSION['codempresa'];
    $res = $ramal->atualizar();
    
    if($res === FALSE){
        $msg_retorno =  "Erro ao atualizar ramal! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Ramal atualizado com sucesso!";
    }
    
    include "../model/Log.php";
    $log             = new Log($conexao);
    $log->acao       = "atualizar";
    
    $log->codpagina  = 39;
    
    $log->data       = date('Y-m-d');
    $log->hora       = date('H:i:s');
    $log->observacao = $msg_retorno . " - ". $dadosAtualizacao;
    $log->inserir();
    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));