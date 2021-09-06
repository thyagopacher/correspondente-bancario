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
    $nivel = new Nivel($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true; 
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $nivel->$key = $value;
    }    
    if($nivel->nome == "Adm. Filial" && $_SESSION["codnivel"] != 1){
        die(json_encode(array('mensagem' => "Não pode criar nivel Adm. Filial ele é fixo e só a matriz altera!", 'situacao' => false)));
    }
    $nivel2 = $conexao->comandoArray("select codnivel from nivel where nome = '{$nivel->nome}' and codempresa = '{$_SESSION['codempresa']}'");
    if(isset($nivel2) && isset($nivel2["codnivel"])){
        $msg_retorno = "Já tem um nivel com esse nome!";
        $sit_retorno = false;        
    }else{
        if(!isset($nivel->nome) || $nivel->nome == NULL || $nivel->nome == ""){
            $msg_retorno = "Não pode inserir sem nome!";
            $sit_retorno = false;
        }else{    
            $nivel->codfuncionario = $_SESSION['codpessoa'];
            $nivel->codempresa = $_SESSION['codempresa'];
            $res = $nivel->inserir($nivel);

            if($res === FALSE){
                $msg_retorno =  "Erro ao inserir nivel! Causado por:". mysqli_error($conexao->conexao);
                $sit_retorno = false;
            }else{
                $msg_retorno =  "Nivel inserido com sucesso!";
            }
        }    
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));