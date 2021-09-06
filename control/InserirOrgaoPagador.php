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
    $orgao   = new OrgaoPagador($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $orgao->$key = $value;
    }    
    $orgao->codfuncionario = $_SESSION['codpessoa'];
    $res = $orgao->inserir();
    if($res === FALSE){
        $msg_retorno = "Erro ao inserir orgao! Causado por:". mysqli_error($conexao->conexao);
        mail("thyago.pacher@gmail.com", "Erro sistema de emprestimos - control/InserirOrgaoPagador.php", $msg_retorno);   
        $sit_retorno = false;
    }else{
        $msg_retorno = "Órgao Pagador inserido com sucesso!";
        $sit_retorno = true;        
    }

    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));