<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $and     = "";

    if(isset($_POST["quemfez"]) && $_POST["quemfez"] != NULL && $_POST["quemfez"] != ""){
        $and .= " and pessoa.nome like '%{$_POST["quemfez"]}%'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
        if(strpos($_POST["data1"], "/")){
            $data1 = implode("-",array_reverse(explode("/", $_POST["data1"])));
            $and .= " and log.data >= '{$data1}'";
        }else{
            $and .= " and log.data >= '{$_POST["data1"]}'";
        }          
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
        if(strpos($_POST["data2"], "/")){
            $data2 = implode("-",array_reverse(explode("/", $_POST["data2"])));
            $and .= " and log.data >= '{$data2}'";
        }else{
            $and .= " and log.data >= '{$_POST["data2"]}'";
        } 
    }
    if(isset($_SESSION["codnivel"]) && $_SESSION["codnivel"] == 1){
        $andPessoa = " and (pessoa.codnivel = 1 or pessoa.codempresa = '{$_SESSION['codempresa']}')";
    }else{
        $andPessoa = " and pessoa.codempresa = '{$_SESSION['codempresa']}'";
    }    
    $sql = "select codlog, DATE_FORMAT(log.data, '%d/%m/%y') as data2, 
        hora, pessoa.codpessoa, pessoa.nome as quemfez, pagina.nome as pagina, log.observacao
    from log 
    inner join pagina on pagina.codpagina = log.codpagina
    inner join pessoa on pessoa.codpessoa = log.codpessoa $andPessoa
    where log.codempresa = '{$_SESSION['codempresa']}' {$and} order by log.data desc, log.hora desc";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $html  = "";
        $nome  = 'Relatório de Log';
        $html .= '<table class="responstable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Data</th>';
        $html .= '<th>Hora</th>';
        $html .= '<th>Quem fez</th>';
        $html .= '<th>Observação</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        while($acesso = $conexao->resultadoArray($res)){
            $html .= '<tr>';
            $html .= '<td style="width: 100px;">'.$acesso["data2"].'</td>';
            $html .= '<td style="width: 100px;">'.$acesso["hora"].'</td>';
            $html .= '<td style="width: 180px;">'.$acesso["quemfez"].'</td>';
            $html .= '<td>'.$acesso["observacao"].'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';

        $_POST["html"] = $html;
        $paisagem = "sim";        
        
        if(isset($_POST["tipo"]) && $_POST["tipo"] == "xls"){
            include "./GeraExcel.php";
        }else{
            include "./GeraPdf.php";
        }          
    }else{
        echo '<script>alert("Sem log encontrado para esse condominio!");window.close();</script>';
    }

