<?php
    include "../model/Conexao.php";
    $conexao = new Conexao();
    
    $pessoa = $conexao->comandoArray("select codpessoa from pessoa where login = '{$_POST["login"]}'");
    if(isset($pessoa["codpessoa"]) && $pessoa["codpessoa"] != NULL && $pessoa["codpessoa"] != ""){
        echo 1;
    }else{
        echo 0;
    }