<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $and     = "";
    if(isset($_POST["codpessoa"]) && $_POST["codpessoa"] != NULL && $_POST["codpessoa"] != ""){
        $and .= " and arquivopessoa.codpessoa = '{$_POST["codpessoa"]}'";
    }

    $and .= " and arquivopessoa.codempresa = '{$_SESSION['codempresa']}'";
    $sql = "select arquivopessoa.codarquivo, empresa.razao, DATE_FORMAT(arquivopessoa.dtcadastro, '%d/%m/%y') as dtcadastro2, arquivopessoa.nome, arquivopessoa.link, pessoa.nome as morador
        from arquivopessoa
        inner join pessoa on pessoa.codpessoa = arquivopessoa.codpessoa and pessoa.codempresa = arquivopessoa.codempresa
        inner join empresa on empresa.codempresa = arquivopessoa.codempresa
        where 1 = 1 {$and} order by arquivopessoa.dtcadastro desc";
    $res = $conexao->comando($sql) or die("<pre>$sql</pre>");
    $qtd = $conexao->qtdResultado($res);
     
    if($qtd > 0){
        echo 'Encontrou ', $qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th style="width: 50px;">Data</th>';
        echo '<th>Nome</th>';
        echo '<th style="width: 50px;">Link</th>';
        echo '<th style="width: 50px;">Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($arquivo = $conexao->resultadoArray($res)){
            echo '<tr id="',$arquivo["codarquivo"],'">';
            echo '<td>',$arquivo["dtcadastro2"],'</td>';
            echo '<td>',$arquivo["nome"],'</td>';
            echo '<td><a target="_blank" href="../arquivos/',$arquivo["link"],'">Download</a></td>';
            echo '<td>';
            echo '<a href="#" onclick="excluirArquivoPessoa(',$arquivo["codarquivo"],')" title="Clique aqui para excluir o arquivo"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
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
        $log->observacao = "Procurado arquivo pessoa - em ". date('d/m/Y'). " - ". date('H:i');
        $log->codpagina  = "0";
        
        $log->hora = date('H:i:s');
        $log->inserir();    