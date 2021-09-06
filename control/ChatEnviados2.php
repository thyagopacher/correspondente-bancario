<?php

ob_start();
include '../model/Conexao.php';
session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
$conexao = new Conexao();
$and = '';
$limite = 20 * $_POST["passo"];
$sql = "select chat.codchat
from chat 
inner join pessoa as enviador on enviador.codpessoa = chat.codpessoa1 
inner join pessoa as receptor on receptor.codpessoa = chat.codpessoa2 
where chat.codpessoa2 = '{$_POST['logado']}' 
and chat.codpessoa1 = '{$_SESSION['codpessoa']}' 
$and   
union
select chat.codchat
from chat 
inner join pessoa as enviador on enviador.codpessoa = chat.codpessoa1 
inner join pessoa as receptor on receptor.codpessoa = chat.codpessoa2
where chat.codpessoa1 = '{$_POST["logado"]}' and chat.codpessoa2 = '{$_SESSION['codpessoa']}' 
$and  
order by codchat desc limit {$limite}";
$array_chat = array();
$resChat = $conexao->comando($sql);
$qtdChat = $conexao->qtdResultado($resChat);
if ($qtdChat > 0) {
    $indice = 0;
    while ($chat = $conexao->resultadoArray($resChat)) {
        $array_chat[$indice] = $chat["codchat"];
        $indice++;
    }
}
krsort($array_chat);
$comma_separated = implode(",", $array_chat);
if ($comma_separated != NULL && $comma_separated != "") {
    $sql = "select chat.texto, chat.codpessoa1, chat.codpessoa2, DATE_FORMAT(chat.dtcadastro, '%H:%i') as dtcadastro2, 
enviador.nome as enviador, chat.dtcadastro, chat.codchat, DATE_FORMAT(chat.dtcadastro, '%d/%m/%Y') as dtcadastro3,
DATE_FORMAT(chat.dtcadastro, '%Y-%m-%d') as dtcadastro4
from chat 
inner join pessoa as enviador on enviador.codpessoa = chat.codpessoa1 
inner join pessoa as receptor on receptor.codpessoa = chat.codpessoa2 
where chat.codpessoa2 = '{$_POST['logado']}' 
and chat.codpessoa1 = '{$_SESSION['codpessoa']}' 
$and  and chat.codchat in({$comma_separated})  
union
select chat.texto, chat.codpessoa1, chat.codpessoa2, DATE_FORMAT(chat.dtcadastro, '%H:%i:%s') as dtcadastro2, 
enviador.nome as enviador, chat.dtcadastro, chat.codchat, 
DATE_FORMAT(chat.dtcadastro, '%d/%m/%Y') as dtcadastro3,
DATE_FORMAT(chat.dtcadastro, '%Y-%m-%d') as dtcadastro4
from chat 
inner join pessoa as enviador on enviador.codpessoa = chat.codpessoa1 
inner join pessoa as receptor on receptor.codpessoa = chat.codpessoa2
where chat.codpessoa1 = '{$_POST["logado"]}' 
and chat.codpessoa2 = '{$_SESSION['codpessoa']}' 
$and  and chat.codchat in({$comma_separated})
order by codchat asc limit {$limite}";

    $reschat = $conexao->comando($sql)or die("<pre>$sql</pre>");
    $qtdchat = $conexao->qtdResultado($reschat);
    $frases_enviadas = '';
    $mensagem_recebida = '';
    if ($qtdchat > 0) {
        $enviadorAnterior = 0;
        $corAnterior = '';
        $data_mensagem = '';
        $passoData = false;
        $mensagemAnterior = '';
        $pessoaAnterior = 0;
        while ($chat = $conexao->resultadoArray($reschat)) {
            if ($mensagemAnterior != "" && $mensagemAnterior == $chat["texto"]) {
                continue;
            }
            $data_mensagem2 = identificaData($chat["dtcadastro4"]);
            if ($data_mensagem == '' || $data_mensagem != $data_mensagem2) {
                $data_mensagem = $data_mensagem2;
                $passoData = false;
            }
            if ($enviadorAnterior != 0 && $enviadorAnterior == $chat['enviador']) {
                $coraplicada = $corAnterior;
            }

            $enviador_por = explode(' ', $chat['enviador']);
            if ($data_mensagem != '' && $passoData == false) {
                $mensagem_recebida .= '<div class="msg" data-reactid=".0.$main.5.1.0.$ml-5511987263937@c=1us.$msg-true_5511987263937@c=1us_1446992315=1--48-date"><div class="message message-system" data-reactid=".0.$main.5.1.0.$ml-5511987263937@c=1us.$msg-true_5511987263937@c=1us_1446992315=1--48-date.0"><span class="message-system-body" data-reactid=".0.$main.5.1.0.$ml-5511987263937@c=1us.$msg-true_5511987263937@c=1us_1446992315=1--48-date.0.0"><span class="hidden-token" data-reactid=".0.$main.5.1.0.$ml-5511987263937@c=1us.$msg-true_5511987263937@c=1us_1446992315=1--48-date.0.0.0"><span data-reactid=".0.$main.5.1.0.$ml-5511987263937@c=1us.$msg-true_5511987263937@c=1us_1446992315=1--48-date.0.0.0.0">⁠⁠⁠⁠</span><span class="emojitext" dir="auto" data-reactid=".0.$main.5.1.0.$ml-5511987263937@c=1us.$msg-true_5511987263937@c=1us_1446992315=1--48-date.0.0.0.1">' . $data_mensagem . '</span><span data-reactid=".0.$main.5.1.0.$ml-5511987263937@c=1us.$msg-true_5511987263937@c=1us_1446992315=1--48-date.0.0.0.2">⁠⁠⁠⁠⁠</span></span></span></div></div>';
                $passoData = true;
            }
            $mensagem_recebida .= "<div class='msg' id='chat'>";
            $situacao = 'in';
            if ($chat["codpessoa1"] == $_SESSION['codpessoa']) {
                $situacao = 'out';
            }
            $mensagem_recebida .= "<div class='message message-" . $situacao . " tail'>";
            $mensagem_recebida .= "<div class='bubble bubble-text'>";
            $mensagem_recebida .= "<div class='message-text'>";
            $mensagem_recebida .= "<span class='message-pre-text'>";
            $mensagem_recebida .= "<span>";
            $mensagem_recebida .= "[" . $chat["dtcadastro3"] . "," + $chat["dtcadastro2"] . "]";
            $mensagem_recebida .= "</span>";
            $mensagem_recebida .= "</span>";
            $mensagem_recebida .= "<span class='emojitext selectable-text' dir='auto'>";
            $mensagem_recebida .= $chat["texto"];
            $mensagem_recebida .= "</span>";
            $mensagem_recebida .= "</div>";
            $mensagem_recebida .= "<div class='message-meta'>";
            $mensagem_recebida .= "<span class='hidden-token'>";
            $mensagem_recebida .= "<span class='message-datetime'>";
            $mensagem_recebida .= $chat["dtcadastro2"];
            $mensagem_recebida .= "</span>";
            $mensagem_recebida .= "</span>";
            $mensagem_recebida .= "</div>";
            $mensagem_recebida .= "</div>";
            $mensagem_recebida .= "</div>";
            $mensagem_recebida .= "</div>";
            $enviadorAnterior = $chat['enviador'];
            if ($chat["codpessoa1"] == $_SESSION['codpessoa']) {
                $setar = ", lidopor1 = '{$_SESSION['codpessoa']}'";
            } elseif ($chat["codpessoa2"] == $_SESSION['codpessoa']) {
                $setar = ", lidopor2 = '{$_SESSION['codpessoa']}'";
            }
            if (isset($_SESSION['codempresa']) && $_SESSION['codempresa'] != NULL && $_SESSION['codempresa'] != "") {
                $conexao->comando("update chat set lido = 's' {$setar} where codchat = '{$chat['codchat']}' and codempresa = '{$_SESSION['codempresa']}'");
            }
            $mensagemAnterior = $chat["texto"];
        }
        mysqli_free_result($reschat);
    }
    $html = ob_get_clean();
    echo preg_replace('/\s+/', ' ', str_replace("> <", "><", $html));
} else {
    $mensagem_recebida = '';
}
echo json_encode(array('logado' => $_POST['logado'], 'mensagens' => $mensagem_recebida));

function identificaData($data) {
    $data2 = 'sss';
    if ($data == date('Y-m-d')) {
        $data2 = "HOJE";
    } elseif ($data == date('Y-m-d', strtotime("-1 days"))) {
        $data2 = "ONTEM";
    } else {
        $data2 = date("d/m/Y", strtotime($data));
    }
    return $data2;
}

function trocaFrase($texto) {
    $texto = str_replace(":)", "<img width='20' src='/sistema/visao/recursos/img/emoticon_sorrindo.png' alt='emoticon sorrindo'/>", $texto);
    $texto = str_replace(":(", "<img width='20' src='/sistema/visao/recursos/img/emoticon_triste.png' alt='emoticon triste'/>", $texto);
    $texto = str_replace(";(", "<img width='20' src='/sistema/visao/recursos/img/emoticon_triste.png' alt='emoticon triste'/>", $texto);
    return $texto;
}
