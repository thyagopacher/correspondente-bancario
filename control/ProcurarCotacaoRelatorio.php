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
    if(isset($_POST["nome"])){
        $and .= " and cotacao.nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["dtcadastro"]) && $_POST["dtcadastro"] != NULL){
        $and .= " and cotacao.dtcadastro >= '{$_POST["dtcadastro"]}'";
    }
    if(isset($_POST["dtcadastro2"]) && $_POST["dtcadastro2"] != NULL){
        $and .= " and cotacao.dtcadastro <= '{$_POST["dtcadastro2"]}'";
    }
    if(isset($_POST["movimentacao"]) && $_POST["movimentacao"] != "R"){
        $and .= " and cotacao.movimentacao = '{$_POST["movimentacao"]}'";
    }
    if(isset($_POST["codtipo"]) && $_POST["codtipo"] != NULL && $_POST["codtipo"] != ""){
        $and .= " and cotacao.codtipo = '{$_POST["codtipo"]}'";
    }
    if(isset($_POST['valor']) && $_POST['valor'] != NULL && $_POST['valor'] != ""){
        $and .= " and cotacao.valor = '{$_POST['valor']}'";
    }
    if(isset($_POST["codempresa"]) && $_POST["codempresa"] != NULL && $_POST["codempresa"] != ""){
        $and .= " and cotacao.codempresa = '{$_POST["codempresa"]}'";
    }else{
        $and .= " and cotacao.codempresa = '{$_SESSION['codempresa']}'";
    }
     
    $sql = "select cotacao.nome, cotacao.descricao, DATE_FORMAT(cotacao.dtcadastro, '%d/%m/%Y') as dtcadastro2, pessoa.nome as funcionario, cotacao.arquivo
    from cotacao 
    inner join pessoa on pessoa.codpessoa = cotacao.codfuncionario and pessoa.codempresa = cotacao.codempresa
    where 1 = 1 {$and} order by cotacao.codcotacao";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $nome  = "Relatório de Cotação";
        $html  = '<table class="responstable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Dt. Cadastro</th>';
        $html .= '<th>Por</th>';
        $html .= '<th>Nome</th>';
        $html .= '<th>Descrição</th>';
        if (isset($_POST["tipo"]) && $_POST["tipo"] == "xls") {
            $rescampo = $conexao->comando("select * from campoextra where codpagina = '{$_POST["codpagina"]}' and codempresa = '{$_SESSION['codempresa']}'");
            $qtdcampo = $conexao->qtdResultado($rescampo);
            if ($qtdcampo > 0) {
                while ($campo = $conexao->resultadoArray($rescampo)) {
                    $html .= '<th>' . $campo["titulo"] . '</th>';
                }
            }
        }        
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        while($cotacao = $conexao->resultadoArray($res)){
            $html .= '<tr>';
            $html .= '<td style="text-align: left;">'.$cotacao["dtcadastro2"].'</td>';
            $html .= '<td style="text-align: left;">'.$cotacao["funcionario"].'</td>';
            $html .= '<td style="text-align: left;">'.$cotacao["nome"].'</td>';
            $html .= '<td style="text-align: left;">'.$cotacao["descricao"].'</td>';
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
        echo '<script>alert("Sem cotação encontrada!");window.close();</script>';
    }