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
        $and .= " and banco.nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL){
        $and .= " and banco.dtcadastro >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL){
        $and .= " and banco.dtcadastro <= '{$_POST["data2"]}'";
    }
    $sql = "select 
    codbanco, DATE_FORMAT(banco.dtcadastro, '%d/%m/%Y') as dtcadastro2, banco.numbanco, banco.site, banco.nome
    from banco
    where 1 = 1
    {$and} order by banco.dtcadastro desc";

    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $nome  = 'Rel banco';
        $html  = '<table class="responstable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Nome</th>';
        $html .= '<th>Num Banco</th>';
        $html .= '<th>Dt. Cadastro</th>';
        $html .= '<th>Site</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        while($banco = $conexao->resultadoArray($res)){
            $html .= '<tr>';
            $html .= '<td style="text-align: left;">'.$banco["nome"].'</td>';
            $html .= '<td style="text-align: left;">'.$banco["numbanco"].'</td>';
            $html .= '<td style="text-align: left;">'.$banco["dtcadastro2"].'</td>';
            $html .= '<td style="text-align: left;">'.$banco["site"].'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        
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
    