<?php

header('Content-type: text/html; charset=UTF-8');
session_start();
include "../model/Conexao.php";
$conexao = new Conexao();
$empresa = $conexao->comandoArray('select razao from empresa where codempresa = ' . $_SESSION["codempresa"]);
?>
<style>
    body{
        font-family: arial;
    }
    .titulo_td{
        font-weight: bolder;
    }
    @media print {
        #nimprimir {display: none; width: 0; height: 0; overflow: hidden; font-family: arial; float: left;}
    }    
</style>
<div id="nimprimir">
    <a href="javascript: window.print();" id="nimprimir">Imprimir</a>
    <a href="javascript: self.close();" id="nimprimir"> - Voltar</a>
</div>
<?php

echo '<img style="width: 170px;" src="../visao/recursos/img/south_sistemas.png">';
echo '<table style="width: 25%; float: right;">';
echo '<tr><td colspan="2" class="titulo_td">RELATÓRIO ANALITICO DE VENDAS' . '</td></tr>';
echo '<tr><td style="text-align: right;">Unidade:</td><td>' . $empresa["razao"] . '</td></tr>';
echo '<tr><td style="text-align: right;">Data:</td><td>' . date("d/m/Y H:i:s") . '</td></tr>';
echo '</table>';

echo '<table style="width: 100%; border-bottom: 1px solid black; margin-bottom: 30px;">';
echo '<tr><td style="border-bottom: 1px solid black;" colspan="4" class="titulo_td">MENSAL</td></tr>';
echo '<tr>';
$sql2 = "select IFNULL(sum(valor),0) as valor from metafuncionario where codempresa = '{$_SESSION["codempresa"]}' and dtinicio >= '" . date("Y-m-") . "01' and dtfim >= '" . date("Y-m-d") . "'";
$meta = $conexao->comandoArray($sql2);

$sql1 = "select IFNULL(sum(valor),0) as valor from baixa where codempresa = '{$_SESSION["codempresa"]}' and  dtcadastro >= '" . date("Y-m-") . "01 00:00:00' and dtcadastro <= '" . date("Y-m-d") . " 23:59:59'";
$producaoTotal = $conexao->comandoArray($sql1);

$resultadoFinal = ($meta["valor"] - $producaoTotal["valor"]) * (-1);
echo '<td>META/MÊS</td>';
echo '<td>R$ ', number_format($meta["valor"], 2, ',', '.'), '</td>';
echo '<td style="font-weight: bolder;">Resultado</td>';
echo '<td style="font-weight: bolder;">R$ ', number_format($resultadoFinal, 2, ',', '.'), '</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Total de vendas:</td>';
echo '<td colspan="3">R$ ', number_format($producaoTotal["valor"], 2, ',', '.'), '</td>';
echo '</tr>';
echo '</table>';

$array_pessoas = array();
$sql = 'select codpessoa, nome from pessoa where codempresa = ' . $_SESSION["codempresa"] . ' and status = "a" and codnivel in(select codnivel from nivel where nome = "OPERADOR") order by pessoa.nome';
$respessoa = $conexao->comando($sql);
$qtdpessoa = $conexao->qtdResultado($respessoa);
if ($qtdpessoa > 0) {
    echo '<table style="width: 100%">';
    echo '<tr>';
    echo '<td>Vendedores</td>';
    echo '<td>Metas Mensais</td>';
    echo '<td>Vendas Mensais</td>';
    echo '<td>Resultados</td>';
    echo '<td></td>';
    echo '</tr>';
    $estiloLinhaFundo = "#D8D8D8";
    while ($pessoa = $conexao->resultadoArray($respessoa)) {
        echo '<tr style="background: ', $estiloLinhaFundo, '">';
        $array_pessoas[] = array('codpessoa' => $pessoa["codpessoa"], 'nome' => $pessoa["nome"]);
        echo '<td>' . $pessoa["nome"] . '</td>';

        $sql2 = "select IFNULL(sum(valor),0) as valor from metafuncionario where codempresa = '{$_SESSION["codempresa"]}'  and codfuncionario = {$pessoa["codpessoa"]} and dtinicio >= '" . date("Y-m-") . "01' and dtfim >= '" . date("Y-m-d") . "'";
        $meta = $conexao->comandoArray($sql2);
        echo '<td>R$ ', number_format($meta["valor"], 2, ',', '.'), '</td>';

        $sql = "select IFNULL(sum(valor),0) as valor from baixa where codempresa = '{$_SESSION["codempresa"]}' and codfuncionario = {$pessoa["codpessoa"]} and  dtcadastro >= '" . date("Y-m-") . "01 00:00:00' and dtcadastro <= '" . date("Y-m-d") . " 23:59:59'";
        $baixa = $conexao->comandoArray($sql);
        echo '<td>R$ ', number_format($baixa["valor"], 2, ',', '.'), '</td>';

        $resultadoMeta = ($meta["valor"] - $baixa["valor"]) * (-1);
        echo '<td>R$ ', number_format($resultadoMeta, 2, ',', '.'), '</td>';
        if ($resultadoMeta < 0) {
            echo '<td><img src="/visao/recursos/img/excluir.png"/></td>';
        } else {
            echo '<td><img src="/visao/recursos/img/positivo.png"/></td>';
        }
        echo '</tr>';
        if ($estiloLinhaFundo == "#D8D8D8") {
            $estiloLinhaFundo = "white";
        } elseif ($estiloLinhaFundo == "white") {
            $estiloLinhaFundo = "#D8D8D8";
        }
    }
    echo '</table>';
} else {
    die("Não tem operadores cadastrados!!!");
}

