<?php

session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
$conexao = new Conexao();
if (isset($_POST["codtabela"]) && $_POST["codtabela"] != NULL && $_POST["codtabela"] != "") {
    $and .= " and tabelaprazo.codtabela = '{$_POST["codtabela"]}'";
}
if (isset($_POST["prazo"]) && $_POST["prazo"] != NULL && $_POST["prazo"] != "") {
    $and .= " and tabelaprazo.prazode  <= '{$_POST["prazo"]}'";
    $and .= " and tabelaprazo.prazoate >= '{$_POST["prazo"]}'";
}
if (isset($_POST["codconvenio"]) && $_POST["codconvenio"] != NULL && $_POST["codconvenio"] != "") {
    $and .= " and tabela.codconvenio = '{$_POST["codconvenio"]}'";
}
$multiplosResultados = "";
$sql = "select tabelaprazo.prazode, tabelaprazo.prazoate, tabelaprazo.codtabela, tabelaprazo.codtabelap from tabelaprazo
inner join tabela on tabela.codtabela = tabelaprazo.codtabela
inner join banco on banco.codbanco = tabela.codbanco
inner join convenio on convenio.codconvenio = tabela.codconvenio
where dtinicio <= '" . date('Y-m-d') . "' and dtinicio <> '0000-00-00'
and (dtfim >= '" . date('Y-m-d') . "' or dtfim = '0000-00-00')    
{$and}    
and convenio.codconvenio > 0 order by tabelaprazo.codtabelap desc";

$restabela = $conexao->comando($sql);
$qtdtabela = $conexao->qtdResultado($restabela);
if($qtdtabela == 1){
    $tabelap = $conexao->resultadoArray($restabela);
}elseif($qtdtabela > 1){
    $multiplosResultados .= "Multiplos prazos ativos para essa tabela:\n";
    while($prazo = $conexao->resultadoArray($restabela)){
        $multiplosResultados .= "De: {$prazo["prazode"]} - Até: {$prazo["prazoate"]}\n";
    }
    
}
echo json_encode(array('de' => $tabelap["prazode"], 'ate' => $tabelap["prazoate"], 'codtabela' => $tabelap["codtabela"], 'codtabelap' => $tabelap["codtabelap"], 'multiplosResultados' => $multiplosResultados));

