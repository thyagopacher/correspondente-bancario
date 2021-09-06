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
        echo 'Encontrou ',$qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th style="width: 600px;">DOCUMENTOS PARA PROPOSTA</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($documento = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td style="text-align: left;">';
            echo 'Nome:', $documento["nome"], ' - Dt. Cadastro: ',$documento["dtcadastro2"],'<br>';
            echo 'Funcionário:', $documento["funcionario"], ' - Banco:', $documento["banco"];
            echo '</td>';
            echo '<td>';
            echo '<a href="Documento.php?coddocumento=',$documento["coddocumento"],'" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '<a href="#" onclick="excluirDocumento(',$documento["coddocumento"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }else{
        echo '';
    }
    
    include "../model/Log.php";
    $log = new Log($conexao);
    
    
    $log->acao       = "procurar";
    $log->observacao = "Procurar documento - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();     