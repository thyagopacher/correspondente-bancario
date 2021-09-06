<?php
include "../model/Conexao.php";
session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
$conexao = new Conexao();
$sql = "select chat.*, DATE_FORMAT(chat.dtcadastro, '%H:%i:%s') as dtcadastro2, enviador.nome as enviador
from chat 
inner join pessoa as enviador on enviador.codpessoa = chat.codpessoa1
inner join pessoa as receptor on receptor.codpessoa = chat.codpessoa2
where chat.codpessoa2 = '{$_POST["logado"]}' 
and chat.codpessoa1 = '{$_SESSION['codpessoa']}' 
and chat.dtcadastro >= '".date('Y-m-d')." 00:00:00' and chat.dtcadastro <= '".date('Y-m-d')." 23:59:59'
$and   
union
select chat.*, DATE_FORMAT(chat.dtcadastro, '%H:%i:%s') as dtcadastro2, enviador.nome as enviador
from chat 
inner join pessoa as enviador on enviador.codpessoa = chat.codpessoa1
inner join pessoa as receptor on receptor.codpessoa = chat.codpessoa2
where chat.codpessoa1 = '{$_POST["logado"]}' and chat.codpessoa2 = '{$_SESSION['codpessoa']}' and chat.dtcadastro >= '".date('Y-m-d')." 00:00:00' and chat.dtcadastro <= '".date('Y-m-d')." 23:59:59'
$and  
order by dtcadastro desc";

$reschat = $conexao->comando($sql)or die("<pre>$sql</pre>");
$qtdchat = $conexao->qtdResultado($reschat);
$frases_enviadas = "";
if($qtdchat > 0){
    $cor1 = "#D9E9FE;";
    $cor2 = "rgb(199,236,252);";
    $coraplicada      = $cor1;    
    $enviadorAnterior = 0;
    $corAnterior      = "";    
    while($chat = $conexao->resultadoArray($reschat)){
        if($enviadorAnterior != 0 && $enviadorAnterior == $chat["enviador"]){
            $coraplicada = $corAnterior;
        }        
        $enviador_por     = explode(" ", $chat["enviador"]);
        $frases_enviadas .= '<div style="border: 1px solid #657FB3;margin-top: 5px;width: 240px;float: right;padding: 5px; background: '.$coraplicada.'">';
        $frases_enviadas .= $chat["texto"];
        $frases_enviadas .= '</div>';
        $frases_enviadas .= '<div style="text-align: right;width: 97.5%;float: left;">'. $enviador_por[0]. ' em '. $chat["dtcadastro2"]. '</div>';
        if($coraplicada == $cor2){
            $coraplicada = $cor1;
        }else{
            $coraplicada = $cor2;
        } 
        $enviadorAnterior = $chat["enviador"];
        $corAnterior      = $coraplicada;
        if($coraplicada == $cor2){
            $coraplicada = $cor1;
        }else{
            $coraplicada = $cor2;
        } 
        if($chat["codpessoa1"] == $_SESSION['codpessoa']){
            $setar = ", lidopor1 = '{$_SESSION['codpessoa']}'";
        }elseif($chat["codpessoa2"] == $_SESSION['codpessoa']){
            $setar = ", lidopor2 = '{$_SESSION['codpessoa']}'";
        }
        $conexao->comando("update chat set lido = 's' {$setar} where codchat = '{$chat["codchat"]}' and codempresa = '{$_SESSION['codempresa']}'");
    }
}
echo json_encode(array('logado' => $_POST["logado"], 'frases_enviadas' => $frases_enviadas));