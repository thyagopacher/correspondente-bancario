<?php

session_start();
header('Content-Type: text/html; charset=utf-8');
function __autoload($class_name) {
    if (file_exists("../model/" . $class_name . '.php')) {
        include "../model/" . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}

if (!isset($_POST["nome"]) || $_POST["nome"] == NULL || $_POST["nome"] == "") {
    if (isset($_POST["retorno_especial"]) && $_POST["retorno_especial"] != NULL && $_POST["retorno_especial"] == "s") {
        die('<script>alert("Erro ao inserir carteira");window.close();</script>');
    } else {
        die(json_encode(array('mensagem' => "Por favor preencha nome de carteira!!!", 'situacao' => false)));
    }
}

if (isset($_FILES['arquivo']) && $_FILES['arquivo'] != NULL) {
    $conexao   = new Conexao();
    $separador = explode(".", $_FILES['arquivo']["name"]);
    if ($separador[1] == "xls" || $separador[1] == "csv") {
        $sql       = 'select codcarteira from carteira where nome = "'.$_POST["nome"].'" and codempresa = '. $_SESSION["codempresa"];
        $carteirap = $conexao->comandoArray($sql);
        if(isset($carteirap["codcarteira"]) && $carteirap["codcarteira"] != NULL && $carteirap["codcarteira"] != ""){
            $codigo_carteira = $carteirap["codcarteira"];
        }else{
            $carteira = new Carteira($conexao);
            $carteira->nome = $_POST["nome"];
            $resInserirCarteira = $carteira->inserir();
            $codigo_carteira = mysqli_insert_id($conexao->conexao);
            if ($resInserirCarteira == FALSE) {
                die(json_encode(array('mensagem' => "Erro ao inserir carteira! Causado por:" . mysqli_error($conexao->conexao), 'situacao' => false)));
            }
        }
        
        $importacao = new Importacao();

        /*         * fazendo o upload do arquivo */
        $upload = new Upload($_FILES['arquivo']);
        if ($upload->erro != "") {
            die(json_encode(array('mensagem' => "Erro ao importar arquivo causado por:" . $upload->erro, 'situacao' => false)));
        } else {
            $importacao->arquivo = $upload->nome_final;
        }
        
        if ($separador[1] == "xls"){
            include("./excel/reader.php");
            $data = new Spreadsheet_Excel_Reader();
            /** lendo o arquivo xls */ 
            $data->setOutputEncoding('UTF-8');
            $data->read('../arquivos/'. $importacao->arquivo);
            $importacao->qtdlinha           = $data->sheets[0]['numRows'] - 2;
        }elseif($separador[1] == "csv") {
            $lines = file("../arquivos/" . $importacao->arquivo);
            $importacao->qtdlinha = count($lines) - 2;
        }
        
        $importacao->codcarteira        = $codigo_carteira;
        $importacao->atualizar_cliente  = $_POST["atualizar_cliente"];
        $importacao->adicionar_carteira = $_POST["adicionar_carteira"];
        $importacao->categoriacliente   = $_POST["layout"];
        $resInserirImportacao           = $importacao->inserir();
        if ($resInserirImportacao == FALSE) {
            die(json_encode(array('mensagem' => "Erro ao carregar arquivo para servidor! Causado por:" . mysqli_error($conexao->conexao), 'situacao' => false)));
        }else{
            if (isset($_POST["retorno_especial"]) && $_POST["retorno_especial"] != NULL && $_POST["retorno_especial"] == 's') {
                die('<script>alert("Importação em andamento!!!");window.close();</script>');
            } else {
                die(json_encode(array('mensagem' => "Importação em andamento!!!", 'situacao' => true)));
            }            
        }
    } else {
        if (isset($_POST["retorno_especial"]) && $_POST["retorno_especial"] != NULL && $_POST["retorno_especial"] == 's') {
            header('Content-Type: text/html; charset=utf-8');
            die('<script>alert("Extensão para importação não permitida!!!");window.close();</script>');
        } else {
            die(json_encode(array('mensagem' => "Extensão para importação não permitida!!!", 'situacao' => false)));
        }
    }
} else {
    die(json_encode(array('mensagem' => "Arquivo não encontrado, por favor selecione algum!!!", 'situacao' => false)));
}
