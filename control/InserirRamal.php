<?php
    
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/Ramal.php";
    $conexao = new Conexao();
    $ramal = new Ramal($conexao);
    
    $msg_retorno = "";
    $sit_retorno = true; 
    $variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
    foreach($variables as $key => $value){
        $ramal->$key = $value;
        $dadosAtualizacao .= "$key = $value;";
    }    
    $ramalp = $conexao->comandoArray("select * from ramal where nome = '{$ramal->nome}' and externo = '{$ramal->externo}' and ramal = '{$ramal->ramal}'");
    if(isset($ramalp) && isset($ramalp["nome"]) && $ramalp["nome"] != NULL && $ramalp["nome"] != ""){
        die(json_encode(array('mensagem' => "Telefone já cadastrado!!!", 'situacao' => false)));
    }

    if((!isset($ramal->ramal) || $ramal->ramal == NULL || $ramal->ramal == "") && (!isset($ramal->telefone) || $ramal->telefone == NULL || $ramal->telefone == "")){
        die(json_encode(array('mensagem' => "Não pode cadastrar sem ramal e sem telefone!!!", 'situacao' => false)));
    }elseif(strlen($ramal->ramal) == 1){
        die(json_encode(array('mensagem' => "Não pode cadastrar ramal com somente 1 digito!!!", 'situacao' => false)));
    }
    
    $numero = $ramal->soNumero($ramal->telefone);
    if(strlen($numero) == 10){
        $ramal->telefone = $ramal->mask($numero);
    }else{
        $ramal->telefone = $ramal->mask($numero, "(##)#####-####");
    }
    
    if(!isset($ramal->nome) || $ramal->nome == NULL || $ramal->nome == ""){
        $msg_retorno =  "Não pode inserir sem nome !";
        $sit_retorno = false;
    }else{    
        $ramal->codempresa = $_SESSION['codempresa'];
        $res = $ramal->inserir();

        if($res === FALSE){
            $msg_retorno =  "Erro ao inserir ramal! Causado por:". mysqli_error($conexao->conexao);
            $sit_retorno = false;
        }else{
            $msg_retorno =  "Ramal inserido com sucesso!";
        }
    }    
    
    include "../model/Log.php";
    $log             = new Log($conexao);
    $log->acao       = "inserir";
    
    $log->codpagina  = 39;
    
    $log->data       = date('Y-m-d');
    $log->hora       = date('H:i:s');
    $log->observacao = $msg_retorno . " - ". $dadosAtualizacao;
    $log->inserir();
    
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));