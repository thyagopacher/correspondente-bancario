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
        $and .= " and tabelap.nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL){
        $and .= " and tabelap.dtcadastro >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL){
        $and .= " and tabelap.dtcadastro <= '{$_POST["data2"]}'";
    }
    $sql = "select tabela.nome as tabela, tabelap.prazode, tabelap.prazoate, tabelap.codtabelap, DATE_FORMAT(tabelap.dtcadastro, '%d/%m/%Y') as dtcadastro2, tabelap.codtabela, tabelap.comissao
    from tabelaprazo as tabelap
    inner join tabela on tabela.codtabela = tabelap.codtabela
    where 1 = 1 and tabela.codempresa = {$_SESSION["codempresa"]}
    {$and} order by tabelap.dtcadastro desc";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        echo 'Encontrou ',$qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th style="width: 600px;">TABELA PRAZO</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($tabelap = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td style="text-align: left;">';
            echo 'Tabela:', $tabelap["tabela"], ' - Dt. Cadastro: ',$tabelap["dtcadastro2"],'<br>';
            echo 'De:', $tabelap["prazode"] , ' - Até: ', $tabelap["prazoate"], '<br>';
            echo 'Valor:', number_format($tabelap["comissao"], 2, ",", ".");
            echo '</td>';
            echo '<td>';
            $comissao = str_replace(",", ".", $tabelap["comissao"]);
            $arrayJavascript = "new Array('{$tabelap["codtabelap"]}', '{$tabelap["prazode"]}', '{$tabelap["prazoate"]}', '{$tabelap["codtabela"]}', '{$comissao}')";
            echo '<a href="javascript: setaEditarTabelap(',$arrayJavascript,')" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '<a href="#" onclick="excluirTabelaPrazo(',$tabelap["codtabelap"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }else{
        echo '';
    }
    