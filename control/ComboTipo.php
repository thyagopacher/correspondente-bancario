<?php
    session_start();
    include "../model/Conexao.php";    
    $conexao = new Conexao();

    $sql     = "select codtipo, nome from tipoconta where codempresa = '{$_SESSION["codempresa"]}' order by nome";
    $res     = $conexao->comando($sql);
    $qtd     = $conexao->qtdResultado($res);

    if($qtd > 0){
        echo '<option value="">--Selecione--</option>';
        while($tipo = $conexao->resultadoArray($res)){
            echo '<option value="',$tipo["codtipo"],'">',$tipo["nome"],'</option>';
        }
    }else{
        echo '<option value="">Nada encontrado!</option>';
    }

