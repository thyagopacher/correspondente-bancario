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
    $site = new Site($conexao);
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $site->$key = $value;
    }    
    
    if (isset($_FILES["logo"])) {
        include "Upload.php";
        $upload = new Upload($_FILES["logo"]);
        if ($upload->erro == "") {
            $site->logo = $upload->nome_final;
        }
    }
    
    $msg_retorno = "";
    $sit_retorno = true; 

    $res = $site->inserir($site);

    if($res === FALSE){
        $msg_retorno =  "Erro ao inserir site! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $msg_retorno =  "Site cadastrado com sucesso!";             
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));