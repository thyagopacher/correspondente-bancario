<?php

session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    if (isset($_POST["retorno_especial"]) && $_POST["retorno_especial"] != NULL && $_POST["retorno_especial"] == "s") {
        die('<script>alert("Sua sessão caiu, por favor logue novamente!!!");window.close();</script>');
    } else {
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }
}

function __autoload($class_name) {
    if (file_exists("../model/" . $class_name . '.php')) {
        include "../model/" . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}

$conexao = new Conexao();
$manual = new Manual($conexao);

$msg_retorno = "";
$sit_retorno = true;

$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
foreach ($variables as $key => $value) {
    $manual->$key = $value;
}
if (isset($_FILES["arquivo"]) && $_FILES["arquivo"] != NULL) {
    $manualp = $conexao->comandoArray("select arquivo from manual where codmanual = '{$manual->codmanual}'");
    if (isset($manualp["arquivo"]) && $manualp["arquivo"] != NULL && $manualp["arquivo"] != "" && file_exists("../arquivos/{$manualp["arquivo"]}")) {
        $resApagarImagemAnterior = unlink("../arquivos/{$manualp["arquivo"]}");
    }
    $upload = new Upload($_FILES["arquivo"]);
    if ($upload->erro == "") {
        $manual->arquivo = $upload->nome_final;
    }
}else{
    if (isset($_POST["retorno_especial"]) && $_POST["retorno_especial"] != NULL && $_POST["retorno_especial"] == "s") {
        die('<script>alert("Arquivo é obrigatório!!!");window.close();</script>');
    } else {
        die(json_encode(array('mensagem' => "Arquivo é obrigatório!!!", 'situacao' => false)));
    }    
}
$manual->codfuncionario = $_SESSION['codpessoa'];
$res = $manual->inserir();
if ($res === FALSE) {
    if (isset($_POST["retorno_especial"]) && $_POST["retorno_especial"] != NULL && $_POST["retorno_especial"] == "s") {
        die('<script>alert("Erro ao inserir manual! Causado por:"'.mysqli_error($conexao->conexao).'");window.close();</script>');
    } else {
        die(json_encode(array('mensagem' => "Erro ao inserir manual! Causado por:" . mysqli_error($conexao->conexao), 'situacao' => false)));
    }
} else {
    if (isset($upload->erro) && $upload->erro != NULL && $upload->erro != "") {
        $msg_retorno .= " Problemas com o envio do arquivo: " . $upload->erro;
    }
    if (isset($_POST["retorno_especial"]) && $_POST["retorno_especial"] != NULL && $_POST["retorno_especial"] == "s") {
        die('<script>alert("Manual inserido com sucesso!' . $msg_retorno . '");window.close();</script>');
    } else {
        die(json_encode(array('mensagem' => "Manual inserido com sucesso!'.$msg_retorno.'", 'situacao' => true)));
    }
}

echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
