<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    function __autoload($class_name) {
        if(file_exists("../model/".$class_name . '.php')){
            include "../model/".$class_name . '.php';
        }elseif(file_exists("../visao/".$class_name . '.php')){
            include "../visao/".$class_name . '.php';
        }elseif(file_exists("./".$class_name . '.php')){
            include "./".$class_name . '.php';
        }
    }
    
    $conexao = new Conexao();
    $convenio   = new Convenio($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $convenio->$key = $value;
    }    
    
    $res = $convenio->excluir();
    if($res === FALSE){
        $msg_retorno = "Erro ao excluir convenio! Causado por:". mysqli_error($conexao->conexao);
        mail("thyago.pacher@gmail.com", "Erro sistema de empréstimos - control/ExcluirConvenio.php", $msg_retorno);   
        $sit_retorno = false;
    }else{
        $msg_retorno = "Convenio excluido com sucesso!";
        $sit_retorno = true;        
    }

    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));