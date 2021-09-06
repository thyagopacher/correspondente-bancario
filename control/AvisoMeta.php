<?php

session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
include "../model/Conexao.php";
$conexao      = new Conexao();
$sql = "select codpessoa, nome from pessoa where codpessoa = '{$_SESSION['codpessoa']}'";
$funcionariop = $conexao->comandoArray($sql);

if(isset($funcionariop["codpessoa"]) && $funcionariop["codpessoa"] != NULL && $funcionariop["codpessoa"] != ""){
    echo '<img style="float: left;width: 300px;margin-right: 10px; margin-bottom: 190px;" src="/visao/recursos/img/mulher.jpg" alt="mulher feia"/>';
}
if(isset($_GET["codfrase"]) && $_GET["codfrase"] != NULL && $_GET["codfrase"] != ""){
    $and .= " and frase.codfrase = '{$_GET["codfrase"]}'";
}else{
    $and .= " and periodo = '".definePeriodo()."'";
}
$frasep = $conexao->comandoArray("select * from frase where popup = 's' {$and}");

echo "<div style='text-transform: initial;'>";
echo trocaFrase($frasep["texto"]).'<br>';
echo '<a style="float: right" href="javascript: window.parent.TINY.box.hide();">Vamos ao trabalho</a>';
echo '</div>';

