<?php

session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   

include "../model/Conexao.php";
include "../model/Tarefa.php";
$conexao = new Conexao();
$tarefa = new Tarefa($conexao);

$msg_retorno = "";
$sit_retorno = true;

$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
foreach ($variables as $key => $value) {
    $tarefa->$key = $value;
}
if (isset($_FILES['imagem']) && $_FILES['imagem'] != NULL) {
    include "Upload.php";
    $upload = new Upload($_FILES['imagem']);
    if ($upload->erro == "") {
        $tarefa->imagem = $upload->nome_final;
    }
}

if (!isset($tarefa->localizacao) || $tarefa->localizacao == NULL || $tarefa->localizacao == "") {
    $msg_retorno = "Não pode inserir sem localizacao !";
    $sit_retorno = false;
} else {
    $sql = "select codtarefa 
    from tarefa 
    where localizacao = '{$tarefa->localizacao}'
    and descricao = '{$tarefa->descricao}'
    and resolvido = '{$tarefa->resolvido}'
    and codfuncionario = '{$_SESSION['codpessoa']}'    
    and prioridade = '{$tarefa->prioridade}'    
    and codempresa = '{$_SESSION['codempresa']}'";
    $tarefa2 = $conexao->comandoArray($sql);
    if(isset($tarefa2) && isset($tarefa2["codtarefa"])){
        $msg_retorno = "Já inseriu um tarefa com esse localizacao para essa empresa!";
        $sit_retorno = false;        
    }else{
        if (isset($_POST["codempresa"]) && $_POST["codempresa"] != NULL && $_POST["codempresa"] != "") {
            $tarefa->codempresa = $_POST["codempresa"];
        } else {
            $tarefa->codempresa = $_SESSION['codempresa'];
        }
        $tarefa->codfuncionario = $_SESSION['codpessoa'];
        $res = $tarefa->inserir($tarefa);

        if ($res === FALSE) {
            $msg_retorno = "Erro ao inserir tarefa! Causado por:" . mysqli_error($conexao->conexao);
            $sit_retorno = false;
        } else {
            $msg_retorno = "Tarefa inserido com sucesso!";
        }
    }
}
if(isset($upload->erro) && $upload->erro != NULL && $upload->erro != ""){
    $msg_retorno .= " Problemas com o envio do arquivo: ". $upload->erro;
}
echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
