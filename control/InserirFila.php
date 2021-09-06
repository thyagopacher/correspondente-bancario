<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
    include "../model/Conexao.php";
    include "../model/FilaEmail.php"; 
    
    $msg_retorno = "";
    $sit_retorno = true;       
    $conexao = new Conexao();
    $fila = new FilaEmail($conexao);
    $sqlPessoa = "select codpessoa, nome, email from pessoa order by nome";
    $resPessoa = $conexao->comando($sqlPessoa);
    $qtdPessoa = $conexao->qtdResultado($resPessoa);
    if($qtdPessoa > 0){
        while($pessoa = $conexao->resultadoArray($resPessoa)){
            $fila->codpessoa = $pessoa["codpessoa"];
            $fila->assunto = $_POST["assunto"];
            $fila->texto = $_POST["texto"];
            $fila->codempresa = $_SESSION['codempresa'];
            $fila->codfuncionario = $_SESSION['codpessoa'];
            $fila->dtcadastro = date("Y-m-d H:i:s");
            $fila->situacao = "n";
            $fila->tipo = "r";
            $res = $fila->inserir();
            if($res == FALSE){
                $msg_retorno = "Erro ao inserir na fila de envios causado por:".mysqli_error($conexao->conexao);
                $sit_retorno = false;
                break;
            }
        }
    }else{
        $msg_retorno = "Ninguém encontrado para enviar!";
        $sit_retorno = false;        
    }
    if($sit_retorno){
        $msg_retorno = "Resumo enviado com sucesso para {$qtdPessoa} pessoas!";
    }
    echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
    
    