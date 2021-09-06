<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/TipoConta.php";
    
    $conexao = new Conexao();
    $tipo    = new TipoConta($conexao);
    if(!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == ""){
        $tipo->codempresa = $_SESSION['codempresa'];
    }else{
        $tipo->codempresa = $_POST["codempresa"];
    }        

    if(isset($_POST["nome"])){
        $res = $tipo->procuraNome($_POST["nome"]);
    }else{
        $res = $tipo->procuraNome("");
    }
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        echo 'Encontrou ', $qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Nome</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($tipo = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td>',$tipo["nome"],'</td>';
            $arrayJavascript = "new Array('{$tipo["codtipo"]}', '{$tipo["nome"]}')";
            echo '<td>';
            echo '<a href="javascript: setaEditarTipo(',$arrayJavascript,')" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '<a href="#" onclick="excluirTipo(',$tipo["codtipo"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }else{
        echo 'Nada encontrado!';
    }

