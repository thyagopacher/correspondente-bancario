<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/SmsPadrao.php";
    $conexao = new Conexao();
    $smspadrao  = new SmsPadrao($conexao);

    if(isset($_POST["texto"]) && $_POST["texto"] != NULL && $_POST["texto"] != ""){
        $and .= " and texto = '{$_POST["texto"]}'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
        $and .= " and dtcadastro >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
        $and .= " and dtcadastro = '{$_POST["data2"]}'";
    }
    $res = $conexao->comando("select smspadrao.*, DATE_FORMAT(dtcadastro, '%d/%m/%Y') as dtcadastro2  from smspadrao where 1 = 1 {$and}");
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        echo 'Encontrou ', $qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Dt. Cadastro</th>';
        echo '<th>Texto</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($smspadrao = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td>',$smspadrao["dtcadastro2"],'</td>';
            echo '<td>',$smspadrao["texto"],'</td>';
            $arrayJavascript = "new Array('{$smspadrao["codsmspadrao"]}', '{$smspadrao["texto"]}')";
            echo '<td>';
            echo '<a href="SmsPadrao.php?codsmspadrao=',$smspadrao["codsmspadrao"],'" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '<a href="#" onclick="excluirSmsPadrao2(',$smspadrao["codsmspadrao"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
            echo '</td>'; 
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }else{
        echo '';
    }

