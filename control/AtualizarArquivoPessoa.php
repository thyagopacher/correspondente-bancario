<?php

session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
include "../model/Conexao.php";
include "../model/Arquivo.php";
$conexao = new Conexao();
$arquivo = new Arquivo($conexao);

$msg_retorno = "";
$sit_retorno = true;

$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
foreach ($variables as $key => $value) {
    $arquivo->$key = $value;
}
if (isset($_FILES["arquivo"]) && $_FILES["arquivo"] != NULL) {
    include "Upload.php";
    $upload = new Upload($_FILES["arquivo"]);
    if ($upload->erro == "") {
        $arquivo->link = $upload->nome_final;
    }
}

if ($sit_retorno) {
    if (isset($_POST["codempresa"]) && $_POST["codempresa"] != NULL && $_POST["codempresa"] != "") {
        $arquivo->codempresa = $_POST["codempresa"];
    } else {
        $arquivo->codempresa = $_SESSION['codempresa'];
    }
    $arquivo->codfuncionario = $_SESSION['codpessoa'];
    $res = $arquivo->atualizar();

    if ($res === FALSE) {
        $msg_retorno = "Erro ao atualizar arquivo! Causado por:" . mysqli_error($conexao->conexao);
        mail("thyago.pacher@gmail.com", "Erro sistema control/AtualizarArquivo.php", $msg_retorno);
        $sit_retorno = false;
    } else {
        $msg_retorno = "Arquivo atualizado com sucesso!";
    }
}
if(isset($upload->erro) && $upload->erro != NULL && $upload->erro != ""){
    $msg_retorno .= " Problemas com o envio do arquivo: ". $upload->erro;
}
include "../model/Log.php";
$log = new Log($conexao);


$log->acao       = "procurar";
$log->observacao = "Atualizado arquivo pessoa - em ". date('d/m/Y'). " - ". date('H:i');
$log->codpagina  = "0";

$log->hora = date('H:i:s');
$log->inserir();   

echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
