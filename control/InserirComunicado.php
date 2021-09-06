<?php

session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
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
$comunicado = new Comunicado($conexao);

$msg_retorno = "";
$sit_retorno = true;

$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
foreach ($variables as $key => $value) {
    $comunicado->$key = $value;
}
if (isset($_FILES["arquivo"]) && $_FILES["arquivo"] != NULL) {
    $comunicadop = $conexao->comandoArray("select arquivo from comunicado where codcomunicado = '{$comunicado->codcomunicado}'");
    if (isset($comunicadop["arquivo"]) && $comunicadop["arquivo"] != NULL && $comunicadop["arquivo"] != "" && file_exists("../arquivos/{$comunicadop["arquivo"]}")) {
        $resApagarImagemAnterior = unlink("../arquivos/{$comunicadop["arquivo"]}");
    }
    $upload = new Upload($_FILES["arquivo"]);
    if ($upload->erro == "") {
        $comunicado->arquivo = $upload->nome_final;
    }
}
$comunicado->codfuncionario = $_SESSION['codpessoa'];
$res = $comunicado->inserir();
if ($res === FALSE) {
    $msg_retorno = "Erro ao inserir comunicado! Causado por:" . mysqli_error($conexao->conexao);
    mail("thyago.pacher@gmail.com", "Erro sistema - control/AtualizarComunicado.php", $msg_retorno);
    $sit_retorno = false;
} else {
    $msg_retorno = "Comunicado inserido com sucesso!";
    $sit_retorno = true;
}
if (isset($upload->erro) && $upload->erro != NULL && $upload->erro != "") {
    $msg_retorno .= " Problemas com o envio do arquivo: " . $upload->erro;
}
echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
