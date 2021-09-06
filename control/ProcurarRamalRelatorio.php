<?php
    header('Content-Type: text/html; charset=utf-8');
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $and     = "";
    
    if(isset($_POST["ramal"]) && $_POST["ramal"] != NULL && $_POST["ramal"] != ""){
        $and .= " and ramal like '%{$_POST["ramal"]}%'";
    }
    if(isset($_POST["externo"]) && $_POST["externo"] != NULL && $_POST["externo"] != ""){
        $and .= " and externo = '{$_POST["externo"]}'";
    }
    if(isset($_POST["telefone"]) && $_POST["telefone"] != NULL && $_POST["telefone"] != ""){
        $and .= " and telefone like '%{$_POST["telefone"]}%'";
    }
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["codempresa"]) && $_POST["codempresa"] != NULL && $_POST["codempresa"] != ""){
        $and .= " and (codempresa = '{$_POST["codempresa"]}' or externo = 's')";
    }else{
        $and .= " and codempresa = '{$_SESSION['codempresa']}'";
    }    
    $sql = "select * from ramal where 1 = 1 {$and} order by nome";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $nome  = "Relatório Telef. Úteis";
        $html .= '<table class="responstable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Nome</th>';
        $html .= '<th>Ramal</th>';
        $html .= '<th>Telefone</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        while($ramal = $conexao->resultadoArray($res)){
            $html .= '<tr>';
            $html .= '<td>'.$ramal["nome"].'</td>';
            $html .= '<td>'.$ramal["ramal"].'</td>';
            $html .= '<td>'.$ramal["telefone"].'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        
        $_POST["html"] = $html;
        $paisagem = "sim";   
        //echo $html;
        
        if(isset($_POST["tipo"]) && $_POST["tipo"] == "xls"){
            include "./GeraExcel.php";
        }else{
            include "./GeraPdf.php";
        }         
    }else{
        echo '<script>alert("Sem telefone útil encontrado!");window.close();</script>';
    }

