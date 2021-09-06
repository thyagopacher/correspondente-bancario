<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
include "../model/Conexao.php";
include "../model/Servico.php";

$conexao = new Conexao();
$servico = new Servico($conexao);
if (!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == "") {
    $servico->codempresa = $_SESSION['codempresa'];
} else {
    $servico->codempresa = $_POST["codempresa"];
}
if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $res = $servico->procuraNome($_POST["nome"]);
} else {
    $res = $servico->procuraNome("");
}
$qtd = $conexao->qtdResultado($res);

if ($qtd > 0) {
    $nome = "Relatório de Serviços";
    $html = '<table class="responstable">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Nome</th>';
    $html .= '<th>Tipo</th>';
    $html .= '<th>R$</th>';
    $html .= '<th>Data</th>';
    $html .= '<th>Para</th>';
    $html .= '<th>Bl</th>';
    $html .= '<th>Apto</th>';
    $html .= '<th>Dt. Cadastro</th>';
    $html .= '<th>Funcionário</th>';
    if (isset($_POST["tipo"]) && $_POST["tipo"] == "xls") {
        $rescampo = $conexao->comando("select * from campoextra where codpagina = '{$_POST["codpagina"]}' and codempresa = '{$_SESSION['codempresa']}'");
        $qtdcampo = $conexao->qtdResultado($rescampo);
        if ($qtdcampo > 0) {
            while ($campo = $conexao->resultadoArray($rescampo)) {
                $html .= '<th>' . $campo["titulo"] . '</th>';
            }
        }
    }
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    while ($servico = $conexao->resultadoArray($res)) {
        $html .= '<tr>';
        $html .= '<td style="text-align: left;">' . $servico["nome"] . '</td>';
        $html .= '<td style="text-align: left;">' . $servico["tipo"] . '</td>';
        $html .= '<td>' . number_format($servico["valor"], 2, ",", "") . '</td>';
        $html .= '<td>' . $servico["data2"] . '</td>';
        $html .= '<td>' . $servico["morador"] . '</td>';
        $html .= '<td>' . $servico["apartamento"] . '</td>';
        $html .= '<td>' . $servico["bloco"] . '</td>';
        $html .= '<td>' . $servico["dtcadastro2"] . '</td>';
        $html .= '<td>' . $servico["funcionario"] . '</td>';
        if (isset($_POST["tipo"]) && $_POST["tipo"] == "xls") {
            $rescampo = $conexao->comando("select * from campoextra where codpagina = '{$_POST["codpagina"]}' and codempresa = '{$_SESSION['codempresa']}'");
            $qtdcampo = $conexao->qtdResultado($rescampo);
            if ($qtdcampo > 0) {
                while ($campo = $conexao->resultadoArray($rescampo)) {
                    $sql = "select * from valorcampo where codempresa = '{$_SESSION['codempresa']}' and codcampo = '{$campo["codcampo"]}' and primariaorigem = '{$pessoa["codpessoa"]}'";
                    $valorCampo = $conexao->comandoArray($sql);
                    $html .= '<td>' . $valorCampo["valor"] . '</td>';
                }
            }
        }
        $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';
    $paisagem = "sim";
    $_POST["html"] = $html;
//        echo $html;
    if (isset($_POST["tipo"]) && $_POST["tipo"] == "xls") {
        include "./GeraExcel.php";
    } else {
        include "./GeraPdf.php";
    }
} else {
    echo '';
}

