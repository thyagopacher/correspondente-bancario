<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $and     = "";
    if(isset($_POST["localizacao"]) && $_POST["localizacao"] != NULL && $_POST["localizacao"] != ""){
        $and .= " and tarefa.localizacao like '%{$_POST["localizacao"]}%'";
    }
    if(isset($_POST["dtcadastro1"]) && $_POST["dtcadastro1"] != NULL && $_POST["dtcadastro1"] != ""){
        $and .= " and tarefa.dtcadastro >= '{$_POST["dtcadastro1"]}'";
    }
    if(isset($_POST["dtcadastro2"]) && $_POST["dtcadastro2"] != NULL && $_POST["dtcadastro2"] != ""){
        $and .= " and tarefa.dtcadastro <= '{$_POST["dtcadastro2"]}'";
    }
    if(isset($_POST["prioridade"]) && $_POST["prioridade"] != NULL && $_POST["prioridade"] != ""){
        $and .= " and tarefa.prioridade = '{$_POST["prioridade"]}'";
    }
    if(isset($_POST["resolvido"]) && $_POST["resolvido"] != NULL && $_POST["resolvido"] != ""){
        $and .= " and tarefa.resolvido = '{$_POST["resolvido"]}'";
    }
    if(isset($_POST["liberado"]) && $_POST["liberado"] != NULL && $_POST["liberado"] != ""){
        $and .= " and tarefa.liberado = '{$_POST["liberado"]}'";
    }
    if(isset($_POST["codempresa"]) && $_POST["codempresa"] != NULL && $_POST["codempresa"] != ""){
        $and .= " and tarefa.codempresa = '{$_POST["codempresa"]}'";
    }
    $sql = "select tarefa.codtarefa, tarefa.descricao, DATE_FORMAT(tarefa.dtcadastro, '%d/%m/%Y %H:%i:%s') as dtcadastro2, 
        DATE_FORMAT(tarefa.hora_resolvido, '%H:%i') as hora_resolvido,
        DATE_FORMAT(tarefa.data_resolvido, '%d/%m/%y') as data_resolvido,
        tarefa.localizacao, tarefa.imagem, tarefa.liberado, tarefa.prioridade, tarefa.resolvido
        from tarefa
        inner join empresa on empresa.codempresa = tarefa.codempresa
        where 1 = 1 {$and} order by tarefa.dtcadastro desc";
    $res = $conexao->comando($sql)or die("<pre>$sql</pre>");
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        echo 'Encontrou ', $qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Tarefas</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($tarefa = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td style="text-align: left;width: 610px;">';
            if($tarefa["resolvido"] == "s"){
                $completaResolvido = ' em: '.$tarefa["data_resolvido"].' às:'.$tarefa["hora_resolvido"];
            }else{
                $completaResolvido = '';
            }
            echo 'Localização:', $tarefa["localizacao"] ,'<br>';
            echo 'Resolvido:',trocaSIMNAO($tarefa["resolvido"]), $completaResolvido,'<br>';
            echo 'Liberado:', trocaSIMNAO($tarefa["liberado"]), ' - Prioridade:', trocaPrioridade($tarefa["prioridade"]),'<br>';
            echo 'Dt. Cadastro: ', $tarefa["dtcadastro2"], '<br>';
            echo 'Descrição:', $tarefa["descricao"];
            echo '</td>';
            echo '<td>';
            echo '<a href="Tarefa.php?codtarefa=',$tarefa["codtarefa"],'" title="Clique aqui para editar a tarefa"><img src="../visao/recursos/img/editar.png" alt="botão excluir"/></a>';
            echo '<a href="#" onclick="excluirTarefa(',$tarefa["codtarefa"],')" title="Clique aqui para excluir o tarefa"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }else{
        echo '';
    }

function trocaSIMNAO($situacao){
    if($situacao != NULL && trim($situacao) == "s"){
        $situacao = "sim";
    }else{
        $situacao = "não";
    }
    return $situacao;
}

function trocaPrioridade($prioridade){
    if($prioridade != NULL && $prioridade == "u"){
        $prioridade = "urgente";
    }elseif($prioridade != NULL && $prioridade == "g"){
        $prioridade = "grande";
    }elseif($prioridade != NULL && $prioridade == "m"){
        $prioridade = "média";
    }elseif($prioridade != NULL && $prioridade == "p"){
        $prioridade = "pequena";
    }
    return $prioridade;
}