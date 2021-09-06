<?php
    session_start();
    include "../model/Conexao.php";
    $conexao = new Conexao();
    
    $and     = "";
    if(isset($_POST["periodo"]) && $_POST["periodo"] != NULL && $_POST["periodo"] != ""){
        $and .= " and inadimplencia.periodo = '{$_POST["periodo"]}'";
    }
    if(isset($_POST["apartamento"]) && $_POST["apartamento"] != NULL && $_POST["apartamento"] != ""){
        $and .= " and inadimplencia.apartamento = '{$_POST["apartamento"]}'";
    }
    if(isset($_POST["bloco"]) && $_POST["bloco"] != NULL && $_POST["bloco"] != ""){
        $and .= " and inadimplencia.bloco = '{$_POST["bloco"]}'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
        $and .= " and inadimplencia.dtpagamento >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
        $and .= " and inadimplencia.dtpagamento <= '{$_POST["data2"]}'";
    }
    $sql = "select inadimplencia.`bloco`, inadimplencia.`apartamento`, inadimplencia.`cotacondominio`, inadimplencia.`fundoreserva`, inadimplencia.`rateioagua`, 
    inadimplencia.`txextra1`, inadimplencia.`txextra2`, inadimplencia.`juro`, inadimplencia.`multa`, inadimplencia.codinadimplencia, 
    DATE_FORMAT(inadimplencia.dtpagamento, '%d/%m/%y') as dtpagamento2, DATE_FORMAT(inadimplencia.dtcadastro, '%d/%m/%y %H:%i') as dtcadastro2,
    pessoa.nome as funcionario
    from inadimplencia 
    inner join empresa on empresa.codempresa = inadimplencia.codempresa
    inner join pessoa on pessoa.codpessoa = inadimplencia.codfuncionario and pessoa.codempresa = inadimplencia.codempresa
    where 1 = 1 {$and}";
    $res = $conexao->comando($sql)or die("<pre>$sql</pre>");
    if($res == FALSE){
        die("Erro ocasionado por:". mysqli_error($conexao->conexao));
    }
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        echo 'Encontrou ', $qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>CONTROLE INADIMPLÊNCIA</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($inadimplencia = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td style="text-align: left;">';
            echo 'Bl: ', $inadimplencia["bloco"], ' - Apto: ', $inadimplencia["apartamento"], '<br>';
            echo 'Dt. cadastro: ', $inadimplencia["dtcadastro2"], ' - Por: ', $inadimplencia["funcionario"], '<br>';
            echo 'Dt. pagamento: ', $inadimplencia["dtpagamento2"], ' - Periodo: ',$inadimplencia["periodo"],'<br>';
            echo 'Cota condominio: R$ ', number_format($inadimplencia["cotacondominio"], 2, ",", "") ,' - Fundo reserva: R$ ', number_format($inadimplencia["fundoreserva"], 2, ",", ""),'<br>';
            echo 'Rateio Água: R$ ', number_format($inadimplencia["rateioagua"], 2, ",", "") , ' - Multa: R$ ', number_format($inadimplencia["multa"], 2, ",", ""), '<br>';
            echo 'Tx. extra 1: R$ ', number_format($inadimplencia["txextra1"], 2, ",", "") ,' - Tx. extra 2: R$ ', number_format($inadimplencia["txextra2"], 2, ",", ""), '<br>';
            $total = $inadimplencia["cotacondominio"] + $inadimplencia["fundoreserva"] + $inadimplencia["rateioagua"] + $inadimplencia["multa"] + $inadimplencia["txextra1"] + $inadimplencia["txextra2"] + $inadimplencia["juro"];
            echo 'Juros: R$ ', number_format($inadimplencia["juro"], 2, ",", ""),' - Total:  R$ ', number_format($total, 2, ",", ""), '<br>';
            echo '</td>';
            echo '<td>';
            echo '<a href="Inadimplencia.php?codinadimplencia=',$inadimplencia["codinadimplencia"],'" title="Clique aqui para editar informação"><img style="width: 20px;" src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
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
    $log->observacao = "Procurado inadimplencia - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();     