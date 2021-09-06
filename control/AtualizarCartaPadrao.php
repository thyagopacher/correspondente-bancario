<?php
    session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }       
    include "../model/Conexao.php";
    include "../model/Conta.php";
    include "../visao/Utilitario.php";
    
    $conexao     = new Conexao();
    $cartapadrao   = new CartaPadrao($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true; 
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $cartapadrao->$key = $value;
    }  

    if(!isset($cartapadrao->texto) || $cartapadrao->texto == NULL || $cartapadrao->texto == ""){
        $msg_retorno =  "Não pode atualizar sem texto !";
    }else{
        $cartapadrao->codempresa =  $_SESSION['codempresa'];
        $cartapadrao->codfuncionario  = $_SESSION['codpessoa'];
        $res = $cartapadrao->atualizar($cartapadrao);

        if($res === FALSE){
            $msg_retorno =  "Erro ao atualizar carta padrão! Causado por:". mysqli_error($conexao->conexao);
            $sit_retorno = false;
        }else{
            $msg_retorno =  "Carta padrão atualizado com sucesso!";
        }
    }    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));