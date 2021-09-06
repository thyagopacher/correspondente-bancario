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
    
    $conexao       = new Conexao();
    
    
    $msg_retorno = "";
    $sit_retorno = true;        
    $conexao->comando("delete from tabelanivel where codnivel = '{$_POST["codnivel"]}' and codempresa = '{$_SESSION['codempresa']}'");  
    $qtdVetor = count($_POST["tabela_selecao"]);
    $codempresa = $_SESSION['codempresa'];
    for($i = 0; $i < $qtdVetor; $i++) {
        $codtabela = $_POST["tabela_selecao"][$i];
        $resInserirTabela = $conexao->comando("INSERT INTO `tabelanivel`(`codtabela`, `codnivel`, `codempresa`, `dtcadastro`, `codfuncionario`) VALUES ('{$codtabela}', '{$_POST["codnivel"]}', '{$codempresa}', '".date("Y-m-d H:i:s")."', '{$_SESSION['codpessoa']}')");
        if($resInserirTabela == FALSE){
            die(json_encode(array('mensagem' => "Erro ao inserir nova tabela por nivel causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
        }
    }
    
    die(json_encode(array('mensagem' => "Tabela nivel salva com sucesso", 'situacao' => true)));