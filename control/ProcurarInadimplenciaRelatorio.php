<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    
    $and     = "";
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and empresa.razao like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
        $and .= " and inadimplencia.dtpagamento >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
        $and .= " and inadimplencia.dtpagamento <= '{$_POST["data2"]}'";
    }
    if(isset($_POST["codempresa"]) && $_POST["codempresa"] != NULL && $_POST["codempresa"] != ""){
        $and .= " and inadimplencia.codempresa = '{$_POST["codempresa"]}'";
    }else{
        $and .= " and inadimplencia.codempresa = '{$_SESSION['codempresa']}'";
    }
    $sql = "select inadimplencia.`bloco`, inadimplencia.`apartamento`, inadimplencia.`cotacondominio`, inadimplencia.`fundoreserva`, inadimplencia.`rateioagua`, 
    inadimplencia.`txextra1`, inadimplencia.`txextra2`, inadimplencia.`juro`, inadimplencia.`multa`, DATE_FORMAT(inadimplencia.dtvencimento, '%d/%m/%y') as dtvencimento2,
    DATE_FORMAT(inadimplencia.dtpagamento, '%d/%m/%y') as dtpagamento2, DATE_FORMAT(inadimplencia.dtcadastro, '%d/%m/%y %H:%i') as dtcadastro2,
    pessoa.nome as funcionario
    from inadimplencia 
    inner join empresa on empresa.codempresa = inadimplencia.codempresa
    inner join pessoa on pessoa.codpessoa = inadimplencia.codfuncionario and pessoa.codempresa = inadimplencia.codempresa
    where 1 = 1 {$and}";
    $res = $conexao->comando($sql)or die("<pre>$sql</pre>");
    if($res == FALSE){
        die("Erro ocasionado por:". mysqli_error($conexao->conexao));
    }
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $nome = "Relatório de Inadimplência";
        $html .= '<table class="responstable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Bl.</th>';
        $html .= '<th>Apto.</th>';
        $html .= '<th>Dt.Pgto.</th>';
        $html .= '<th>Dt.Venc.</th>';
        $html .= '<th>Cota Cond.</th>';
        $html .= '<th>Fundo</th>';
        $html .= '<th>Rateio</th>';
        $html .= '<th>Multa</th>';
        $html .= '<th>Tx. extra1</th>';
        $html .= '<th>Tx. extra2</th>';
        $html .= '<th>Juros</th>';
        $html .= '<th>Total</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        while($inadimplencia = $conexao->resultadoArray($res)){
            $total = $inadimplencia["cotacondominio"] + $inadimplencia["fundoreserva"] + $inadimplencia["rateioagua"] + $inadimplencia["multa"] + $inadimplencia["txextra1"] + $inadimplencia["txextra2"] + $inadimplencia["juro"];
            $html .= '<tr>';
            $html .= '<td style="text-align: left;">'.$inadimplencia["bloco"].'</td>';   
            $html .= '<td style="text-align: left;">'.$inadimplencia["apartamento"].'</td>';   
            $html .= '<td style="text-align: left;">'.$inadimplencia["dtpagamento2"].'</td>';   
            $html .= '<td style="text-align: left;">'.$inadimplencia["dtvencimento2"].'</td>';   
            $html .= '<td style="text-align: right;">'.number_format($inadimplencia["cotacondominio"], 2, ",", "").'</td>';   
            $html .= '<td style="text-align: right;">'.number_format($inadimplencia["fundoreserva"], 2, ",", "").'</td>';   
            $html .= '<td style="text-align: right;">'.number_format($inadimplencia["rateioagua"], 2, ",", "").'</td>';   
            $html .= '<td style="text-align: right;">'.number_format($inadimplencia["multa"], 2, ",", "").'</td>';   
            $html .= '<td style="text-align: right;">'.number_format($inadimplencia["txextra1"], 2, ",", "").'</td>';   
            $html .= '<td style="text-align: right;">'.number_format($inadimplencia["txextra2"], 2, ",", "").'</td>';   
            $html .= '<td style="text-align: right;">'.number_format($inadimplencia["juros"], 2, ",", "").'</td>';   
            $html .= '<td style="text-align: right;">'.number_format($total, 2, ",", "").'</td>';   
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';

        $paisagem = "sim";        
        $_POST["html"] = $html;
        
        if(isset($_POST["tipo"]) && $_POST["tipo"] == "xls"){     
            include "./GeraExcel.php";
        }else{
            include "./GeraPdf.php";
        }    

    }else{
        echo '<script>alert("Sem pessoa encontrada!");window.close();</script>';
    }

