<?php
if(!isset($_POST["texto"])){
    $_POST["texto"] = "teste";
}
if(!isset($_POST["numero"])){
    $_POST["numero"] = "4291046063";
}

$stringJson = file_get_contents("http://sms.multibr.com/painel/api.ashx?action=sendsms&lgn=(45)99753829&pwd=647032&msg={$_POST["texto"]}&numbers={$_POST["numero"]}");
$retorno    = json_decode($stringJson);

if($retorno->status == 1){
    echo json_encode(array('mensagem' => "Sucesso ao enviar a msg SMS!!!", 'situacao' => true));
}else{
    echo json_encode(array('mensagem' => "Problemas ao enviar msg SMS!!!", 'situacao' => false));
}

