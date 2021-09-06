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
    $coeficiente   = new Coeficiente($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $coeficiente->$key = $value;
    }    
   
    $coeficiente->valor = str_replace(",", ".", $coeficiente->valor);
    $res = $coeficiente->atualizar();
    if($res === FALSE){
        $msg_retorno = "Erro ao atualizar coeficiente! Causado por:". mysqli_error($conexao->conexao);
        mail("thyago.pacher@gmail.com", "Erro sistema de condominios - control/AtualizarCoeficiente.php", $msg_retorno);   
        $sit_retorno = false;
    }else{
        $msg_retorno = "Coeficiente atualizado com sucesso!";
        $sit_retorno = true;
    }
    include "../model/Log.php";
    $log = new Log($conexao);
    
    
    $log->acao       = "procurar";
    $log->observacao = "Atualizado coeficiente {$coeficiente->valor} - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();   
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));