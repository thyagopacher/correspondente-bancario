<?php

session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu. por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
include "../model/Proposta.php";
$conexao = new Conexao();
$proposta = new Proposta($conexao);

$and = "";
if (isset($_POST["codcliente"]) && $_POST["codcliente"] != NULL && $_POST["codcliente"] != "") {
    $and .= " and proposta.codcliente = '{$_POST["codcliente"]}'";
}
if (isset($_POST["codstatus"]) && $_POST["codstatus"] != NULL && $_POST["codstatus"] != "") {
    $and .= " and proposta.codstatus = '{$_POST["codstatus"]}'";
}
if (isset($_POST["cpf"]) && $_POST["cpf"] != NULL && $_POST["cpf"] != "") {
    $and .= " and cliente.cpf like '{$_POST["cpf"]}%'";
}
if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $and .= " and proposta.nome like '%{$_POST["nome"]}%'";
}
if (isset($_POST["data1"]) && $_POST["data1"] != NULL) {
    $and .= " and proposta.dtcadastro >= '{$_POST["data1"]}'";
}
if (isset($_POST["data2"]) && $_POST["data2"] != NULL) {
    $and .= " and proposta.dtcadastro <= '{$_POST["data2"]}'";
}
if (isset($_SESSION["codnivel"]) && $_SESSION["codnivel"] != 1 && $_SESSION["codnivel"] != 18) {
    $and .= " and proposta.codfuncionario = '{$_SESSION['codpessoa']}'";
}
$sql = "select proposta.codproposta. proposta.nome. DATE_FORMAT(proposta.dtcadastro. '%d/%m/%Y') as dtcadastro2. proposta.codfuncionario. 
    funcionario.nome as funcionario. cliente.nome as cliente. cliente.cpf. proposta.vlsolicitado. convenio.nome as convenio. proposta.codbanco. proposta.codconvenio. 
    proposta.codtabela. proposta.prazo. banco.nome as banco. tabela.nome as tabela. status.nome as status. proposta.codstatus. proposta.codcliente. proposta.vlparcela. proposta.vlliberado. proposta.codbeneficio.
    status.cor. proposta.codbanco2. proposta.agencia. proposta.conta. proposta.operacao. proposta.poupanca. proposta.dtvenda. proposta.observacao. proposta.pendente.
    DATE_FORMAT(proposta.dtpago. '%d/%m/%Y') as dtpago2. proposta.dtpago
    from proposta
    inner join pessoa as funcionario on funcionario.codpessoa = proposta.codfuncionario 
    inner join pessoa as cliente on cliente.codpessoa = proposta.codcliente and cliente.codempresa = proposta.codempresa
    inner join convenio on convenio.codconvenio = proposta.codconvenio
    inner join banco on banco.codbanco = proposta.codbanco
    inner join tabela on tabela.codtabela = proposta.codtabela
    inner join statusproposta as status on status.codstatus = proposta.codstatus
    where 1 = 1
    {$and} order by proposta.dtcadastro desc";
$res = $conexao->comando($sql);
$qtd = $conexao->qtdResultado($res);

if ($qtd > 0) {
    $html .= '<table class="responstable">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Cadastro</th>';
    $html .= '<th>Banco</th>';
    $html .= '<th>Convênio</th>';
    $html .= '<th>Tabela</th>';
    $html .= '<th>Prazo</th>';
    $html .= '<th>Valor</th>';
    $html .= '<th>Dt Pgto</th>';
    $html .= '<th>Status</th>';
    $html .= '<th>Opções</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    while ($proposta = $conexao->resultadoArray($res)) {
        $observacao = $conexao->comandoArray("select observacao from observacaoproposta as obs where obs.codempresa = '{$_SESSION['codempresa']}' and obs.codcliente = '{$proposta["codcliente"]}' and obs.observacao <> '' and codstatus = '7' order by codobservacao desc");
        $comissoes = $conexao->comandoArray("select * from tabelaprazo where codtabela = '{$proposta["codtabela"]}' and prazoate >= '{$proposta["prazo"]}' and prazode <= '{$proposta["prazo"]}'");
        $html .= '<tr class="' . $proposta["cor"] . '">';
        $html .= '<td style="text-align: left;">' . $proposta["dtcadastro2"] . '</td>';
        $html .= '<td style="text-align: left;">' . $proposta["banco"] . '</td>';
        $html .= '<td style="text-align: left;">' . $proposta["convenio"] . '</td>';
        $html .= '<td style="text-align: left;">' . $proposta["tabela"] . '</td>';
        $html .= '<td style="text-align: left;">' . $proposta["prazo"] . '</td>';
        $html .= '<td style="text-align: left;">' . number_format($proposta["vlsolicitado"], 2, ",", "") . '</td>';
        $html .= '<td style="text-align: left;">' . $proposta["dtpago2"] . '</td>';
        $html .= '<td style="text-align: left;">' . $proposta["status"] . '</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';


    $_POST["html"] = $html;
    $paisagem = "sim";

    if (isset($_POST["tipo"]) && $_POST["tipo"] == "xls") {
        include "./GeraExcel.php";
    } else {
        include "./GeraPdf.php";
    }
} else {
    echo '<script>alert("Sem proposta encontrada!");window.close();</script>';
}

