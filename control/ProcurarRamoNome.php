<?php
    session_start();
    include "../model/Conexao.php";
    include "../model/Ramo.php";
    $conexao = new Conexao();
    $ramo    = new Ramo($conexao);
    if(!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == ""){
        $ramo->codempresa = $_SESSION['codempresa'];
    }else{
        $ramo->codempresa = $_POST["codempresa"];
    }    
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $res = $ramo->procuraNome($_POST["nome"]);
    }else{
        $res = $ramo->procuraNome("");//procurar tudo
    }
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        echo 'Encontrou ', $qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Código</th>';
        echo '<th>Nome</th>';
        echo '<th>Editar</th>';
        echo '<th>Excluir</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($ramo = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td>',$ramo["codramo"],'</td>';
            echo '<td>',$ramo["nome"],'</td>';
            echo '<td><a href="Ramo.php?codramo=',$ramo["codramo"],'" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a></td>';
            echo '<td><a href="#" onclick="excluir(',$ramo["codramo"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a></td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }else{
        echo '0';
    }

