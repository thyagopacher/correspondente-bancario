<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    
    include "../model/Conexao.php";
    include "../model/EmailPadrao.php";
    $conexao = new Conexao();
    $emailpadrao  = new EmailPadrao($conexao);

    if(isset($_POST["texto"]) && $_POST["texto"] != NULL && $_POST["texto"] != ""){
        $and .= " and texto = '{$_POST["texto"]}'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
        $and .= " and dtcadastro >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
        $and .= " and dtcadastro = '{$_POST["data2"]}'";
    }
    $res = $conexao->comando("select * from emailpadrao where 1 = 1 {$and}");
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        echo 'Encontrou ', $qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Assunto</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($emailpadrao = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td>',$emailpadrao["assunto"],'</td>';
            $arrayJavascript = "new Array('{$emailpadrao["codemailpadrao"]}', '{$emailpadrao["assunto"]}')";
            echo '<td>';
            echo '<a href="javascript:setaEditarEmailPadrao(',$arrayJavascript,')" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '<a href="#" onclick="excluirEmailPadrao2(',$emailpadrao["codemailpadrao"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }else{
        echo '0';
    }

