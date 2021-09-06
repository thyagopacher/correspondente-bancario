<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/NivelPagina.php";
    $conexao = new Conexao();
    $nivel   = new NivelPagina($conexao);

    $nivel_pagina = $nivel->procuraCodigo($_SESSION["codnivel"], $_POST["pagina"]);
    
    if(isset($nivel_pagina)){
        echo "{$nivel_pagina["inserir"]},{$nivel_pagina["atualizar"]},{$nivel_pagina["excluir"]},{$nivel_pagina["procurar"]},{$nivel_pagina["mostrar"]}";
    }else{
        echo '-1';
    }

