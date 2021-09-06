<?php

/* 
 * @author Thyago Henrique Pacher - thyago.pacher@gmail.com
 */

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
    $cache   = new Cache();
    if(!isset($_POST["codproposta"]) || $_POST["codproposta"] == NULL || $_POST["codproposta"] == ""){
        die(json_encode(array('mensagem' => 'Sem código de proposta', 'situacao' => false)));
    }     
    
    $proposta              = new Proposta($conexao);
    $proposta->codstatus   = 22;
    $proposta->codproposta = $_POST["codproposta"];
    $resInserirProposta    = $proposta->atualizar();
    
    if($resInserirProposta != FALSE){
        $cache->save('esteira_tela_' . $_SESSION['codempresa'] . '_' . $_SESSION["codnivel"] . '_' . $_SESSION["codpessoa"], '', '1 minutes');
        die(json_encode(array('mensagem' => 'Cliente avisado com sucesso!', 'situacao' => true)));
    }else{
        die(json_encode(array('mensagem' => 'Erro ao avisar cliente causado por:'. mysqli_error($conexao->conexao), 'situacao' => false)));
    }