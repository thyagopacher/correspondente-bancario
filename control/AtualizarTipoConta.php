<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/TipoConta.php";
    
    $conexao   = new Conexao();
    $tipo      = new TipoConta($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $tipo->$key = $value;
    }    
    $tipo->codempresa = $_SESSION['codempresa'];
    $res = $tipo->atualizar($tipo);
    
    if($res === FALSE){
        $msg_retorno =  "Erro ao atualizar tipo! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Tipo Correspondência atualizado com sucesso!";
    }
    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));