function trocaFrase($frase){
    global $conexao, $funcionariop;
    
    $tabela_funcionario = '';
    $tabela_baixa       = '';
    $total_vendas       = 0.0;
    $frase = str_replace("[nome_colaborador]", ucfirst($funcionariop["nome"]), $frase);
    $sql = "select sum(valor) as valor from metafuncionario where codfuncionario in(select codpessoa from pessoa where codempresa = '{$_SESSION['codempresa']}')
    and dtcadastro >= '" . date("Y-m-") . "01 00:00:00'    
    and dtcadastro <= '" . date("Y-m-") . "30 23:59:59'";
    $metaFuncionario1 = $conexao->comandoArray($sql);
    if(strpos($frase, "[vendas_dia]")){
        $sql = "select baixa.valor, pessoa.nome as funcionario 
        from baixa 
        inner join pessoa on pessoa.codpessoa = baixa.codfuncionario
        where baixa.dtcadastro >= '".date('Y-m-d')." 00:00:00' and baixa.codempresa = '{$_SESSION['codempresa']}'";
        $resBaixa = $conexao->comando($sql);
        $qtdBaixa = $conexao->qtdResultado($resBaixa);
        if($qtdBaixa > 0){
            $tabela_baixa .= "<table style='width: 535px;' class='tabela_formulario'";
            $tabela_baixa .= "<tr>";
            $tabela_baixa .= "<td>Operador</td>";
            $tabela_baixa .= "<td>Valor</td>";
            $tabela_baixa .= "</tr>";
            while($baixa = $conexao->resultadoArray($resBaixa)){
                $tabela_baixa .= "<tr>";
                $tabela_baixa .= "<td>{$baixa["funcionario"]}</td>";
                $tabela_baixa .= "<td>".  number_format($baixa["valor"], 2, ",", ".")."</td>";
                $tabela_baixa .= "</tr>";
            }
            $tabela_baixa .= "</table>";
        }else{
            $frase           = str_replace("[vendas_dia]", "==nada registrado==", $frase);
        }
        $frase           = str_replace("[vendas_dia]", $tabela_baixa, $frase);
    }
    if(strpos($frase, "[tabela_diario]")){
        $sql = "select * from pessoa 
        where codempresa = '{$_SESSION['codempresa']}' 
        and codcategoria not in(1,6) 
        and codpessoa in(select codfuncionario from metafuncionario) and pessoa.status = 'a'";
        $resPessoa = $conexao->comando($sql);
        $qtdPessoa = $conexao->qtdResultado($resPessoa);
        if($qtdPessoa > 0){
            $tabela_funcionario .= "<table style='width: 535px;' class='tabela_formulario'>";
            $tabela_funcionario .= "<tr>";
            $tabela_funcionario .= "<td>Operador</td>";
            $tabela_funcionario .= "<td>Meta</td>";
            $tabela_funcionario .= "<td>Vendido</td>";
            $tabela_funcionario .= "<td>Objetivo Diário</td>";
            $tabela_funcionario .= "</tr>";            
            while($pessoa = $conexao->resultadoArray($resPessoa)){
                $tabela_funcionario .= "<tr>";
                $tabela_funcionario .= "<td>{$pessoa["nome"]}</td>";
                
                $metaFuncionario = $conexao->comandoArray("select valor from metafuncionario 
                where codfuncionario = '{$pessoa["codpessoa"]}'
                and dtcadastro >= '" . date("Y-m-") . "01 00:00:00'    
                and dtcadastro <= '" . date("Y-m-") . "30 23:59:59'");
                
                $tabela_funcionario .= "<td>".  number_format($metaFuncionario["valor"], 2, ",", ".")."</td>";

                $baixaFuncionario = $conexao->comandoArray("select sum(valor) as valor from baixa 
                where codfuncionario = '{$pessoa["codpessoa"]}'
                and codempresa = '{$_SESSION['codempresa']}'    
                and dtcadastro >= '" . date("Y-m-") . "01 00:00:00'    
                and dtcadastro <= '" . date("Y-m-") . "30 23:59:59'");   
                $total_vendas += $baixaFuncionario["valor"];
                $tabela_funcionario .= "<td>".  number_format($baixaFuncionario["valor"], 2, ",", ".")."</td>";
                $tabela_funcionario .= "<td>".  number_format(calculaMetaFuncionario($pessoa["codpessoa"]), 2, ",", ".")."</td>";
                $tabela_funcionario .= "</tr>";
            }
            $tabela_funcionario .= "</table>";
        }
        $frase           = str_replace("[tabela_diario]", $tabela_funcionario, $frase);
    }
    $frase           = str_replace("[soma_operador]", "R$ " . number_format($metaFuncionario1["valor"], 2, ",", "."), $frase);
    $frase           = str_replace("[meta_falta]", "R$ " . number_format($metaFuncionario1["valor"] - $total_vendas, 2, ",", "."), $frase);
    $frase           = str_replace("\n", "<br>", $frase);
    return $frase;
}

function definePeriodo(){
    $periodo = date('H:i:s');
    if(strtotime($periodo) >= strtotime(date("07:00:00")) && strtotime($periodo) <= strtotime(date("12:00:00"))){
        $periodo = "1";
    }elseif(strtotime($periodo) >= strtotime(date("12:00:01")) && strtotime($periodo) <= strtotime(date("18:00:00"))){
        $periodo = "2";
    }elseif(strtotime($periodo) >= strtotime(date("18:00:01")) && strtotime($periodo) <= strtotime(date("23:59:59"))){
        $periodo = "3";
    }
    return $periodo;
}


function calculaMetaFuncionario($codfuncionario = null) {
    global $conexao;
    if($codfuncionario == NULL){
        $codfuncionario = $_SESSION['codpessoa'];
    }
    /*     * pegando o valor total da meta do funcionário */
    $sql = "select valor from metafuncionario where codfuncionario = '{$codfuncionario}' 
    and dtcadastro >= '" . date("Y-m-") . "01 00:00:00'    
    and dtcadastro <= '" . date("Y-m-") . "30 23:59:59'            
    order by codmeta desc";
    $metaFuncionario = $conexao->comandoArray($sql);
    $diasUteis = 0;

    if (isset($metaFuncionario) && $metaFuncionario["valor"] != NULL && $metaFuncionario["valor"] != "") {

        /*         * somatório valor total vendido */
        $baixaTotal = $conexao->comandoArray("select sum(valor) as valor from baixa 
        where codempresa = '{$_SESSION['codempresa']}' 
        and dtcadastro >= '" . date("Y-m-") . "01'    
        and dtcadastro <= '" . date("Y-m-") . "30'    
        and codfuncionario = '{$codfuncionario}'");

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
            $sql = "select * from horariofilial where codempresa = '{$_SESSION['codempresa']}' and dia = '{$semana[$dia_semana]}'";
            $horarioFilial = $conexao->comandoArray($sql);
            if (isset($horarioFilial["codhorario"]) && $horarioFilial["codhorario"] != NULL && $horarioFilial["codhorario"] != "") {
                $diasUteis++;
            }
        }

        if ($diasUteis == 0) {
            $resultadoFinal = 0;
        } else {
            $resultadoFinal = ($metaFuncionario["valor"] - $baixaTotal["valor"]) / $diasUteis;
        }
    }

    return $resultadoFinal;
}