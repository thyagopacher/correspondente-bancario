<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $and     = "";
    
    if(isset($_POST["ramal"]) && $_POST["ramal"] != NULL && $_POST["ramal"] != ""){
        $and .= " and ramal like '%{$_POST["ramal"]}%'";
    }
    if(isset($_POST["externo"]) && $_POST["externo"] != NULL && $_POST["externo"] != ""){
        $and .= " and externo = '{$_POST["externo"]}'";
    }
    if(isset($_POST["telefone"]) && $_POST["telefone"] != NULL && $_POST["telefone"] != ""){
        $and .= " and telefone like '%{$_POST["telefone"]}%'";
    }
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["codempresa"]) && $_POST["codempresa"] != NULL && $_POST["codempresa"] != ""){
        $and .= " and (codempresa = '{$_POST["codempresa"]}' or externo = 's')";
    }else{
        $and .= " and codempresa = '{$_SESSION['codempresa']}'";
    }    
    $sql = "select * from ramal where 1 = 1 {$and} order by nome";
    $res = $conexao->comando($sql)or die("<pre>$sql</pre>");
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        echo 'Encontrou ', $qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>TELEFONES ÚTEIS</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($ramal = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td style="text-align: left;  width: 720px;">';
            echo 'Nome:', $ramal["nome"], ' - Externo:', trocaExterno($ramal["externo"]), '<br>';
            echo 'Telefone:', $ramal["telefone"] ." - Ramal:".$ramal["ramal"];
            echo '</td>';

            echo '<td>';
            echo '<a href="Ramal.php?codramal=',$ramal["codramal"],'" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '<a href="#" onclick="excluirRamal2(',$ramal["codramal"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }else{
        echo '';
    }

    
    function trocaExterno($externo){
        switch ($externo) {
            case "s":
                $externo = "sim";
                break;
            case "n":
                $externo = "não";
                break;
        }
        return $externo;
    }
