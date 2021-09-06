<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    
    $comunicadop = $conexao->comandoArray('select texto, nome, DATE_FORMAT(dtcadastro, "%d/%m/%Y") as dtcadastro2  from comunicado where codcomunicado = '. $_GET["codcomunicado"]);
    
    echo 'Nome: ', $comunicadop["nome"], ' - Dt. cadastro: ',$comunicadop["dtcadastro2"],'<br>';
    echo 'Texto: ',$comunicadop["texto"];