echo '<table style="width: 100%; border-bottom: 1px solid black; margin-bottom: 30px;    margin-top: 30px;">';
echo '<tr><td style="border-bottom: 1px solid black;" colspan="4" class="titulo_td">DIÁRIA</td></tr>';
echo '<tr>';
$meta["valor"] = calculaMetaFuncionario();

$sql1 = "select IFNULL(sum(valor),0) as valor from baixa where codempresa = '{$_SESSION["codempresa"]}' and  dtcadastro >= '" . date("Y-m-d") . " 00:00:00' and dtcadastro <= '" . date("Y-m-d") . " 23:59:59'";
$producaoTotal = $conexao->comandoArray($sql1);

$resultadoFinal = ($meta["valor"] - $producaoTotal["valor"]) * (-1);
echo '<td>META/DIA</td>';
echo '<td>R$ ', number_format($meta["valor"], 2, ',', '.'), '</td>';
echo '<td style="font-weight: bolder;">Resultado</td>';
echo '<td style="font-weight: bolder;">R$ ', number_format($resultadoFinal, 2, ',', '.'), '</td>';
echo '</tr>';

echo '<tr>';
echo '<td>Total de vendas:</td>';
echo '<td colspan="3">R$ ', number_format($producaoTotal["valor"], 2, ',', '.'), '</td>';
echo '</tr>';
echo '</table>';

$array_pessoas = array();
$sql = 'select codpessoa, nome from pessoa where codempresa = ' . $_SESSION["codempresa"] . ' and status = "a" and codnivel in(select codnivel from nivel where nome = "OPERADOR") order by pessoa.nome';
$respessoa = $conexao->comando($sql);
$qtdpessoa = $conexao->qtdResultado($respessoa);
if ($qtdpessoa > 0) {
    echo '<table style="width: 100%">';
    echo '<tr>';
    echo '<td>Vendedores</td>';
    echo '<td>Metas Diárias</td>';
    echo '<td>Vendas Diárias</td>';
    echo '<td>Resultados</td>';
    echo '<td></td>';
    echo '</tr>';
    $estiloLinhaFundo = "#D8D8D8";
    while ($pessoa = $conexao->resultadoArray($respessoa)) {
        echo '<tr style="background: ', $estiloLinhaFundo, '">';
        $array_pessoas[] = array('codpessoa' => $pessoa["codpessoa"], 'nome' => $pessoa["nome"]);
        echo '<td>' . $pessoa["nome"] . '</td>';

        $meta["valor"] = calculaMetaFuncionario($pessoa["codpessoa"]);
        echo '<td>R$ ', number_format($meta["valor"], 2, ',', '.'), '</td>';

        $sql = "select IFNULL(sum(valor),0) as valor from baixa where codempresa = '{$_SESSION["codempresa"]}' and codfuncionario = {$pessoa["codpessoa"]} and  dtcadastro >= '" . date("Y-m-d") . " 00:00:00' and dtcadastro <= '" . date("Y-m-d") . " 23:59:59'";
        $baixa = $conexao->comandoArray($sql);
        echo '<td>R$ ', number_format($baixa["valor"], 2, ',', '.'), '</td>';

        $resultadoMeta = ($meta["valor"] - $baixa["valor"]) * (-1);
        echo '<td>R$ ', number_format($resultadoMeta, 2, ',', '.'), '</td>';
        if ($resultadoMeta < 0) {
            echo '<td><img src="/visao/recursos/img/excluir.png"/></td>';
        } else {
            echo '<td><img src="/visao/recursos/img/positivo.png"/></td>';
        }
        echo '</tr>';
        if ($estiloLinhaFundo == "#D8D8D8") {
            $estiloLinhaFundo = "white";
        } elseif ($estiloLinhaFundo == "white") {
            $estiloLinhaFundo = "#D8D8D8";
        }
    }
    echo '</table>';
} else {
    die("Não tem operadores cadastrados!!!");
}

