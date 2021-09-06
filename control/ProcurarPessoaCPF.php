<?php

    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
include "../model/Conexao.php";
$conexao = new Conexao();
$pessoap = $conexao->comandoArray("select codpessoa from pessoa where cpf = '{$_POST["cpf"]}' and codempresa = '{$_SESSION['codempresa']}'");
echo $pessoap["codpessoa"];

include "../model/Log.php";
$log = new Log($conexao);


$log->acao       = "procurar";
$log->observacao = "Procurado pessoa pelo cpf: {$_POST["cpf"]} - em ". date('d/m/Y'). " - ". date('H:i');
$log->codpagina  = "0";

$log->hora = date('H:i:s');
$log->inserir();    