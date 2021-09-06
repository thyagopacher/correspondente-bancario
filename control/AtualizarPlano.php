<?php
    session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }       
    include "../model/Conexao.php";
    include "../model/Plano.php";
    
    $conexao = new Conexao();
    $plano   = new Plano($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true; 
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $plano->$key = $value;
    }  
    $res = $plano->atualizar();   
    if($res !== FALSE){
        $msg_retorno = "Plano atualizado com sucesso!!!";
    }else{
        $msg_retorno = "Erro ao atualizar plano causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }
    include "../model/Log.php";
    $log = new Log($conexao);
    
    
    $log->acao       = "procurar";
    $log->observacao = "Atualizado plano - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();          
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));