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
    $tabela   = new Tabela($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $tabela->$key = $value;
    }   
    $conexao->comando("delete from proposta where codtabela = '{$_POST["codtabela"]}'");
    $sql = "select count(1) as qtd from proposta where codtabela = '{$_POST["codtabela"]}'";
 
    $tabelaQtd = $conexao->comandoArray($sql);
    if(isset($tabelaQtd["qtd"]) && $tabelaQtd["qtd"] != NULL && $tabelaQtd["qtd"] != "" && $tabelaQtd["qtd"] > 0){
        die(json_encode(array('mensagem' => "Não pode excluir tabela está relacionado a propostas!!!", 'situacao' => false)));
    }
    $res = $tabela->excluir();
    if($res === FALSE){
        $msg_retorno = "Erro ao excluir tabela! Causado por:". mysqli_error($conexao->conexao);
        mail("thyago.pacher@gmail.com", "Erro sistema de condominios - control/ExcluirTabela.php", $msg_retorno);   
        $sit_retorno = false;
    }else{
        $msg_retorno = "Tabela excluido com sucesso!";
        $sit_retorno = true;        
    }

    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));