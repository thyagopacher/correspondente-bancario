<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    
    $and     = "";
    if(isset($_POST["numinss"]) && $_POST["numinss"] != NULL && $_POST["numinss"] != ""){
        $and .= " and especie.numinss = '{$_POST["numinss"]}'";
    }
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and especie.nome like '%{$_POST["nome"]}%'";
    }
    
    $sql = "select codespecie, nome, numinss from especie
    where 1 = 1 {$and}";
    $res = $conexao->comando($sql);
    if($res == FALSE){
        die("Erro ocasionado por:". mysqli_error($conexao->conexao));
    }
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        echo 'Rel. Espécies';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Num. INSS</th>';
        echo '<th>Nome</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($especie = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td>',$especie["numinss"],'</td>';
            echo '<td>',$especie["nome"],'</td>';
            echo '<td>';
            echo '<a href="Especie.php?codespecie=',$especie["codespecie"],'" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '<a href="#" onclick="excluirEspecie(',$especie["codespecie"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
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
    $log->observacao = "Procurado espécie - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();  