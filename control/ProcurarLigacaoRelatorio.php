<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
include "../model/Conexao.php";
$and = "";
if(isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != ""){
    $and .= " and agenda.dtcadastro >= '{$_POST["data1"]} 00:00:00'";
}
if(isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != ""){
    $and .= " and agenda.dtcadastro <= '{$_POST["data2"]} 23:59:59'";
}
if(isset($_POST["operador"]) && $_POST["operador"] != NULL && $_POST["operador"] != ""){
    $and .= " and agenda.codfuncionario = '{$_POST["operador"]}'";
}
if(isset($_POST["codstatus"]) && $_POST["codstatus"] != NULL && $_POST["codstatus"] != ""){
    $and .= " and agenda.codstatus = '{$_POST["codstatus"]}'";
}
$conexao   = new Conexao();
$sql = "select status.nome as status, funcionario.nome as funcionario, DATE_FORMAT(agenda.dtcadastro, '%d/%m/%y') as dtcadastro2, 
cliente.nome as cliente, DATE_FORMAT(agenda.dtagenda, '%d/%m/%y') as dtagenda2, nivel.nome as nivel, cliente.cpf, cliente.codpessoa as codigo_cliente, agenda.observacao
from agenda
inner join statuspessoa as status on status.codstatus = agenda.codstatus
inner join pessoa as funcionario on funcionario.codpessoa = agenda.codfuncionario and funcionario.codempresa = agenda.codempresa
inner join nivel on nivel.codnivel = funcionario.codnivel 
inner join pessoa as cliente on cliente.codpessoa = agenda.codpessoa and cliente.codempresa = agenda.codempresa
where agenda.codempresa = '{$_SESSION['codempresa']}' {$and} order by agenda.dtcadastro desc";
$resagenda = $conexao->comando($sql)or die("<pre>$sql</pre>");
$qtdagenda = $conexao->qtdResultado($resagenda);
if($qtdagenda > 0){
    $nome  = 'Rel. Ligação';
    $html  = '<table class="responstable">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th>Status</th>';
    $html .= '<th width="140">Operador</th>';
    $html .= '<th width="100">Dt. Cadastro</th>';
    $html .= '<th>Cliente</th>';
    $html .= '<th width="140">Cpf</th>';
    $html .= '<th width="100">Agendado</th>';
    if(isset($_POST["tipo"]) && $_POST["tipo"] == "xls"){
        $html .= '<th width="140">Telefone(s)</th>';
        $html .= '<th width="250">Observação</th>';
    }    
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    while($agenda = $conexao->resultadoArray($resagenda)){
        $html .= '<tr>';
        $html .= '<td style="text-align: left;">'.$agenda["status"].'</td>';
        $html .= '<td style="text-align: left;">'.$agenda["funcionario"].'</td>';
        $html .= '<td style="text-align: center;">'.$agenda["dtcadastro2"].'</td>';
        $html .= '<td style="text-align: left;">'.$agenda["cliente"].'</td>';
        $html .= '<td style="text-align: left;">'.$agenda["cpf"].'</td>';
        $html .= '<td style="text-align: center;">'.$agenda["dtagenda2"].'</td>';
        if(isset($_POST["tipo"]) && $_POST["tipo"] == "xls"){
            $restelefone = $conexao->comando("select * from telefone where codpessoa = '{$agenda["codigo_cliente"]}' and numero <> '' and codempresa = '{$_SESSION['codempresa']}'");
            $qtdtelefone = $conexao->qtdResultado($restelefone);
            if($qtdtelefone > 0){
                $html .= '<td>';
                while($telefone = $conexao->resultadoArray($restelefone)){
                    $html .= ''.$telefone["numero"].', ';
                }
                $html .= '</td>';
            }
            $html .= '<td style="text-align: center;">'.$agenda["observacao"].'</td>';
        }
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
    echo '<script>alert("Sem agenda encontrada!");window.close();</script>';
}