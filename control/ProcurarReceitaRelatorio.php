<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    
    $and  = "";
    $and2 = "";
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
        $and  .= " and reserva.data >= '{$_POST["data1"]}'";
        $and2 .= " and conta.data >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
        $and  .= " and reserva.data <= '{$_POST["data2"]}'";
        $and2 .= " and conta.data <= '{$_POST["data2"]}'";
    }

    $and .= " and reserva.codempresa = '{$_SESSION['codempresa']}'";
    $and2 .= " and conta.codempresa = '{$_SESSION['codempresa']}'";
    $sql = "select 
    ambiente.valor, DATE_FORMAT(reserva.data, '%d/%m/%Y') as data2, ambiente.nome as de
    from reserva
    inner join ambiente on ambiente.codambiente = reserva.codambiente and ambiente.codempresa = reserva.codempresa
    where 1 = 1 {$and}
    union
    select conta.valor, DATE_FORMAT(conta.data, '%d/%m/%Y') as data2, conta.nome as de
    from conta
    where movimentacao = 'R' {$and2}";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $totalReceita = 0.0;
        $nome = "Receita do Condominio - {$qtd} resultados";
        $html = "";
        $html .=  '<table class="responstable">';
        $html .=  '<thead>';
        $html .=  '<tr>';
        $html .=  '<th>Data</th>';
        $html .=  '<th>Valor</th>';
        $html .=  '<th>De</th>';
        $html .=  '</tr>';
        $html .=  '</thead>';
        $html .=  '<tbody>';
        while($reserva = $conexao->resultadoArray($res)){
            $totalReceita += $reserva["valor"];
            $html .=  '<tr>';
            $html .=  '<td>'.$reserva["data2"].'</td>';
            $html .=  '<td>'.number_format($reserva["valor"], 2, ",", ".").'</td>';
            $html .=  '<td>'.$reserva["de"].'</td>';
            $html .=  '</tr>';
        }
        $html .=  '</tbody>';
        $html .=  '</table>';
        
        $html .=  'Total R$ '. number_format($totalReceita, 2, ",", ".").'</td>';
        
        $_POST["html"] = $html;
        $paisagem = "sim";        
         
        if(isset($_POST["tipo"]) && $_POST["tipo"] == "xls"){
            include "./GeraExcel.php";
        }else{
            include "./GeraPdf.php";
        }         
    }else{
        echo '<script>alert("Sem receita encontrada!");window.close();</script>';
    }

