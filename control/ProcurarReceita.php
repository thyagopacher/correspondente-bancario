<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    
    $and  = "";
    $and2 = "";
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
        $and2 .= " and conta.data >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
        $and2 .= " and conta.data <= '{$_POST["data2"]}'";
    }
    $and2 .= " and conta.codempresa = '{$_SESSION['codempresa']}'";
    $sql = "select conta.valor, DATE_FORMAT(conta.data, '%d/%m/%Y') as data2, conta.nome as de
    from conta
    where movimentacao = 'R' {$and2} order by conta.data";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $totalReceita = 0.0;
        echo 'Encontrou ', $qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Data</th>';
        echo '<th>Valor</th>';
        echo '<th>De</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($reserva = $conexao->resultadoArray($res)){
            $totalReceita += $reserva["valor"];
            echo '<tr>';
            echo '<td>',$reserva["data2"],'</td>';
            echo '<td>',number_format($reserva["valor"], 2, ",", "."),'</td>';
            echo '<td>',$reserva["de"],'</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        
        echo 'Total R$ ', number_format($totalReceita, 2, ",", ".");
    }else{
        echo '';
    }

