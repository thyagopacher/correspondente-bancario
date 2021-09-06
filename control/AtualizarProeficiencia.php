<?php
    session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }       
    include "../model/Conexao.php";
    include "../model/Proeficiencia.php";
    
    $conexao = new Conexao();    
    $msg_retorno = "";
    $sit_retorno = true; 
    
    $conexao->comando("delete from proeficiencia where perfil = '{$_POST["perfil"]}'");
    
    foreach ($_POST['valor'] as $key => $valor) {
        $proeficiencia = new Proeficiencia($conexao);
        $proeficiencia->codfuncionario = $_SESSION['codpessoa'];
        $proeficiencia->dtcadastro = date("Y-m-d H:i:s");
        $proeficiencia->dtvigencia = $_POST["dtvigencia"][$key];
        $proeficiencia->dtvigenciaIni = $_POST["dtvigenciaIni"][$key];
        $proeficiencia->margem = $_POST["margem"][$key];
        $proeficiencia->perfil = $_POST["perfil"];
        $proeficiencia->remuneracao = str_replace(",", ".", $_POST["remuneracao"][$key]);
        $proeficiencia->valor = str_replace(",", ".", $_POST['valor'][$key]);
        $res = $proeficiencia->inserir();   
        if($res == FALSE){
            die(json_encode(array('mensagem' => "Erro ao proeficienciar causado por:". mysqli_error($conexao->conexao), 'situacao' => false)));
        }
    }
    
    die(json_encode(array('mensagem' => "Proeficiencia salva com sucesso!", 'situacao' => true)));
    
    include "../model/Log.php";
    $log = new Log($conexao);
    
    
    $log->acao       = "atualizar";
    $log->observacao = "Atualizada proeficiencia - em ". date('d/m/Y'). " - ". date('H:i');
    $log->codpagina  = "0";
    
    $log->hora = date('H:i:s');
    $log->inserir();          
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));