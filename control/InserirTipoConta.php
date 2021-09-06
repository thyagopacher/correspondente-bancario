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
    $tipo = new TipoConta($conexao);
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $tipo->$key = $value;
    }
    
    $msg_retorno = "";
    $sit_retorno = true; 
    $tipo2 = $conexao->comandoArray("select codtipo from tipoconta where nome = '{$tipo->nome}' and codempresa = '{$_SESSION['codempresa']}'");
    if(isset($tipo2) && isset($tipo2["nome"])){
        $msg_retorno =  "Já tem um tipo com esse nome!";
        $sit_retorno = false;        
    }else{       
        if(!isset($tipo->nome) || $tipo->nome == NULL || $tipo->nome == ""){
            $msg_retorno =  "Não pode inserir sem nome !";
        }else{ 

            $tipo->codempresa = $_SESSION['codempresa'];
            $res = $tipo->inserir($tipo);

            if($res === FALSE){
                $msg_retorno =  "Erro ao inserir tipo conta! Causado por:". mysqli_error($conexao->conexao);
                $sit_retorno = false;
            }else{
                $msg_retorno =  "Tipo conta inserido com sucesso!";
            }
        }    
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));