<?php
    session_start();
    include "../model/Conexao.php";    
    $conexao = new Conexao();

    $and = "";
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["naomaster"]) && $_POST["naomaster"] != NULL && $_POST["naomaster"] != ""){
        $and .= " and codnivel <> '1'";
    }
    
    $sql     = "select * from nivel where codempresa = '{$_SESSION['codempresa']}' {$and} order by nome";
    $res     = $conexao->comando($sql);
    $qtd     = $conexao->qtdResultado($res);
    
    echo '<p>';
    echo '<label>Nível</label>';
    echo '<select class="form-control" name="codnivel" id="codnivelPermissao" onchange="escolheCombo()" title="Selecione aqui o nível">';
    if($qtd > 0){
        echo '<option value="">--Selecione--</option>';
        while($nivel = $conexao->resultadoArray($res)){
            echo '<option value="',$nivel["codnivel"],'">',$nivel["nome"],'</option>';
        }
    }else{
        echo '<option value="">Nada encontrado!</option>';
    }
    echo '</select>';
    echo '</p>';
