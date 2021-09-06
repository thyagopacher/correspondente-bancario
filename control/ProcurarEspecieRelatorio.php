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
        $nome  = 'Rel. Espécies';
        $html .= '<table class="responstable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Num. INSS</th>';
        $html .= '<th>Nome</th>';       
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        while($especie = $conexao->resultadoArray($res)){
            $html .= '<tr>';
            $html .= '<td>'.$especie["numinss"].'</td>';
            $html .= '<td>'.$especie["nome"].'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
    }else{
        echo '';
    }

    $_POST["html"] = $html;
    $paisagem = "sim";

    if(isset($_POST["tipo"]) && $_POST["tipo"] == "xls"){
        include "./GeraExcel.php";
    }else{
        include "./GeraPdf.php";
    }
