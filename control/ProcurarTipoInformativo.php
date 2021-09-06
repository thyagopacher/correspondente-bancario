<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/TipoInformativo.php";
    
    $conexao = new Conexao();
    $tipo    = new TipoInformativo($conexao);
    if(!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == ""){
        $tipo->codempresa = $_SESSION['codempresa'];
    }else{
        $tipo->codempresa = $_POST["codempresa"];
    }        
    
    
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
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
            echo '<td style="text-align: left;">',$tipo["nome"],'</td>';
            $arrayJavascript = "new Array('{$tipo["codtipo"]}', '{$tipo["nome"]}', '{$tipo["cor"]}')";
            echo '<td>';
            echo '<a href="javascript:setaEditarTipo(',$arrayJavascript,')" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '<a href="#" onclick="excluir(',$tipo["codtipo"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }else{
        echo '';
    }

