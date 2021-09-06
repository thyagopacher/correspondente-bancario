<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Site.php";
    
    $conexao = new Conexao();
    $site  = new Site($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true;        

    $res = $site->excluir($_POST["codsite"]);
    
    if($res === FALSE){
        $msg_retorno =  "Erro ao excluir site! Causado por:". mysqli_error($conexao->conexao);
        $sit_retorno = false;
    }else{
        if(isset($site2["logo"]) && $site2["logo"] != NULL && $site2["logo"] != ""){
            unlink("../arquivos/".$site2["logo"]);
        }
        $msg_retorno =  "Site excluido com sucesso!";           
    }    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));