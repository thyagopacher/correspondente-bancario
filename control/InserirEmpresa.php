<?php
session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
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

if(!empty($empresa->email)){
    if(strpos($empresa->email, '@') == FALSE){
        $msg_retorno = "Não pode inserir e-mail sem @!";
        $sit_retorno = false;
        die(json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno)));
    }elseif(strlen($empresa->email) < 5){
        $msg_retorno = "Impossível ter um e-mail com 5 letras!";
        $sit_retorno = false;
        die(json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno)));    
    }
}

if (isset($_FILES["logo"]) && $_FILES["logo"] != NULL) {
    include "Upload.php";
    $upload = new Upload($_FILES["logo"]);
    if ($upload->erro == "") {
        $empresa->logo = $upload->nome_final;
    }
}
if(!isset($empresa->codstatus) || $empresa->codstatus == NULL || $empresa->codstatus == ""){
    $empresa->codstatus = "3";
}
$empresa->pctsupervisao = str_replace(",", ".", $empresa->pctsupervisao);
$empresa->porcentagem = str_replace(",", ".", $empresa->porcentagem);
$empresa->codpessoa = $_SESSION['codpessoa'];
$empresa2 = $conexao->comandoArray("select codempresa from empresa where razao = '{$empresa->razao}' and cep = '{$empresa->cep}'");

if(isset($empresa2) && isset($empresa2["codempresa"])){
    $msg_retorno = "Já tem um cadastro de empresa com esse nome e cep!";
    $sit_retorno = false;    
}else{
    $res = $empresa->inserir();
    
    $codigo_empresa = mysqli_insert_id($conexao->conexao);
    if ($res === FALSE) {
        $msg_retorno = "Erro ao cadastrar empresa! Causado por:" . mysqli_error($conexao->conexao);
        $sit_retorno = false;
    } else {
        $msg_retorno = "Empresa cadastrada com sucesso!";
        
        include("../model/Log.php");
        $log = new Log($conexao);
        $log->codpessoa = $_SESSION['codpessoa'];
        
        $log->acao = "inserir";
        $log->observacao = "Inserir Empresa - ". $empresa->razao . " de e-mail :". $empresa->email. " de telefone: ". $empresa->telefone;
        $log->codpagina = "11";
        $log->inserir();          
    }
}
if(isset($upload->erro) && $upload->erro != NULL && $upload->erro != ""){
    $msg_retorno .= " Problemas com o envio do arquivo: ". $upload->erro;
}
echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno, 'imagem' => $empresa->logo, 'codempresa' => $codigo_empresa, 'codramo' =>$empresa->codramo));
