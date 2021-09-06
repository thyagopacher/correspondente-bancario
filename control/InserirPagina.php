<?php
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
    $pagina = new Pagina($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true; 
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $pagina->$key = $value;
    }  
 
    $pagina2 = $conexao->comandoArray("select codpagina from pagina where nome = '{$pagina->nome}'");
    if(isset($pagina2) && isset($pagina2["codpagina"])){
        $msg_retorno =  "Já tem essa pagina inserida!";
        $sit_retorno = false;        
    }else{
        if(!isset($pagina->nome) || $pagina->nome == NULL || $pagina->nome == ""){
            $msg_retorno =  "Não pode inserir sem nome a página!";
            $sit_retorno = false;
        }else{
            $res = $pagina->inserir($pagina);

            if($res === FALSE){
                $msg_retorno =  "Erro ao inserir pagina! Causado por:". mysqli_error($conexao->conexao);
                $sit_retorno = false;
            }else{
                $msg_retorno =  "Página inserida com sucesso!";
            }
        }    
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));