<?php
session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   

include "../model/Conexao.php";
include "../model/Pessoa.php";
include "../model/Telefone.php";
$conexao = new Conexao();
$pessoa = new Pessoa($conexao);

$msg_retorno = "";
$sit_retorno = true;

$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_REQUEST;
foreach ($variables as $key => $value) {
    $pessoa->$key = $value;
}

if (!isset($pessoa->nome) || $pessoa->nome == NULL || $pessoa->nome == "") {
    $msg_retorno = ("Não pode cadastrar pessoa sem nome!");
    $sit_retorno = false;
} else {
    if ($sit_retorno) {
        $pessoa->porcentagem = str_replace(",", ".", $pessoa->porcentagem);
        $pessoap = $conexao->comandoArray("select * from pessoa where codempresa = '{$_SESSION['codempresa']}' and login = '{$pessoa->email}' and senha = '".base64_encode($pessoa->senha)."'");
        if(isset($pessoap) && isset($pessoap["nome"])){
            die(json_encode(array('mensagem' => "Não pode inserir usuários repetidos!!!", 'situacao' => false)));
        }
        if(isset($_POST["codempresa"]) && $_POST["codempresa"] != NULL && $_POST["codempresa"] != ""){
            $pessoa->codempresa = $_POST["codempresa"];
        }elseif(isset($_SESSION['codempresa']) && $_SESSION['codempresa'] != NULL && $_SESSION['codempresa'] != ""){
            $pessoa->codempresa = $_SESSION['codempresa'];
        }
        if(!isset($pessoa->codnivel) || $pessoa->codnivel == NULL || $pessoa->codnivel == ""){
            $pessoa->codnivel = 24;
        }
        $pessoa->codpessoa = $_POST["codpessoa"];
        $res = $pessoa->inserir();
        $codigo_pessoa = mysqli_insert_id($conexao->conexao);
        if ($res === FALSE) {
            $msg_retorno = "Erro ao cadastrar pessoa! Causado por:" . mysqli_error($conexao->conexao);
            $sit_retorno = false;
        }else{
            $msg_retorno = "pessoa inserida com sucesso!";
        
            include "../model/Log.php";
            $log             = new Log($conexao);
            $log->acao       = "atualizar";
            
            $log->codpagina  = 4;
            
            $log->data       = date('Y-m-d');
            $log->hora       = date('H:i:s');
            $log->observacao =  "Pessoa inserida com sucesso: {$pessoa->nome} - Email: {$pessoa->email} - Status: {$pessoa->status} - Sexo: {$pessoa->sexo}";
            $log->inserir();             
        }
    }
}

echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
