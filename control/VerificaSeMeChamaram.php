<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
    include "../model/Conexao.php";
    $conexao = new Conexao();
    $sql = "select distinct pessoa.codpessoa, pessoa.nome as logado 
    from chat
    inner join pessoa on pessoa.codpessoa = chat.codpessoa1
    where chat.codpessoa2 = '{$_SESSION['codpessoa']}' 
    and chat.dtcadastro >= '".date('Y-m-d')." 00:00:00'
    and chat.dtcadastro <= '".date('Y-m-d')." 23:59:59'
    and chat.finalizado <> 's' order by chat.codchat desc";

    $reschat = $conexao->comando($sql);
    $qtdchat = $conexao->qtdResultado($reschat);
    if($qtdchat > 0){
        while($chat = $conexao->resultadoArray($reschat)){
            $logados      .= "{$chat["codpessoa"]};";
            $logados_nome .= "{$chat["logado"]};";
        }
    }

    if(isset($logados) && $logados != ""){
        die(json_encode(array('mensagem' => "Chamado aberto!!!", 'situacao' => true, 'logado' => $logados, 'logado_nome' => $logados_nome)));
    }else{
        die(json_encode(array('mensagem' => "Nada aberto", 'situacao' => false)));
    }