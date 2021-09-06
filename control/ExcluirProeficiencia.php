<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";    
    $conexao = new Conexao();
    
    $msg_retorno = "";
    $sit_retorno = true; 

    $sql = "select perfil from proeficiencia where codproeficiencia = '{$_POST["codproeficiencia"]}'";
    $proeficiencia = $conexao->comandoArray($sql);
    $sql = "delete from proeficiencia where perfil = '{$proeficiencia["perfil"]}'";
    $res = $conexao->comando($sql);
    if($res !== FALSE){
        $msg_retorno = "Proeficiencia excluida com sucesso!!!";
    }else{
        $msg_retorno = "Erro ao proeficienciar causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }
    include "../model/Log.php";
    $log = new Log($conexao);
    
    
    $log->acao       = "excluir";
    $log->observacao = "Excluir proeficiencia - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();          
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));