<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
try {
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
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Dt. Cadastro</th>';
        echo '<th>Assunto</th>';
        echo '<th>Para</th>';
        echo '<th>E-mail</th>';
        echo '<th>Enviado</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while ($filaemail = $conexao->resultadoArray($res)) {
            echo '<tr>';
            echo '<td>', $filaemail["dtcadastro2"], '</td>';
            echo '<td>', $filaemail["assunto"], '</td>';
            echo '<td>', $filaemail["morador"], '</td>';
            echo '<td><a href="mailto:', $filaemail["email"], '">', $filaemail["email"], '</a></td>';
            echo '<td>', $filaemail["situacao"], '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '0';
    }
} catch (Exception $ex) {
    echo 'Erro ocasionado por:', $ex;
}