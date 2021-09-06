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
    if (isset($_POST["codempresa"]) && $_POST["codempresa"] != NULL && $_POST["codempresa"] != "") {
        $and .= " and conta.codempresa = '{$_POST["codempresa"]}'";
    } else {
        $and .= " and conta.codempresa = '{$_SESSION['codempresa']}'";
    }
    $sql = "select conta.*,DATE_FORMAT(conta.data, '%d/%m/%Y') as data2, pessoa.nome as funcionario
        from conta
        inner join pessoa on pessoa.codpessoa = conta.codfuncionario
        where 1 = 1 {$and} order by conta.data desc";
    $res = $conexao->comando($sql) or die(mysqli_error($conexao->conexao));
    $qtd = $conexao->qtdResultado($res);
    if ($qtd > 0) {
        $nome = "Fluxo de Caixa";
        $html = "";
        $html .= '<table class="responstable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Data</th>';
        $html .= '<th>Nome</th>';
        $html .= '<th>Valor</th>';
        $html .= '<th>Tipo</th>';
        $html .= '<th>Cadastrado por</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $somaCredito = 0.0;
        $somaDebito = 0.0;
        while ($conta = $conexao->resultadoArray($res)) {
            $html .= '<tr>';
            $html .= '<td>'. $conta["data2"]. '</td>';
            $html .= '<td>'. $conta["nome"]. '</td>';
            $html .= '<td>'. number_format($conta["valor"], 2, ",", "."). '</td>';
            if ($conta["movimentacao"] == "R") {
                $html .= '<td>Crédito</td>';
                $somaCredito = $somaCredito + $conta["valor"];
            } elseif ($conta["movimentacao"] == "D") {
                $html .= '<td>Débito</td>';
                $somaDebito = $somaDebito + $conta["valor"];
            }
            $html .= '<td>'. $conta["funcionario"]. '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '<tfoot>';
        $html .= '<tr>';
        $html .= '<td colspan="2" style="padding: 10px;"> Crédito: R$ '. number_format($somaCredito, 2, ",", "."). '</td>';
        $html .= '<td colspan="2" style="padding: 10px;"> Débitos: R$ '. number_format($somaDebito, 2, ",", "."). '</td>';
        $html .= '<td colspan="2" style="padding: 10px;"> Saldo: R$ '. number_format($somaCredito - $somaDebito, 2, ",", "."). '</td>';
        $html .= '</tr>';
        $html .= '</tfoot>';        
        $html .= '</table>';

        $_POST["html"] = $html;
        $paisagem = "sim";        
        
        if(isset($_POST["tipo"]) && $_POST["tipo"] == "xls"){
            include "./GeraExcel.php";
        }else{
            include "./GeraPdf.php";
        }         
    } else {
        echo '<script>alert("Sem caixa encontrado!");window.close();</script>';
    }
} catch (Exception $ex) {
    echo '<script>alert("Erro ao pesquisar caixa! Causado por: ',$ex,'");window.close();</script>';
}