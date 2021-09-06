<?php

    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    $conexao = new Conexao();
    
    if(isset($_SESSION["codnivel"]) && $_SESSION["codnivel"] != 1){
        $and .= " and pessoa.codpessoa = '{$_SESSION['codpessoa']}'";
    }     
    if(isset($_POST["codproposta"]) && $_SESSION["codproposta"] != 1){
        $and .= " and obs.codproposta = '{$_POST["codproposta"]}'";
    }     
    
    $sql = "select obs.observacao, status.nome as status, DATE_FORMAT(obs.dtcadastro, '%d/%m/%y %H:%i') as dtcadastro2,
    banco.nome as banco, convenio.nome as convenio, tabela.nome as tabela, obs.prazo, obs.valor, pessoa.nome as funcionario    
    from observacaoproposta as obs
    inner join statusproposta as status on status.codstatus = obs.codstatus
    inner join banco on banco.codbanco = obs.codbanco
    inner join convenio on convenio.codconvenio = obs.codconvenio
    inner join tabela on tabela.codtabela = obs.codtabela
    inner join pessoa on pessoa.codpessoa = obs.codfuncionario
    where obs.codempresa = '{$_SESSION['codempresa']}' and obs.codcliente = '{$_POST["codcliente"]}' {$and} order by obs.dtcadastro desc";

    $resobservacao = $conexao->comando($sql)or die("<pre>$sql</pre>");
    $qtdobservacao = $conexao->qtdResultado($resobservacao);
    if($qtdobservacao > 0){
        echo 'Andamento da proposta';
        echo '<table class="responstable" style="font-size: 12px;">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Cadastro</th>';
        echo '<th>Banco</th>';
        echo '<th>Convênio</th>';
        echo '<th>Tabela</th>';
        echo '<th>Prazo</th>';
        echo '<th>Valor</th>';
        echo '<th>Status</th>';
        echo '<th>Por</th>';
        echo '<th>Obs</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($observacao = $conexao->resultadoArray($resobservacao)){
            echo '<tr>';
            echo '<td>',$observacao["dtcadastro2"],'</td>';
            echo '<td>',$observacao["banco"],'</td>';
            echo '<td>',$observacao["convenio"],'</td>';
            echo '<td>',$observacao["tabela"],'</td>';
            echo '<td>',$observacao["prazo"],'</td>';
            echo '<td>',number_format($observacao["valor"], 2, ",", ""),'</td>';
            echo '<td>',$observacao["status"],'</td>';
            echo '<td>',$observacao["funcionario"],'</td>';
            echo '<td>',$observacao["observacao"],'</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';        
    }