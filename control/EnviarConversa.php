<?php

session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
function __autoload($class_name) {
    if(file_exists("../model/".$class_name . '.php')){
        include "../model/".$class_name . '.php';
    }elseif(file_exists("../visao/".$class_name . '.php')){
        include "../visao/".$class_name . '.php';
    }elseif(file_exists("./".$class_name . '.php')){
        include "./".$class_name . '.php';
    }
}

$conexao          = new Conexao();
$chat             = new Chat($conexao);
$chat->codempresa = $_SESSION['codempresa'];
$chat->codpessoa1 = $_POST["enviadopor"];
$chat->codpessoa2 = $_POST["logado"];
$chat->dtcadastro = date("Y-m-d H:i:s");
$chat->texto      = $_POST["texto"];

$resInserirChat   = $chat->inserir();

if($resInserirChat === FALSE){
    die(json_encode(array('mensagem' => "Erro ao enviar msg. chat causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
}else{
    die(json_encode(array('mensagem' => "Msg. enviada", 'situacao' => true)));
}
