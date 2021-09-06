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
    $proposta   = new Proposta($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $proposta->$key = $value;
    }    
    
    if($_SESSION["codnivel"] != '1' && $_SESSION["codnivel"] != '19'){
        die(json_encode(array('mensagem' => "Exclusão não permitida, solicite ao administrador!!!", 'situacao' => false)));
    }
    
    $sql = 'delete from observacaoproposta where codproposta = '. $proposta->codproposta;
    $conexao->comando($sql);
    
    $res = $proposta->excluir();
    if($res === FALSE){
        $msg_retorno = "Erro ao excluir proposta! Causado por:". mysqli_error($conexao->conexao);
        mail("thyago.pacher@gmail.com", "Erro sistema de condominios - control/ExcluirProposta.php", $msg_retorno);   
        $sit_retorno = false;
    }else{
        $msg_retorno = "Proposta excluida com sucesso!";
        $sit_retorno = true;        
    }

    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));