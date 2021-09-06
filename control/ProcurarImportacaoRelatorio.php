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
        $and .= " and importacao.data >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
        $and .= " and importacao.data <= '{$_POST["data2"]}'";
    }
    $sql = "select codimportacao, codfuncionario, qtdimportado, qtdnimportado, DATE_FORMAT(data, '%d/%m/%Y') as data, pessoa.nome as funcionario 
    from importacao 
    inner join empresa on empresa.codempresa = importacao.codempresa
    inner join pessoa on pessoa.codpessoa = importacao.codfuncionario and pessoa.codempresa = importacao.codempresa
    where 1 = 1  {$and} and importacao.codempresa = '{$_SESSION['codempresa']}'";
    $res = $conexao->comando($sql);
    if($res == FALSE){
        die("Erro ocasionado por:". mysqli_error($conexao->conexao));
    }
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $nome  = "Relatório de Importação Padrão";
        $html .= '<table class="responstable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Data</th>';
        $html .= '<th>Funcionário</th>';
        $html .= '<th>Qtd. Importado</th>';
        $html .= '<th>Qtd. não Importado</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        while($importacao = $conexao->resultadoArray($res)){
            $html .= '<tr>';
            $html .= '<td>'.$importacao["data"].'</td>';
            $html .= '<td>'.$importacao["funcionario"].'</td>';
            $html .= '<td>'.$importacao["qtdimportado"].'</td>';
            $html .= '<td>'.$importacao["qtdnimportado"].'</td>';
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
        echo '<script>alert("Sem importação encontrada!");window.close();</script>';
    }

