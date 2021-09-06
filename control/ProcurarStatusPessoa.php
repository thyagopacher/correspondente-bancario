<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/StatusPessoa.php";
    
    $conexao = new Conexao();
    $status  = new StatusPessoa($conexao);
    
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $res = $status->procuraNome($_POST["nome"]);
    }else{
        $res = $status->procuraNome("");
    }
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        echo 'Encontrou ', $qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Código</th>';
        echo '<th>Nome</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($status = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td>',$status["codstatus"],'</td>';
            echo '<td>',$status["nome"],'</td>';
            $arrayJavascript = "new Array('{$status["codstatus"]}', '{$status["nome"]}')";
            echo '<td>';
            echo '<a href="javascript:setaEditarStatus(',$arrayJavascript,')" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '<a href="#" onclick="excluirStatus(',$status["codstatus"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }else{
        echo '';
    }

