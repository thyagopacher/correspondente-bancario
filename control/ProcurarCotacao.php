<?php
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
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>CONTROLE DE COTAÇÃO</th>';
        echo '<th class="nrelatorio">Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($cotacao = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td style="text-align: left;">';
            echo 'Dt. Cadastro: ', $cotacao["dtcadastro2"], ' - Por:', $cotacao["funcionario"], '<br>';
            echo 'Nome:', $cotacao["nome"], '<br>';
            if(isset($cotacao["arquivo"]) && $cotacao["arquivo"] != NULL && $cotacao["arquivo"] != ""){
                echo 'Arquivo: <a href="../arquivos/', $cotacao["arquivo"], '" target="_blank">Link</a><br>';
            }
            echo 'Descrição:', $cotacao["descricao"];
            echo '</td>';
            $arrayJavascript = "new Array('{$cotacao["codcotacao"]}', '{$cotacao["nome"]}', '{$cotacao["descricao"]}')";
            echo '<td>';
            echo '<a href="javascript: setarCotacao(',$arrayJavascript,')" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '<a href="#" onclick="excluirCotacao(',$cotacao["codcotacao"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
            echo '<a href="#" onclick="enviarCotacao(',$academia["codacademia"],')" title="Clique aqui para enviar"><img style="width: 40px;" src="../visao/recursos/img/correspondencia.png" alt="botão enviar"/></a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }else{
        echo '0';
    }
    

    include "../model/Log.php";
    $log = new Log($conexao);
    
    
    $log->acao       = "procurar";
    $log->observacao = "Procurado cotação - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();         