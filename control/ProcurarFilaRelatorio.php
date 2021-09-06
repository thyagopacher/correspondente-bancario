<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
include "../model/Conexao.php";
$conexao = new Conexao();
$and = "";
if (isset($_POST["assunto"]) && $_POST["assunto"] != NULL && $_POST["assunto"] != "") {
    $and .= " and filaemail.assunto like '%{$_POST["assunto"]}%'";
}
if (isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != "") {
    $and .= " and filaemail.dtcadastro >= '{$_POST["data1"]}'";
}
if (isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != "") {
    $and .= " and filaemail.dtcadastro <= '{$_POST["data2"]}'";
}

if (isset($_POST["codempresa"]) && $_POST["codempresa"] != NULL && $_POST["codempresa"] != "") {
    $and .= " and filaemail.codempresa = '{$_POST["codempresa"]}'";
} else {
    $and .= " and filaemail.codempresa = '{$_SESSION['codempresa']}'";
}
$sql = "select filaemail.*,DATE_FORMAT(filaemail.dtcadastro, '%d/%m/%Y') as dtcadastro2, funcionario.nome as funcionario,
        morador.nome as morador, morador.email 
        from filaemail
        inner join pessoa as funcionario on funcionario.codpessoa = filaemail.codfuncionario and funcionario.codempresa = filaemail.codempresa
        inner join pessoa as morador on morador.codpessoa = filaemail.codpessoa and morador.codempresa = filaemail.codempresa
        where 1 = 1 {$and}";
$res = $conexao->comando($sql);
$qtd = $conexao->qtdResultado($res);
if ($qtd > 0) {
    $nome  = "Relatório E-mails na fila";
    $html  = '<table class="responstable">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Dt. Cadastro</th>';
    $html .= '<th>Assunto</th>';
    $html .= '<th>Para</th>';
    $html .= '<th>E-mail</th>';
    $html .= '<th>Enviado</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    while ($filaemail = $conexao->resultadoArray($res)) {
        $html .= '<tr>';
        $html .= '<td>' . $filaemail["dtcadastro2"] . '</td>';
        $html .= '<td>' . $filaemail["assunto"] . '</td>';
        $html .= '<td>' . $filaemail["morador"] . '</td>';
        $html .= '<td><a href="mailto:' . $filaemail["email"] . '">' . $filaemail["email"] . '</a></td>';
        $html .= '<td>' . $filaemail["situacao"] . '</td>';
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
} else {
    echo '<script>alert("Sem fila encontrada!");window.close();</script>';
}
