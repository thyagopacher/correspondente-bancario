<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/Dia.php";
    $conexao = new Conexao();
    $dia  = new Dia($conexao);
    
    $and     = "";
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and dia.nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL){
        $and .= " and dia.dtcadastro >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL){
        $and .= " and dia.dtcadastro <= '{$_POST["data2"]}'";
    }
    $sql = "select 
    coddia, DATE_FORMAT(dia.dtcadastro, '%d/%m/%Y') as dtcadastro2, pessoa.nome as funcionario, DATE_FORMAT(dia.data, '%d/%m/%Y') as data2
    from dia
    inner join pessoa on pessoa.codpessoa = dia.codfuncionario
    where 1 = 1
    {$and} order by dia.dtcadastro desc";

    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $nome  = 'Rel feriados';
        $html  = '<table class="responstable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Data</th>';
        $html .= '<th>Dt. Cadastro</th>';
        $html .= '<th>Funcionário</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        while($dia = $conexao->resultadoArray($res)){
            $html .= '<tr>';
            $html .= '<td style="text-align: left;">'.$dia["data2"].'</td>';
            $html .= '<td style="text-align: left;">'.$dia["dtcadastro2"].'</td>';
            $html .= '<td style="text-align: left;">'.$dia["funcionario"].'</td>';
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
        echo '<script>alert("Sem feriado encontrado!");window.close();</script>';
    }
    