<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/Documento.php";
    $conexao = new Conexao();
    $documento  = new Documento($conexao);
    
    $and     = "";
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and documento.nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL){
        $and .= " and documento.dtcadastro >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL){
        $and .= " and documento.dtcadastro <= '{$_POST["data2"]}'";
    }
    $sql = "select 
    coddocumento, DATE_FORMAT(documento.dtcadastro, '%d/%m/%Y') as dtcadastro2, pessoa.nome as funcionario, documento.nome, banco.nome as banco
    from documento
    inner join pessoa on pessoa.codpessoa = documento.codfuncionario
    left join banco on banco.codbanco = documento.codbanco
    where 1 = 1
    {$and} order by documento.dtcadastro desc";

    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $nome  = 'Rel documentação';
        $html  = '<table class="responstable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Nome</th>';
        $html .= '<th>Dt. Cadastro</th>';
        $html .= '<th>Funcionário</th>';
        $html .= '<th>Banco</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        while($documento = $conexao->resultadoArray($res)){
            $html .= '<tr>';
            $html .= '<td style="text-align: left;">'.$documento["nome"].'</td>';
            $html .= '<td style="text-align: left;">'.$documento["dtcadastro2"].'</td>';
            $html .= '<td style="text-align: left;">'.$documento["funcionario"].'</td>';
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
    