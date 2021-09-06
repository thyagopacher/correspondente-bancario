<?php

session_start();
include "../model/Conexao.php";
$conexao = new Conexao();
$sql = "select codbanco from banco where numbanco = '{$_POST["numbanco"]}' and nome <> ''";
$banco   = $conexao->comandoArray($sql);
echo $banco["codbanco"];


include "../model/Log.php";
$log = new Log($conexao);


$log->acao       = "procurar";
$log->observacao = "Procurado código banco - em ". date('d/m/Y'). " - ". date('H:i');
$log->codpagina  = "0";

$log->hora = date('H:i:s');
$log->inserir();     