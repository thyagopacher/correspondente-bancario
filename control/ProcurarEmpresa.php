<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    
    $and     = "";
    if(isset($_POST["codstatus"]) && $_POST["codstatus"] != NULL && $_POST["codstatus"] != ""){
        $and .= " and empresa.codstatus = '{$_POST["codstatus"]}'";
    }
    if(isset($_POST["codramo"]) && $_POST["codramo"] != NULL && $_POST["codramo"] != ""){
        $and .= " and empresa.codramo = '{$_POST["codramo"]}'";
    }
    if(isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != ""){
        $and .= " and empresa.razao like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
        $and .= " and empresa.dtcadastro >= '{$_POST["data1"]}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
        $and .= " and empresa.dtcadastro <= '{$_POST["data2"]}'";
    }
    if(isset($_POST["fornecedor"]) && $_POST["fornecedor"] != NULL && $_POST["fornecedor"] == "true"){
        $and .= " and empresa.codramo <> '7' and nomecontato <> ''";
    }elseif($_SESSION["codnivel"] != 1){
        $and .= " and empresa.codempresa = '{$_SESSION['codempresa']}' and codramo = 7";
    }
    
    if(isset($_SESSION["codnivel"]) && $_SESSION["codnivel"] != NULL && $_SESSION["codnivel"] != "1"){
        $and .= " and empresa.codpessoa in(select codpessoa from pessoa where pessoa.codempresa = '{$_SESSION['codempresa']}')";
    }
    $sql = "select empresa.codempresa, empresa.razao, empresa.telefone, empresa.celular, 
    empresa.email, DATE_FORMAT(empresa.dtcadastro, '%d/%m/%Y') as data, status.nome as status, pessoa.nome as funcionario
    from empresa 
    inner join statusempresa as status on status.codstatus = empresa.codstatus
    inner join pessoa on pessoa.codpessoa = empresa.codpessoa
    where 1 = 1 {$and}";

    $res = $conexao->comando($sql);
    if($res == FALSE){
        die("Erro ocasionado por:". mysqli_error($conexao->conexao));
    }
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        echo 'Encontrou ', $qtd, ' resultados<br>';
        echo '<table class="responstable">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>CONTROLE DE EMPRESAS</th>';
        echo '<th>Opções</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while($empresa = $conexao->resultadoArray($res)){
            echo '<tr>';
            echo '<td style="text-align: left;">';
            echo 'Razão:', $empresa["razao"], '<br>';
            echo 'Dt. Cadastro:',$empresa["data"], ' - Por:', $empresa["funcionario"], '<br>';
            echo 'E-mail:<a title="Clique para enviar e-mails" href="mailto:',$empresa["email"],'&subject=Contato GestCCon">',$empresa["email"],'</a><br>';
            echo 'Telefone: <a href="callto:',$empresa["telefone"],'" title="Clique aqui para ligar">',$empresa["telefone"],'</a>';
            echo ' - Celular: <a href="callto:',$empresa["celular"],'" title="Clique aqui para ligar">',$empresa["celular"],'</a><br>';
            echo 'Status:', $empresa["status"];
            echo '</td>';
            echo '<td>';
            if(isset($_POST["fornecedor"]) && $_POST["fornecedor"] != NULL && $_POST["fornecedor"] == "true"){
                echo '<a href="javascript:setaFornecedor(',$empresa["codempresa"],')" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            }else{
                echo '<a href="Empresa.php?codempresa=',$empresa["codempresa"],'" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
            }
            if($_SESSION['codpessoa'] == 6 && $_SESSION["codnivel"] == 1){
                echo '<a href="#" onclick="excluirEmpresa(',$empresa["codempresa"],')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
            }
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
    $log->observacao = "Procurar empresa - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir(); 