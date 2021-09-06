<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
try {
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $and = "";
    if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
        $and .= " and conta.nome like '%{$_POST["nome"]}%'";
    }
    if (isset($_POST["data"]) && $_POST["data"] != NULL && $_POST["data"] != "") {
        $and .= " and conta.data >= '{$_POST["data"]}'";
    }
    if (isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != "") {
        $and .= " and conta.data <= '{$_POST["data2"]}'";
    }
    if (isset($_POST["movimentacao"]) && $_POST["movimentacao"] != "" && $_POST["movimentacao"] != "T") {
        $and .= " and conta.movimentacao = '{$_POST["movimentacao"]}'";
    }
    if (isset($_POST["unidade"]) && $_POST["unidade"] != NULL && $_POST["unidade"] != "") {
        $and .= " and conta.codempresa = '{$_POST["unidade"]}'";
    } else {
        $and .= " and conta.codempresa = '{$_SESSION['codempresa']}'";
    }
    
    $sql = "select conta.*,DATE_FORMAT(conta.data, '%d/%m/%Y') as data2, pessoa.nome as funcionario
        from conta
        inner join pessoa on pessoa.codpessoa = conta.codfuncionario
        where 1 = 1 {$and} order by conta.data desc";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    if ($qtd > 0) {
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Data</th>';
        echo '<th>Nome</th>';
        echo '<th>Valor</th>';
        echo '<th>Tipo</th>';
        echo '<th>Cadastrado por</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        $somaCredito = 0.0;
        $somaDebito = 0.0;
        while ($conta = $conexao->resultadoArray($res)) {
            echo '<tr>';
            echo '<td>', $conta["data2"], '</td>';
            echo '<td>', $conta["nome"], '</td>';
            echo '<td>', number_format($conta["valor"], 2, ",", "."), '</td>';
            if ($conta["movimentacao"] == "R") {
                echo '<td>Crédito</td>';
                $somaCredito = $somaCredito + $conta["valor"];
            } elseif ($conta["movimentacao"] == "D") {
                echo '<td>Débito</td>';
                $somaDebito = $somaDebito + $conta["valor"];
            }
            echo '<td>', $conta["funcionario"], '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '<table class="tabela_procurar">';
        echo '<tr>';
        echo '<td style="padding: 10px;"> Crédito: R$ ', number_format($somaCredito, 2, ",", "."), '</td>';
        echo '<td style="padding: 10px;"> Débitos: R$ ', number_format($somaDebito, 2, ",", "."), '</td>';
        echo '<td style="padding: 10px;"> Saldo: R$ ', number_format($somaCredito - $somaDebito, 2, ",", "."), '</td>';
        echo '</tr>';
        echo '</table>';
    } else {
        echo '';
    }
} catch (Exception $ex) {
    echo 'Erro ocasionado por:', $ex;
}