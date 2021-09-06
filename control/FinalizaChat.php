<?php

session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
include "../model/Conexao.php";
$conexao = new Conexao();
$resFinalizaChat = $conexao = $conexao->comando("update chat set finalizado = 's' where (codpessoa1 = '{$_SESSION['codpessoa']}' or codpessoa2 = '{$_SESSION['codpessoa']}')");
if($resFinalizaChat !== FALSE){
    die(json_encode(array('mensagem' => "Chat finalizado", 'situacao' => true)));
}else{
    die(json_encode(array('mensagem' => "Erro ao finalizar chat causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
}