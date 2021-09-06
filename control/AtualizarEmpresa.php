<?php

session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
}
include "../model/Conexao.php";
include "../model/Empresa.php";
$conexao = new Conexao();
$empresa = new Empresa($conexao);

$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
foreach ($variables as $key => $value) {
    $empresa->$key = $value;
}

$msg_retorno = "";
$sit_retorno = true;
if (isset($empresa->email) && $empresa->email != NULL && $empresa->email != "") {
    if (strpos($empresa->email, '@') == FALSE) {
        $msg_retorno = "Não pode inserir e-mail sem @!";
        $sit_retorno = false;
        die(json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno)));
    } elseif (strlen($empresa->email) < 5) {
        $msg_retorno = "Impossível ter um e-mail com 5 letras!";
        $sit_retorno = false;
        die(json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno)));
    }
}
if (isset($empresa->telefone) && $empresa->telefone != NULL && $empresa->telefone != "" && strlen($empresa->telefone) < 5) {
    $msg_retorno = "Impossível ter um telefone com 5 letras!";
    $sit_retorno = false;
    die(json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno)));
}
if (isset($empresa->celular) && $empresa->celular != NULL && $empresa->celular != "" && strlen($empresa->celular) < 5) {
    $msg_retorno = "Impossível ter um celular com 5 letras!";
    $sit_retorno = false;
    die(json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno)));
}

if (isset($_FILES["logo"])) {
    include "Upload.php";
    $upload = new Upload($_FILES["logo"]);
    if ($upload->erro == "") {
        $empresa->logo = $upload->nome_final;
    }
}
$empresa->pctsupervisao = str_replace(",", ".", $empresa->pctsupervisao);
$empresa->porcentagem = str_replace(",", ".", $empresa->porcentagem);
$res = $empresa->atualizar();

if ($res === FALSE) {
    $msg_retorno = "Erro ao atualizar empresa! Causado por:" . mysqli_error($conexao->conexao);
    mail("thyago.pacher@gmail.com", "Erro sistema de condominios - control/AtualizarEmpresa.php", $msg_retorno);
    $sit_retorno = false;
} else {
    $msg_retorno = "Empresa atualizada com sucesso!";
    include("../model/Log.php");
    $log = new Log($conexao);
    $log->codpessoa = $_SESSION['codpessoa'];
    
    $log->acao = "atualizar";
    $log->observacao = "Atualizar Empresa - {$empresa->razao} com celular:{$empresa->celular} e telefone: {$empresa->telefone} - E-mail: {$empresa->email}";
    $log->codpagina = "11";
    $log->inserir($log);
}
if (isset($upload->erro) && $upload->erro != NULL && $upload->erro != "") {
    $msg_retorno .= " Problemas com o envio do arquivo: " . $upload->erro;
}
echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno, 'imagem' => $empresa->logo));
