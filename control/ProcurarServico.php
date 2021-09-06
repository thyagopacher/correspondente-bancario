<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    include "../model/Servico.php";
    
    $conexao = new Conexao();
    $servico  = new Servico($conexao);
    if(!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == ""){
        $servico->codempresa = $_SESSION['codempresa'];
    }else{
        $servico->codempresa = $_POST["codempresa"];
    }        
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $res = $servico->procuraNome($_POST["nome"]);
    }else{
        $res = $servico->procuraNome("");
    }
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        echo 'Encontrou ', $qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th style="width: 600px;">CONTROLE DE SERVIÇO</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($servico = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td style="text-align: left;width: 600px;">';
            echo 'Nome:', $servico["nome"], ' - R$ ',  number_format($servico["valor"], 2, ",", "."), '<br>';
            echo 'Data:', $servico["data2"], ' - Para: ', $servico["morador"], ' - Tipo: ',$servico["tipo"],'<br>';
            echo 'Bl:',$servico["bloco"], '- Apto: ', $servico["apartamento"], '<br>';
            echo 'Dt. Cadastro:',$servico["dtcadastro2"],' - Funcionário:', $servico["funcionario"];
            echo '</td>';
            echo '<td>';
            echo '<a href="Servico.php?codservico=',$servico["codservico"],'" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '<a href="#" onclick="excluirServico2(',$servico["codservico"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
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
    $log->observacao = "Procurado serviço - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir(); 