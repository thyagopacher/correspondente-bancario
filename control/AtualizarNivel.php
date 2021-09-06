<?php
    session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }       
    include "../model/Conexao.php";
    include "../model/Nivel.php";
    
    $conexao = new Conexao();
    $nivel = new Nivel($conexao);
    $msg_retorno = "";
    $sit_retorno = true;    
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $nivel->$key = $value;
    }    
    $nivel->codfuncionario = $_SESSION['codpessoa'];
    $nivel->codempresa = $_SESSION['codempresa'];    
    $res = $nivel->atualizar($nivel);

    if($res === FALSE){
        $msg_retorno =  "Erro ao atualizar nivel! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Nivel atualizado com sucesso!";
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));