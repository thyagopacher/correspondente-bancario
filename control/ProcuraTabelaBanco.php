<?php
session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }   
include "../model/Conexao.php";
$conexao   = new Conexao();

if(isset($_POST["codconvenio"]) && $_POST["codconvenio"] != NULL && $_POST["codconvenio"] != ""){
    $and .= " and tabela.codconvenio = {$_POST["codconvenio"]}";
}
$sql = "select codtabela, nome from tabela where codbanco = '{$_POST["codbanco"]}' and codempresa = {$_SESSION["codempresa"]} {$and} order by nome";
// echo "<pre>{$sql}</pre>";
$restabela = $conexao->comando($sql);
$qtdtabela = $conexao->qtdResultado($restabela);
if($qtdtabela > 0){
    echo '<option value="">--Selecione--</option>';
    while($tabela = $conexao->resultadoArray($restabela)){
        echo '<option value="',$tabela["codtabela"],'">',$tabela["nome"],'</option>';
    }
}else{
    echo '<option value="">--Nada encontrado--</option>';
}
    
    