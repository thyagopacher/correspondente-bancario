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
    $emailpadrao   = new EmailPadrao($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true; 
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $emailpadrao->$key = $value;
    }  

    if(!isset($emailpadrao->texto) || $emailpadrao->texto == NULL || $emailpadrao->texto == ""){
        $msg_retorno =  "Não pode atualizar sem texto !";
    }else{
        $emailpadrao->codempresa =  $_SESSION['codempresa'];
        $emailpadrao->codfuncionario  = $_SESSION['codpessoa'];
        $res = $emailpadrao->atualizar($emailpadrao);

        if($res === FALSE){
            $msg_retorno =  "Erro ao atualizar email padrão! Causado por:". mysqli_error($conexao->conexao);
            $sit_retorno = false;
        }else{
            $msg_retorno =  "Email padrão atualizado com sucesso!";
        }
    }    
    include "../model/Log.php";
    $log = new Log($conexao);
    
    
    $log->acao       = "procurar";
    $log->observacao = "Atualizado email padrão {$emailpadrao->assunto} - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();       
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));