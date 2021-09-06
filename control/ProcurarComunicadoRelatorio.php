<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/Manual.php";
    $conexao = new Conexao();
    $comunicado  = new Manual($conexao);
    
    $and     = "";
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and comunicado.nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["data"]) && $_POST["data"] != NULL){
        $and .= " and comunicado.dtcadastro >= '{$_POST["data"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL){
        $and .= " and comunicado.dtcadastro <= '{$_POST["data2"]}'";
    }
    $sql = "select codcomunicado, comunicado.nome, DATE_FORMAT(comunicado.dtcadastro, '%d/%m/%Y') as dtcadastro2, comunicado.arquivo
    from comunicado
    where 1 = 1
    {$and} order by comunicado.dtcadastro desc";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $nome  = 'Rel comunicado';
        $html  = '<table class="responstable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Nome</th>';
        $html .= '<th>Dt. Cadastro</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        while($documento = $conexao->resultadoArray($res)){
            $html .= '<tr>';
            $html .= '<td style="text-align: left;">'.$documento["nome"].'</td>';
            $html .= '<td style="text-align: left;">'.$documento["dtcadastro2"].'</td>';
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
        echo '<script>alert("Sem comunicado encontrado!");window.close();</script>';
    }
    