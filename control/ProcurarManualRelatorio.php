<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/Manual.php";
    $conexao = new Conexao();
    $manual  = new Manual($conexao);
    
    $and     = "";
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and manual.nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["data"]) && $_POST["data"] != NULL){
        $and .= " and manual.dtcadastro >= '{$_POST["data"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL){
        $and .= " and manual.dtcadastro <= '{$_POST["data2"]}'";
    }
    $sql = "select codmanual, manual.nome, DATE_FORMAT(manual.dtcadastro, '%d/%m/%Y') as dtcadastro2, manual.arquivo, banco.nome as banco
    from manual
    inner join banco on banco.codbanco = manual.codbanco
    where 1 = 1
    {$and} order by manual.dtcadastro desc";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $nome  = 'Rel manual';
        $html  = '<table class="responstable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Nome</th>';
        $html .= '<th>Dt. Cadastro</th>';
        $html .= '<th>Banco</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        while($documento = $conexao->resultadoArray($res)){
            $html .= '<tr>';
            $html .= '<td style="text-align: left;">'.$documento["nome"].'</td>';
            $html .= '<td style="text-align: left;">'.$documento["dtcadastro2"].'</td>';
            $html .= '<td style="text-align: left;">'.$documento["banco"].'</td>';
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
    