<?php
    session_start();
    include "../model/Conexao.php";
    include "../model/Tarefa.php";
    
    $conexao  = new Conexao();
    $tarefa  = new Tarefa($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        
    
    $tarefa2 = $conexao->comandoArray("select localizacao, imagem from tarefa where codtarefa = '{$_POST["codtarefa"]}'");
    if(!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == ""){
        $tarefa->codempresa = $_SESSION['codempresa'];
    }else{
        $tarefa->codempresa = $_POST["codempresa"];
    }    
    $res      = $tarefa->excluir($_POST["codtarefa"]);
    
    if($res === FALSE){
        $msg_retorno =  "Erro ao excluir tarefa! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        $resDelete = NULL;
        if(isset($tarefa2["imagem"]) && $tarefa2["imagem"] != NULL && $tarefa2["imagem"] != "" && file_exists("../tarefas/".$tarefa2["imagem"])){
            $resDelete = unimagem("../tarefas/".$tarefa2["imagem"]);
        }
        if($resDelete == false){
            $msg_retorno =  "Vinculo de tarefa apagado com sucesso do banco, porém não conseguiu apagar tarefa de imagem do servidor!";
            $sit_retorno = false;
        }else{
            $msg_retorno =  "Tarefa excluida com sucesso!";
        }
        include("../model/Log.php");
        $log = new Log($conexao);
        $log->codpessoa = $_SESSION['codpessoa'];
        
        $log->acao = "excluir";
        $log->codpagina = 3;
        $log->observacao = "Tarefa excluido: {$tarefa2["localizacao"]}";
        $log->inserir($log);             
    }    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));