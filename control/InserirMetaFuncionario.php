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
    $meta   = new MetaFuncionario($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $meta->$key = $value;
    }    
    $meta->codempresa = $_SESSION["codempresa"];
    $meta->codfuncionario = $_POST["codfuncionario"];
    $res = $meta->inserir();
    if($res === FALSE){
        $msg_retorno = "Erro ao cadastrar meta! Causado por:". mysqli_error($conexao->conexao);
        mail("thyago.pacher@gmail.com", "Erro sistema de condominios - control/InserirMetaFuncionario.php", $msg_retorno);   
        $sit_retorno = false;
    }else{
        $msg_retorno = "Meta Funcionario cadastrado com sucesso!";
        $sit_retorno = true;  
        
        $funcionario = $conexao->comandoArray("select nome from pessoa where codpessoa = '{$meta->codfuncionario}'");
        include "../model/Log.php";
        $log = new Log($conexao);
        $log->acao = "inserir";
        
        $log->codpagina = 76;
        $log->codpessoa = $_SESSION['codpessoa'];
        
        $log->hora = date('H:i:s');
        $log->observacao = "Meta funcionário atualizada: {$meta->valor} do funcionário: {$funcionario["nome"]}";
        $log->inserir();              
    }

    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));