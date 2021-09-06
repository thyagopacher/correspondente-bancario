<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Inadimplencia.php";
    $conexao = new Conexao();
    $inadimplencia = new Inadimplencia($conexao);
    
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $inadimplencia->$key = $value;
    }    
    
    $msg_retorno = "";
    $sit_retorno = true; 
    
    if(strpos($inadimplencia->cotacondominio, ",")){
        $inadimplencia->cotacondominio = str_replace(",", ".", $inadimplencia->cotacondominio);
    }
    if(strpos($inadimplencia->fundoreserva, ",")){
        $inadimplencia->fundoreserva = str_replace(",", ".", $inadimplencia->fundoreserva);
    }
    if(strpos($inadimplencia->juro, ",")){
        $inadimplencia->juro = str_replace(",", ".", $inadimplencia->juro);
    }
    if(strpos($inadimplencia->multa, ",")){
        $inadimplencia->multa = str_replace(",", ".", $inadimplencia->multa);
    }
    if(strpos($inadimplencia->rateioagua, ",")){
        $inadimplencia->rateioagua = str_replace(",", ".", $inadimplencia->rateioagua);
    }
    if(strpos($inadimplencia->txextra1, ",")){
        $inadimplencia->txextra1 = str_replace(",", ".", $inadimplencia->txextra1);
    }
    if(strpos($inadimplencia->txextra2, ",")){
        $inadimplencia->txextra2 = str_replace(",", ".", $inadimplencia->txextra2);
    }
    if(strpos($inadimplencia->valornrecebido, ",")){
        $inadimplencia->valornrecebido = str_replace(",", ".", $inadimplencia->valornrecebido);
    }
    if(strpos($inadimplencia->valorrecebido, ",")){
        $inadimplencia->valorrecebido = str_replace(",", ".", $inadimplencia->valorrecebido);
    }
    
    $inadimplencia->codfuncionario = $_SESSION['codpessoa'];
    $inadimplencia->codempresa = $_SESSION['codempresa'];
    $res = $inadimplencia->atualizar();
    if($res === FALSE){
        $msg_retorno = "Erro ao atualizar inadimplencia! Causado por:". mysqli_error($conexao->conexao);
        mail("thyago.pacher@gmail.com", "Erro sistema de condominios - control/AtualizarInadimplencia.php", $msg_retorno);   
        $sit_retorno = false;
    }else{
        $msg_retorno = "Inadimplência atualizada com sucesso!";             
    }
    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));