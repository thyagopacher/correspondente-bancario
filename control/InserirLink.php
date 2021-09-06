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
$link = new Link($conexao);

$msg_retorno = "";
$sit_retorno = true;

$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
foreach ($variables as $key => $value) {
    $link->$key = $value;
}

if(validar_url($link->link) == FALSE){
        die(json_encode(array('mensagem' => "Por favor insira uma URL válida!!!", 'situacao' => false)));
}
$link->codfuncionario = $_SESSION['codpessoa'];
$res = $link->inserir();
if ($res === FALSE) {
    $msg_retorno = "Erro ao inserir link! Causado por:" . mysqli_error($conexao->conexao);
    mail("thyago.pacher@gmail.com", "Erro sistema - control/AtualizarLink.php", $msg_retorno);
    $sit_retorno = false;
} else {
    $msg_retorno = "Link inserido com sucesso!";
    $sit_retorno = true;
}

echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));

function validar_url($url) {
	return preg_match('|^http(s)?://[a-z0-9-]+(\.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}