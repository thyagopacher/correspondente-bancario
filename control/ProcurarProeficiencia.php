<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $and     = "";

    if(isset($_POST["perfil"]) && $_POST["perfil"] != NULL && $_POST["perfil"] != ""){
        $and .= " and proeficiencia.perfil like '%{$_POST["perfil"]}%'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
        $and .= " and proeficiencia.dtvigencia >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
        $and .= " and proeficiencia.dtvigencia <= '{$_POST["data2"]}'";
    }
    
    $sql = "select distinct perfil, DATE_FORMAT(dtcadastro, '%d/%m/%Y') as dtcadastro2 
        from proeficiencia
        where 1 = 1 {$and} 
        order by proeficiencia.dtcadastro desc";
  
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        echo 'Encontrou ', $qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Dt. Cadastro</th>';
        echo '<th>Perfil</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($proeficiencia = $conexao->resultadoArray($res)){
            $proeficiencia2 = $conexao->comandoArray("select codproeficiencia from proeficiencia where perfil = '{$proeficiencia["perfil"]}'");
            echo '<tr>';
            echo '<td>',$proeficiencia["dtcadastro2"],'</td>';
            echo '<td>',$proeficiencia["perfil"],'</td>';
            echo '<td>';
            echo '<a href="Proeficiencia.php?perfil=',$proeficiencia["perfil"],'" onclick="" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '<a href="#" onclick="excluirProeficiencia(',$proeficiencia2["codproeficiencia"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
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
    $log->observacao = "Procurado proeficiencia - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();  