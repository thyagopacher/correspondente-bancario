<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $and     = "";
    if(isset($_POST["codstatus"]) && $_POST["codstatus"] != NULL && $_POST["codstatus"] != ""){
        $and .= " and empresa.codstatus = '{$_POST["codstatus"]}'";
    }
    if(isset($_POST["codramo"]) && $_POST["codramo"] != NULL && $_POST["codramo"] != ""){
        $and .= " and empresa.codramo = '{$_POST["codramo"]}'";
    }
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and empresa.razao like '%".trim($_POST["nome"])."%'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
        $and .= " and empresa.dtcadastro >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
        $and .= " and empresa.dtcadastro <= '{$_POST["data2"]}'";
    }
    if(isset($_POST["fornecedor"]) && $_POST["fornecedor"] != NULL && $_POST["fornecedor"] == "true"){
        $and .= " and empresa.codramo <> '7' and nomecontato <> ''";
    }elseif($_SESSION["codnivel"] != 1){
        $and .= " and empresa.codempresa = '{$_SESSION['codempresa']}' and codramo = 7";
    }
    
    if(isset($_SESSION["codnivel"]) && $_SESSION["codnivel"] != NULL && $_SESSION["codnivel"] != "1"){
        $and .= " and empresa.codpessoa in(select codpessoa from pessoa where pessoa.codempresa = '{$_SESSION['codempresa']}')";
    }
    $sql = "select empresa.codempresa, empresa.razao, empresa.telefone, empresa.celular, 
    empresa.email, DATE_FORMAT(empresa.dtcadastro, '%d/%m/%Y') as data, status.nome as status, pessoa.nome as funcionario
    from empresa 
    inner join statusempresa as status on status.codstatus = empresa.codstatus
    inner join pessoa on pessoa.codpessoa = empresa.codpessoa
    where 1 = 1 {$and}";
    $res = $conexao->comando($sql);
    if($res == FALSE){
        die("Erro ocasionado por:". mysqli_error($conexao->conexao));
    }
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $nome  = 'Rel empresa';
        $html  = '<table class="responstable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Nome</th>';
        $html .= '<th>Dt. Cadastro</th>';
        $html .= '<th>E-mail</th>';
        $html .= '<th>Por</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        while($empresa = $conexao->resultadoArray($res)){
            $html .= '<tr>';
            $html .= '<td style="text-align: left;">'.$empresa["razao"].'</td>';
            $html .= '<td style="text-align: left;">'.$empresa["data"].'</td>';
            $html .= '<td style="text-align: left;">'.$empresa["email"].'</td>';
            $html .= '<td style="text-align: left;">'.$empresa["funcionario"].'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        
        $_POST["html"] = $html;
        $paisagem = "sim";        
        
        
        if(isset($_POST["tipoRel"]) && $_POST["tipoRel"] == "xls"){
            include "./GeraExcel.php";
        }else{
            include "./GeraPdf.php";
        }         
    }else{
        echo '<script>alert("Sem empresa encontrada!");window.close();</script>';
    }
    