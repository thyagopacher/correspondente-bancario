<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";   
    $conexao = new Conexao();
    
    include "../model/Log.php";
    $log = new Log($conexao);
    
    
    $log->acao       = "procurar";
    $log->observacao = "Procurado conta relatório - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();         
    
    $and     = "";
    if(isset($_POST["nome"])){
        $and .= " and conta.nome like '%{$_POST["nome"]}%'";
    }
    if(isset($_POST["data"]) && $_POST["data"] != NULL){
        $data = implode("-",array_reverse(explode("/",$_POST["data"])));
        $and .= " and conta.data >= '{$data}'";
    }
    if(isset($_POST["data2"]) && $_POST["data2"] != NULL){
        $data2 = implode("-",array_reverse(explode("/",$_POST["data2"])));
        $and  .= " and conta.data <= '{$data2}'";
    }
    if(isset($_POST["movimentacao"]) && $_POST["movimentacao"] != NULL && $_POST["movimentacao"] != ""){
        $and .= " and conta.movimentacao = '{$_POST["movimentacao"]}'";
    }
    if(isset($_POST["codtipo"]) && $_POST["codtipo"] != NULL && $_POST["codtipo"] != ""){
        $and .= " and conta.codtipo = '{$_POST["codtipo"]}'";
    }
    if(isset($_POST['valor']) && $_POST['valor'] != NULL && $_POST['valor'] != ""){
        $and .= " and conta.valor = '{$_POST['valor']}'";
    }
    if(isset($_POST["codstatus"]) && $_POST["codstatus"] != NULL && $_POST["codstatus"] != ""){
        $and .= " and conta.codstatus = '{$_POST["codstatus"]}'";
    }
    if(isset($_POST["codempresa"]) && $_POST["codempresa"] != NULL && $_POST["codempresa"] != ""){
        $and .= " and conta.codempresa = '{$_POST["codempresa"]}'";
    }elseif(!isset($_POST["master"]) || $_POST["master"] == NULL || $_POST["master"] == ""){
        $and .= " and conta.codempresa = '{$_SESSION['codempresa']}'";
    }
    if(isset($_POST["rateio"]) && $_POST["rateio"] != NULL && $_POST["rateio"] == "s"){
        $and .= " and conta.codambiente > 0";
        $link = "Rateio.php";
    }else{
        $link = "Conta.php";
    }
    if(isset($_POST["ordem"]) && $_POST["ordem"] != NULL && $_POST["ordem"] != ""){
        if($_POST["ordem"] == 1){
            $orderBy = "order by codconta desc";
        }elseif($_POST["ordem"] == 2){
            $orderBy = "order by conta.nome desc";
        }
    }
    
    $sql = "select conta.codconta, conta.nome, conta.valor, DATE_FORMAT(conta.data, '%d/%m/%Y') as data2, DATE_FORMAT(conta.dtpagamento, '%d/%m/%Y') as dtpagamento2,
    DATE_FORMAT(conta.dtcadastro, '%d/%m/%Y') as dtcadastro2, funcionario.nome as funcionario, conta.data, empresa.razao as empresa, tipo.nome as tipo, conta.observacao
    from conta 
    inner join tipoconta as tipo on tipo.codtipo = conta.codtipo and tipo.codempresa = tipo.codempresa 
    inner join pessoa as funcionario on funcionario.codpessoa = conta.codfuncionario
    inner join empresa on empresa.codempresa = conta.codempresa
    where 1 = 1 {$and} $orderBy";

    $res = $conexao->comando($sql);
    $qtd = $conexao->qtdResultado($res);
    
    if($qtd > 0){
        $html = "";
        $nome = "Rel. Contas - ". date('d/m/Y');
        $html .= '<table class="responstable">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<th>Vencimento</th>';
        $html .= '<th>Dt. Pag.</th>';
        $html .= '<th>Dt. Cadastro</th>';
        $html .= '<th>Funcionário</th>';
        $html .= '<th>Nome</th>';
        $html .= '<th>Tipo</th>';
        $html .= '<th>Valor</th>';
        $html .= '<th>Observação</th>';        
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        $i           = 0;
        $valores     = array();
        $referencias = array();
        while($conta = $conexao->resultadoArray($res)){
            $html .= '<tr>';
            $html .= '<td style="text-align: center;">'.$conta["data2"].'</td>';
            if(isset($conta["dtpagamento2"]) && $conta["dtpagamento2"] != NULL && $conta["dtpagamento2"] != "" && trim($conta["dtpagamento2"]) != "00/00/0000"){
                $html .= '<td style="text-align: center;">'.$conta["dtpagamento2"].'</td>';
            }else{
                $html .= '<td></td>';
            }
            $html .= '<td style="text-align: center;">'.$conta["dtcadastro2"].'</td>';
            $html .= '<td style="width: 180px;">'.$conta["funcionario"].'</td>';
            $html .= '<td>'.$conta["nome"].'</td>';
            $html .= '<td style="width: 200px;">'.$conta["tipo"].'</td>';
            $html .= '<td style="text-align: right;">'.  number_format($conta["valor"], 2., ",", ".").'</td>';
            $html .= '<td style="text-align: right;">'.  $conta["observacao"].'</td>';
            $html .= '</tr>';
            $referencias[$i] = $conta["nome"];
            $valores[$i]     = $conta["valor"];
            $i++;
        }
        $html .= '</tbody>';
        $html .= '</table>';

        $_POST["html"] = $html;
        $paisagem = "sim";  
//        echo $html;
        if(isset($_POST["tipo"]) && $_POST["tipo"] == "xls"){
            include "./GeraExcel.php";
        }else{
            include "./GeraPdf.php";
        }          
    }else{
        echo '<script>alert("Sem conta encontrada!");window.close();</script>';
    }
