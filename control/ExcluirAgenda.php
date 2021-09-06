<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Agenda.php";
    
    $conexao = new Conexao();
    $agenda  = new Agenda($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true; 
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $agenda->$key = $value;
    }  
    $agenda->codempresa = $_SESSION['codempresa'];   
    $res = $agenda->excluir($agenda->codagenda);   
    if($res !== FALSE){
        $msg_retorno = "Agenda excluida com sucesso!!!";
    }else{
        $msg_retorno = "Erro ao excluir agendar causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));