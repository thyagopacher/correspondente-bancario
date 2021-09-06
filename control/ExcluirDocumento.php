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
    $documento   = new Documento($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    $varocumentobles = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($varocumentobles as $key => $value){
        $documento->$key = $value;
    }    
    $documento->codfuncionario = $_SESSION['codpessoa'];
    $documento->codempresa = $_SESSION['codempresa'];
    $res = $documento->excluir();
    if($res === FALSE){
        $msg_retorno = "Erro ao excluir documento! Causado por:". mysqli_error($conexao->conexao);
        mail("thyago.pacher@gmail.com", "Erro sistema - control/ExcluirDocumento.php", $msg_retorno);   
        $sit_retorno = false;
    }else{
        $msg_retorno = "Documento excluido com sucesso!";
        $sit_retorno = true;        
    }

    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));