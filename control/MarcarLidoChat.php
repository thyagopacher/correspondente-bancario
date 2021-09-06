<?php

    
    session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }       
    function __autoload($class_name) {
        if(file_exists('../model/'.$class_name . '.php')){
            include '../model/'.$class_name . '.php';
        }elseif(file_exists("../visao/".$class_name . '.php')){
            include "../visao/".$class_name . '.php';
        }elseif(file_exists("./".$class_name . '.php')){
            include "./".$class_name . '.php';
        }
    }
    $conexao = new Conexao();
    $sql = "update chat set lidopor1 = 's', lido = 's' where codpessoa1 = '{$_POST["codpessoa2"]}' and codpessoa2 = '{$_SESSION['codpessoa']}'";

    $res     = $conexao->comando($sql);
    if($res == FALSE){
        die(json_encode(array('mensagem' => "Não pode atualizar chat para lido:". mysqli_error($conexao->conexao), 'situacao' => false)));
    }else{
        mysqli_free_result($res);
    }    