function calculaMetaFuncionario($codfuncionario = '', $baixaAte = 30) {
    global $conexao;
    if ($codfuncionario != '') {
        $and = " and codfuncionario = '{$codfuncionario}'";
    }
    /*     * pegando o valor total da meta do funcionário */
    $sql = "select sum(valor) as valor from metafuncionario where codempresa = '{$_SESSION['codempresa']}' 
    and dtcadastro >= '" . date("Y-m-") . "01 00:00:00'    
    and dtcadastro <= '" . date("Y-m-") . "30 23:59:59'     $and       
    order by codmeta desc";
    $metaFuncionario = $conexao->comandoArray($sql);
    $diasUteis = 0;

    if (isset($metaFuncionario) && $metaFuncionario["valor"] != NULL && $metaFuncionario["valor"] != "") {

        /*         * somatório valor total vendido */
        $baixaTotal = $conexao->comandoArray("select sum(valor) as valor from baixa 
        where codempresa = '{$_SESSION['codempresa']}' {$and}
        and dtcadastro >= '" . date("Y-m-") . "01'    
        and dtcadastro <= '" . date("Y-m-") . $baixaAte . "'");

        $ultimo_dia = date("t", mktime(0, 0, 0, date("m"), '01', date("Y")));
        $dia_mes = date("Y-m-");
        $semana = array(
            'Sun' => 'domingo',
            'Mon' => 'segunda',
            'Tue' => 'terca',
            'Wed' => 'quarta',
            'Thu' => 'quinta',
            'Fri' => 'sexta',
            'Sat' => 'sabado'
        );
        for ($i = date("d"); $i <= $ultimo_dia; $i++) {
            if ($i < 10) {
                $dia_mes2 = "0" . $i;
            } else {
                $dia_mes2 = $i;
            }
            $data_selec = $dia_mes . $dia_mes2;
            $sql = "select * from dia where data = '{$data_selec}' and codempresa = '{$_SESSION['codempresa']}'";
            $dia_feriado = $conexao->comandoArray($sql);
            if (isset($dia_feriado) && $dia_feriado["data"] != NULL && $dia_feriado["data"] != "") {
                continue; //tira os feriados
            }

            $dia_semana = date("D", strtotime($data_selec));
//            $sql = "select * from horariofilial where codempresa = '{$_SESSION['codempresa']}' and dia = '{$semana[$dia_semana]}'";
//            $horarioFilial = $conexao->comandoArray($sql);
//            if (isset($horarioFilial["codhorario"]) && $horarioFilial["codhorario"] != NULL && $horarioFilial["codhorario"] != "") {
//                $diasUteis++;
//            }
            if ($semana[$dia_semana] == "segunda" || $semana[$dia_semana] == "terca" || $semana[$dia_semana] == "quarta" || $semana[$dia_semana] == "quinta" || $semana[$dia_semana] == "sexta") {
                $diasUteis++;
            }
        }

        if ($diasUteis == 0) {
            $resultadoFinal = 0;
        } else {
            $resultadoFinal = ($metaFuncionario["valor"] - $baixaTotal["valor"]) / $diasUteis;
        }
    }

    return number_format($resultadoFinal, 2, '.', '');
}
