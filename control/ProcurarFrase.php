<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    
    $and     = "";
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL){
        $and .= " and frase.dtcadastro >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL){
        $and .= " and frase.dtcadastro <= '{$_POST["data2"]}'";
    }
    $sql = "select codfrase, texto, DATE_FORMAT(dtcadastro, '%d/%m/%Y') as dtcadastro2, frase.popup, frase.periodo
    from frase
    where 1 = 1
    {$and} order by frase.dtcadastro desc";
    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        echo 'Encontrou ',$qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th style="width: 600px;">CADASTRO FRASE</th>';
        echo '<th>POPUP</th>';
        echo '<th>Periodo</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($frase = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td style="text-align: left;">';
            echo 'Texto:', $frase["texto"], ' - Dt. Cadastro: ',$frase["dtcadastro2"],'<br>';
            echo '</td>';
            echo '<td style="text-align: left;">',$frase["popup"],'</td>';
            echo '<td style="text-align: left;">',periodoDefine($frase["periodo"]),'</td>';
            echo '<td>';
            echo '<a href="Frase.php?codfrase=',$frase["codfrase"],'" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            echo '<a href="#" onclick="excluirFrase(',$frase["codfrase"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
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
    $log->observacao = "Procurar frase - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();     
    
    function periodoDefine($periodo){
        switch ($periodo) {
            case '0':
                $periodo = "nenhum";
                break;
            case '1':
                $periodo = "manhã";
                break;
            case '2':
                $periodo = "tarde";
                break;
            case '3':
                $periodo = "noite";
                break;
        }
        return $periodo;
    }