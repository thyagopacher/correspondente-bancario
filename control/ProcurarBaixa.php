<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/Baixa.php";
    $conexao = new Conexao();
    $baixa  = new Baixa($conexao);
    
    $and     = "";
    if(isset($_POST["cpf"]) && $_POST["cpf"] != NULL && $_POST["cpf"] != ""){
        $and .= " and baixa.cpf like '%{$_POST["cpf"]}%'";
    }
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and baixa.nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL){
        $and .= " and baixa.dtcadastro >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL){
        $and .= " and baixa.dtcadastro <= '{$_POST["data2"]}'";
    }
    if(isset($_POST["codfuncionario"]) && $_POST["codfuncionario"] != NULL){
        $and .= " and baixa.codfuncionario = '{$_POST["codfuncionario"]}'";
    }
    $sql = "select * from nivel where codnivel = '{$_SESSION["codnivel"]}'";
    $nivel_logado = $conexao->comandoArray($sql);
    if(isset($nivel_logado["nome"]) && $nivel_logado["nome"] == "OPERADOR"){
        $and .= " and baixa.codfuncionario = '{$_SESSION['codpessoa']}'";
    }
    $sql = "select baixa.codbaixa, baixa.cpf, baixa.valor, DATE_FORMAT(baixa.dtcadastro, '%d/%m/%Y') as dtcadastro2, pessoa.nome as funcionario, baixa.codempresa, DATE_FORMAT(baixa.dtcadastro, '%Y-%m-%d') as dtcadastro, baixa.codfuncionario
    from baixa
    inner join pessoa on pessoa.codpessoa = baixa.codfuncionario
    where 1 = 1
    {$and} order by baixa.dtcadastro desc";

    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $linha = 0;
        echo 'Encontrou ',$qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th style="width: 600px;">CADASTRO BAIXA</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($baixa = $conexao->resultadoArray($res)){
            $sql = "select valor from metafuncionario where codfuncionario = '{$baixa["codfuncionario"]}' 
            and dtcadastro >= '" . date("Y-m-") . "01 00:00:00'    
            and dtcadastro <= '" . date("Y-m-") . "30 23:59:59'            
            order by codmeta desc";
            $metaFuncionario = $conexao->comandoArray($sql);            
            
            $sql = "select sum(baixa.valor) as valor
            from baixa 
            inner join pessoa on pessoa.codpessoa = baixa.codfuncionario
            where baixa.codfuncionario = '{$baixa["codfuncionario"]}' 
            and baixa.dtcadastro >= '" . date("Y-m-") . "01' 
            and baixa.dtcadastro <= '" . date("Y-m-") . "30'";
            $totbaixa = $conexao->comandoArray($sql);            
            echo '<tr>';
            echo '<td style="text-align: left;">';
            echo 'CPF:', $baixa["cpf"], ' - Dt. Cadastro: ',$baixa["dtcadastro2"],'<br>';
                        
            if($linha < $qtd - 1){
                $sql = "select valor from baixa where codbaixa > '{$baixa["codbaixa"]}' and codfuncionario = '{$baixa["codfuncionario"]}' and codempresa = '{$baixa["codempresa"]}'";
                $baixa_posterior = $conexao->comandoArray($sql);
                $total_Vendas = $totbaixa["valor"] - $baixa_posterior["valor"];
            }else{
                $total_Vendas = $totbaixa["valor"];
            }
            $restante = $metaFuncionario["valor"] - $total_Vendas;            
            echo 'Valor:', number_format($baixa["valor"], 2, ",", "."), ' - Func:', $baixa["funcionario"], '<br>';
            echo 'Meta: ', number_format($metaFuncionario["valor"], 2, ",", "."), ' - Tot Vendas:', number_format($total_Vendas, 2, ",", "."), '<br>';

            
            echo 'Restante: ', number_format($restante, 2, ",", ".");
            echo '</td>';
            echo '<td>';
            $valor = str_replace(".", ",", $baixa["valor"]);
            $arrayJavascript = "new Array('{$baixa["codbaixa"]}', '{$baixa["cpf"]}', '{$valor}', '{$baixa["dtcadastro"]}')";
            echo '<a href="javascript: setaEditarBaixa(',$arrayJavascript,')" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '<a href="#" onclick="excluirBaixa(',$baixa["codbaixa"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
            echo '</td>';
            echo '</tr>';
            $linha++;
        }
        echo '</tbody>';
        echo '</table>';
    }else{
        echo '';
    }
    
    include "../model/Log.php";
    $log = new Log($conexao);
    
    
    $log->acao       = "procurar";
    $log->observacao = "Procurado baixa - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();     
    