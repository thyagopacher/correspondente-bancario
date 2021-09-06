<?php
session_start();
include "../model/Conexao.php";
include "../model/BeneficioCliente.php";
$conexao = new Conexao();

$beneficio = new BeneficioCliente($conexao);
if (isset($_GET["cpf"]) && $_GET["cpf"] != NULL && $_GET["cpf"] != "") {
    $num = $beneficio->consultaCpfInss2($_GET["cpf"]);
} elseif (isset($_GET["beneficio"]) && $_GET["beneficio"] != NULL && $_GET["beneficio"] != "") {
    $num[0] = $_GET["beneficio"];
}


echo '<table class="table table-bordered table-striped dataTable" role="grid" >';
echo '<thead>';
echo '<tr>';
echo '<th>Num Beneficio</th>';
echo '<th>Margem</th>';
echo '<th>Representante legal</th>';
echo '<th>Tipo Pgto</th>';
echo '<th>Salário Base</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
for ($i = 0; $i < count($num); $i++) {
    $chave = $beneficio->consultaBenInss2($num[$i]);
    echo '<tr>';
    echo '<td>', $num[$i], '</td>';
    echo '<td>', str_replace('.', ',', $chave->dados_cadastrais->margemdisp), '</td>';
    echo '<td>', $chave->dados_cadastrais->representante_legal, '</td>';
    echo '<td>', $chave->dados_cadastrais->tipo_pagto, '</td>';
    echo '<td>', str_replace('.', ',', $chave->dados_cadastrais->mr), '</td>';
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';
