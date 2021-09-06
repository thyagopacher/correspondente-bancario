<?php
try{
    session_cache_expire(180000);
    session_start();
    
    header ('Content-type: text/html; charset=UTF-8');
    function __autoload($class_name) {
        if(file_exists("../model/".$class_name . '.php')){
            include "../model/".$class_name . '.php';
        }elseif(file_exists("../visao/".$class_name . '.php')){
            include "../visao/".$class_name . '.php';
        }elseif(file_exists("./".$class_name . '.php')){
            include "./".$class_name . '.php';
        }
    }
    $conexao  = new Conexao();
    $pessoa   = new Pessoa($conexao);
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $pessoa->$key = str_replace("select", "", str_replace("insert", "", str_replace("update", "", str_replace("or 1 = 1", "", $value))));
    }    
    $pessoa2 = $pessoa->login();
    
    if(!isset($pessoa2["codpessoa"])){
        die(json_encode(array('mensagem' => 'Login ou senha inválidos!', 'situacao' => false)));
    }else{
        if($pessoa2["status"] == "i"){
            die(json_encode(array('mensagem' => 'Login inativo!!!', 'situacao' => false)));
        }

        $_SESSION["nome"]       = $pessoa2["nome"];
        $_SESSION["codnivel"]   = $pessoa2["codnivel"];
        $_SESSION['codpessoa']  = $pessoa2["codpessoa"]; 
        $_SESSION['codempresa'] = $pessoa2["codempresa"];
        $_SESSION["imagem"]     = $pessoa2["imagem"];
        
        $acesso             = new Acesso($conexao);
        $acesso->codempresa = $_SESSION['codempresa'];
        $acesso->codpessoa  = $_SESSION['codpessoa'];
        $acesso->data       = date('Y-m-d');
        $acesso->enderecoip = $_SERVER["REMOTE_ADDR"];
        $acesso->salvar();
        die(json_encode(array('mensagem' => '', 'situacao' => true)));
    }
}catch(Exception $ex){
    die(json_encode(array('mensagem' => 'Erro ao realizar login causado por: '.$ex.'!!', 'situacao' => false)));
}
 