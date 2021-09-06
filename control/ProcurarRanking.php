<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
include "../model/Conexao.php";
include "../model/Baixa.php";
$conexao = new Conexao();
$baixa = new Baixa($conexao);

$and = "";
if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $and .= " and b2.nome like '%{$_POST["nome"]}%'";
}
if (isset($_POST["mes"]) && $_POST["mes"] != NULL) {
    if ($_POST["mes"] < 10) {
        $mes = "0" . $_POST["mes"];
    } else {
        $mes = $_POST["mes"];
    }
    $and .= " and b2.dtcadastro >= '" . date("Y") . '-' . $mes . "-01'";
}
if (isset($_POST["mes"]) && $_POST["mes"] != NULL) {
    if ($_POST["mes"] < 10) {
        $mes = "0" . $_POST["mes"];
    } else {
        $mes = $_POST["mes"];
    }
    $and .= " and b2.dtcadastro <= '" . date("Y") . '-' . $mes . "-30'";
}
if (isset($_POST["codfuncionario"]) && $_POST["codfuncionario"] != NULL) {
    $and .= " and b2.codfuncionario = '{$_POST["codfuncionario"]}'";
}
if(!isset($_POST["codfilial"])){
    $_POST["codfilial"] = $_SESSION["codempresa"];
}
$sql = "select * from nivel where codnivel = '{$_SESSION["codnivel"]}'";
$nivel_logado = $conexao->comandoArray($sql);

//    if(isset($nivel_logado["nome"]) && $nivel_logado["nome"] == "OPERADOR"){
//        $and .= " and baixa.codfuncionario = '{$_SESSION['codpessoa']}'";
//    }
$sql = "select distinct pessoa.codpessoa, pessoa.codempresa, pessoa.nome, pessoa.imagem
    from baixa as b2
    inner join pessoa on pessoa.codpessoa = b2.codfuncionario and pessoa.codempresa = b2.codempresa
    inner join empresa on empresa.codempresa = b2.codempresa
    where 1 = 1
    and b2.codempresa = {$_POST["codfilial"]}
    {$and} order by b2.valor desc";
$res = $conexao->comando($sql);
$qtd = $conexao->qtdResultado($res);
$colocacao = 1;
$ranking = array();
if ($qtd > 0) {
    $linhaIteracao = 0;
    while ($baixa = $conexao->resultadoArray($res)) {
        $sql = "select sum(b2.valor) as valor from baixa as b2 where b2.codfuncionario = '{$baixa["codpessoa"]}' {$and}";
        $total = $conexao->comandoArray($sql);
        $ranking[$linhaIteracao]["codempresa"] = $baixa["codempresa"];
        $ranking[$linhaIteracao]["nome"] = $baixa["nome"];
        $ranking[$linhaIteracao]["codfuncionario"] = $baixa["codpessoa"];
        $ranking[$linhaIteracao]["valor"] = $total["valor"];
        $ranking[$linhaIteracao]["imagem"] = $baixa["imagem"];
        $linhaIteracao++;
    }
} else {
    echo '';
}

usort($ranking, "cmpValor");

echo 'Encontrou ', $qtd, ' resultados<br>';
echo '<table class="table table-bordered table-striped dataTable" role="grid">';
echo '<thead>';
echo '<tr>';
echo '<th class="sorting" style="width: 75px;"></th>';
echo '<th class="sorting"></th>';
echo '<th class="sorting">Unidade</th>';
echo '<th class="sorting">Colaborador</th>';
echo '<th class="sorting">Valor</th>'; 
echo '</tr>';
echo '</thead>';
echo '<tbody>';
for ($i = 0; $i < $qtd; $i++) {
    $empresa = $conexao->comandoArray("select razao from empresa where codempresa = '{$ranking[$i]["codempresa"]}'");
    echo '<tr>';
    echo '<td style="text-align: center;font-weight: bolder;font-size: 25px;margin-right: 0;">', $colocacao, ' °</td>';
    if (isset($ranking[$i]["imagem"]) && $ranking[$i]["imagem"] != NULL && $ranking[$i]["imagem"] != "") {
        echo '<td style="text-align: left;"><img style="width: 140px;" src="../arquivos/', $ranking[$i]["imagem"], '" alt="Imagem Colaborador"/></td>';
    } else {
        echo '<td style="text-align: left;">--</td>';
    }
    echo '<td style="text-align: left;">', $empresa["razao"], '</td>';
    echo '<td style="text-align: left;">', $ranking[$i]["nome"], '</td>';
    if ($ranking[$i]["codfuncionario"] != $_SESSION['codpessoa'] && $_SESSION["codnivel"] != '1' && $_SESSION["codnivel"] != '19') {
        echo '<td style="text-align: left;">--</td>';
    } elseif($_SESSION["codnivel"] == "1" || $_SESSION["codnivel"] == "19" || $ranking[$i]["codfuncionario"] == $_SESSION['codpessoa']){
        echo '<td style="text-align: left;">', number_format($ranking[$i]["valor"], 2, ",", "."), '</td>';
    }
    echo '</tr>';
    $colocacao++;
} 
echo '</tbody>'; 
echo '</table>'; 

function cmpValor($a, $b){
    if ($a["valor"] == $b["valor"]) {
        return 0;
    }
    return ($a["valor"] > $b["valor"]) ? -1 : 1;
}