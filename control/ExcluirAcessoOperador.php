<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    
    include "../model/Conexao.php";
    include "../model/AcessoOperador.php";
    
    $conexao = new Conexao();
    $acesso  = new AcessoOperador($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true; 
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $acesso->$key = $value;
    }  
    $acesso->codempresa     = $_SESSION['codempresa'];    
    $res = $acesso->excluir($acesso->codacesso);   
    if($res !== FALSE){
        $msg_retorno = "Acesso operador excluido com sucesso!!!";
    }else{
        $msg_retorno = "Erro ao excluir acesso de operador causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));