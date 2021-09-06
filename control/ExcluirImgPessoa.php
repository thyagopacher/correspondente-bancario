<?php
session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
include "../model/Conexao.php";
include "../model/Pessoa.php";
$conexao = new Conexao();
$pessoa  = new Pessoa($conexao);
$pessoa->codpessoa  = $_POST["codpessoa"];
$pessoa->codempresa = $_SESSION['codempresa'];
$pessoa->imagem     = " ";
$resAtualizarPessoa = $pessoa->atualizar();

if($resAtualizarPessoa !== FALSE){
    die(json_encode(array('mensagem' => "Imagem excluida com sucesso!!!", 'situacao' => true)));
}else{
    die(json_encode(array('mensagem' => "Erro ao excluir imagem causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
}
