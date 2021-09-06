<?php

header("Content-type: application/json");
session_start();
include "../model/Cache.php";
$cache = new Cache();
$dadosProducao = $cache->read('dadosProducao_' . $_SESSION['codempresa'] . '_' . $_SESSION["codpessoa"]);
if (!isset($valorMeta) || $valorMeta == NULL) {
    $and = '';
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $qtd_meses = 5;

//igual mês atual - qtd_meses meses
    $mes_inicial = (int) date("m") - $qtd_meses;
    $ano_inicial = date("Y");
    if ($mes_inicial < 0) {//colocado como soma pois o número mês inicial caso caia aqui vira negativo
        $mes_inicial = 12 + $mes_inicial;
        $ano_inicial = $ano_inicial - 1;
        $mes_final = (int) date("m") + 12;
    } else {
        $mes_final = (int) date("m");
    }
    $dados = array();
    if ($_SESSION["codnivel"] != 19) {
        $and = " and codfuncionario = '{$_SESSION["codpessoa"]}'";
    }
    $dados[0] = array("Data", "Produção", "Meta");

    for ($i = $mes_inicial; $i <= $mes_final; $i++) {
        if ($i > 12) {
            $i = $i - 12;
            $mes_final = $mes_final - 12;
            $ano_inicial = $ano_inicial + 1;
        }
        if ($i < 10) {
            $mes_corrente = "0" . $i;
        } else {
            $mes_corrente = $i;
        }
        $data_inicial = $ano_inicial . "-" . $mes_corrente . "-01";
        $data_final = $ano_inicial . "-" . $mes_corrente . "-31";

        //pegando a produção do mês
        $sql1 = "select IFNULL(sum(valor),0) as valor from baixa where codempresa = '{$_SESSION["codempresa"]}' and  dtcadastro >= '$data_inicial' and dtcadastro <= '$data_final' {$and}";
        $producao = $conexao->comandoArray($sql1);

        $sql2 = "select IFNULL(sum(valor),0) as valor from metafuncionario where codempresa = '{$_SESSION["codempresa"]}' and dtinicio >= '$data_inicial' and dtfim <= '$data_final' {$and}";
        $meta = $conexao->comandoArray($sql2);

        if (!isset($meta["valor"])) {
            $meta["valor"] = "0";
        }
        $producao["valor"] = str_replace(",", "", $producao["valor"]);
        $meta["valor"] = str_replace(",", "", $meta["valor"]);
        $dados[] = array($mes_corrente . '-' . $ano_inicial, (double) number_format($producao["valor"], 2, '.', ''), (double) $meta["valor"]);
        //caso o laço esteja beirando o final do ano
        if ($mes_inicial == 12) {
            $mes_inicial = 1;
            $ano_inicial = $ano_inicial + 1;
        }
    }

    $cache->save('dadosProducao_' . $_SESSION['codempresa'] . '_' . $_SESSION["codpessoa"], $dados, '2 minutes');
} else {
    $dados = $dadosProducao;
}
echo json_encode($dados);
