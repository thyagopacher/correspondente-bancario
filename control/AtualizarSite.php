<?php
    include "../model/Conexao.php";
    include "../model/Site.php";
    $conexao = new Conexao();
    $site = new Site($conexao);
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $site->$key = $value;
    }    
    
    if (isset($_FILES["logo"])) {
        $res = move_uploaded_file($_FILES["logo"]["tmp_name"], "../visao/recursos/img/logo.png");
        if($res == FALSE){
            die(json_encode(array('mensagem' => "Problemas ao atualizar logo site", 'situacao' => false)));
        }
    }    
    
    $msg_retorno = "";
    $sit_retorno = true; 
    $res = $site->atualizar();
    if($res === FALSE){
        $msg_retorno = "Erro ao inserir site! Causado por:". mysqli_error($conexao->conexao);
        mail("thyago.pacher@gmail.com", "Erro sistema de condominios - control/AtualizarSite.php", $msg_retorno);   
        $sit_retorno = false;
    }else{
        $msg_retorno = "Site atualizado com sucesso!";             
    }
    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));