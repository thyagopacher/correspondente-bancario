<?php
session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
include "../model/Conexao.php";
$conexao   = new Conexao();
$restabela = $conexao->comando("select codtabela, nome from tabela where codbanco = '{$_POST["codbanco"]}' and codempresa = {$_SESSION["codempresa"]} order by nome");
$qtdtabela = $conexao->qtdResultado($restabela);
if($qtdtabela > 0){
    echo '<option value="">--Selecione--</option>';
    while($tabela = $conexao->resultadoArray($restabela)){
        echo '<option value="',$tabela["codtabela"],'">',$tabela["nome"],'</option>';
    }
}else{
    echo '<option value="">--Nada encontrado--</option>';
}
    
    