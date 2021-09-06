<?php
    session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $and     = "";
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and pessoa.nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
        $and .= " and acesso.data >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
        $and .= " and acesso.data <= '{$_POST["data2"]}'";
    }

    $res = $conexao->comando("select codacesso, pessoa.nome, acesso.enderecoip, DATE_FORMAT(data, '%d/%m/%Y') as data2, acesso.quantidade, pessoa.bloco, pessoa.apartamento
        from acesso
        inner join pessoa on pessoa.codpessoa = acesso.codpessoa and pessoa.codempresa = acesso.codempresa
        where 1 = 1 {$and} 
        and acesso.codempresa = '{$_SESSION['codempresa']}'            
        order by acesso.data desc");
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $html  = '';
        $nome = 'Acesso Pessoa - Encontrou '. $qtd. ' resultados<br>';
        $html .= '<table class="responstable  table table-hover">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Data</th>';
        $html .= '<th>Nome</th>';
        $html .= '<th>Bloco</th>';
        $html .= '<th>Apto.</th>';
        $html .= '<th>Endereço IP</th>';
        $html .= '<th>Quantidade</th>';
        if (isset($_POST["tipo"]) && $_POST["tipo"] == "xls") {
            $rescampo = $conexao->comando("select * from campoextra where codpagina = '{$_POST["codpagina"]}' and codempresa = '{$_SESSION['codempresa']}'");
            $qtdcampo = $conexao->qtdResultado($rescampo);
            if ($qtdcampo > 0) {
                while ($campo = $conexao->resultadoArray($rescampo)) {
                    $html .= '<th>' . $campo["titulo"] . '</th>';
                }
            }
        }        
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        while($acesso = $conexao->resultadoArray($res)){
            $html .= '<tr id="'.$acesso["codacesso"].'">';
            $html .= '<td>'.$acesso["data2"].'</td>';
            $html .= '<td>'.$acesso["nome"].'</td>';
            $html .= '<td>'.$acesso["bloco"].'</td>';
            if(!isset($acesso["apartamento"]) || $acesso["apartamento"] == NULL || $acesso["apartamento"] == "" || $acesso["apartamento"] == "0"){
                $html .= '<td></td>';
            }else{
                $html .= '<td>'.$acesso["apartamento"].'</td>'; 
            }
            $html .= '<td>'.$acesso["enderecoip"].'</td>';
            $html .= '<td>'.$acesso["quantidade"].'</td>';
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
        echo '<script>alert("Sem acesso encontrado!");window.close();</script>';
    }

