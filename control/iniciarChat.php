<?php
include "../model/Conexao.php";
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
function __autoload($class_name) {
    if(file_exists("../model/".$class_name . '.php')){
        include "../model/".$class_name . '.php';
    }elseif(file_exists("../visao/".$class_name . '.php')){
        include "../visao/".$class_name . '.php';
    }elseif(file_exists("./".$class_name . '.php')){
        include "./".$class_name . '.php';
    }
}

echo '<form id="finiciarChat" action="" method="post" name="finiciarChat" onsubmit="return false;">';
echo '<input type="hidden" name="enviadopor" id="enviadopor" value="',$_SESSION['codpessoa'],'"/>';
echo '<input type="hidden" name="logado" id="logadoChat" value="',$_POST["logado"],'"/>';
echo '<table style="width: auto;" class="tabela_formulario">';
echo '<tr>';
echo '<td>Texto</td>';
echo '<td><textarea style="width: 600px; max-width: 600px; min-width: 600px; height: 75px; min-height: 75px; max-height: 75px; margin: 0px;" name="texto" id="texto" class="texto" cols="8"></textarea></td>';
echo '</tr>';
echo '</table>';
echo '<input type="button" style="margin-left: 10px;" onclick="enviarConversa();" value="Enviar"/>';
echo '<input type="button" style="margin-left: 10px;" onclick="finalizaChat();" value="Finalizar"/>';
echo '</form>';
    

echo '<div id="chat_enviados" style="  height: 300px; overflow: auto;">';
$conexao = new Conexao();
$sql = "select chat.*, DATE_FORMAT(chat.dtcadastro, '%H:%i:%s') as dtcadastro2, enviador.nome as enviador 
from chat 
inner join pessoa as enviador on enviador.codpessoa = chat.codpessoa1
inner join pessoa as receptor on receptor.codpessoa = chat.codpessoa2
where chat.codpessoa2 = '{$_POST["logado"]}' and chat.codpessoa1 = '{$_SESSION['codpessoa']}' and chat.dtcadastro >= '".date('Y-m-d')." 00:00:00' and chat.dtcadastro <= '".date('Y-m-d')." 23:59:59'
union
select chat.*, DATE_FORMAT(chat.dtcadastro, '%H:%i:%s') as dtcadastro2, enviador.nome as enviador 
from chat 
inner join pessoa as enviador on enviador.codpessoa = chat.codpessoa1
inner join pessoa as receptor on receptor.codpessoa = chat.codpessoa2
where chat.codpessoa1 = '{$_POST["logado"]}' and chat.codpessoa2 = '{$_SESSION['codpessoa']}' and chat.dtcadastro >= '".date('Y-m-d')." 00:00:00' and chat.dtcadastro <= '".date('Y-m-d')." 23:59:59'
order by dtcadastro desc";

$reschat = $conexao->comando($sql)or die("<pre>$sql</pre>");
$qtdchat = $conexao->qtdResultado($reschat);
if($qtdchat > 0){
    $cor1 = "rgb(229,246,253);";
    $cor2 = "rgb(199,236,252);";
    $coraplicada = $cor1;    
    while($chat = $conexao->resultadoArray($reschat)){
        echo '<div style="margin-top: 10px; background: ',$coraplicada,';border-radius: 5px;padding: 10px;  width: 625px;">';
        echo 'Texto:', $chat["texto"];
        echo '</div>';
        echo '<div style="text-align: right;width: 97.5%;">', $chat["enviador"], ' em ', $chat["dtcadastro2"], '</div>';
        if($coraplicada == $cor2){
            $coraplicada = $cor1;
        }else{
            $coraplicada = $cor2;
        }        
    }
}
echo '</div>';

