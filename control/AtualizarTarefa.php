<?php

session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   

include "../model/Conexao.php";
include "../model/Tarefa.php";
$conexao = new Conexao();
$tarefa = new Tarefa($conexao);

$msg_retorno = "";
$sit_retorno = true;
$envioerro = "";
$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
foreach ($variables as $key => $value) {
    $tarefa->$key = $value;
}
if (isset($_FILES['imagem']) && $_FILES['imagem'] != NULL) {
    include "Upload.php";
    $upload = new Upload($_FILES['imagem']);
    if ($upload->erro == "") {
        $tarefa->imagem = $upload->nome_final;
    }
}

if ($sit_retorno) {

    if($tarefa->resolvido == 's'){
        $tarefa->data_resolvido = date("Ymd");
        $tarefa->hora_resolvido = date('H:i:s');
    }
    $res = $tarefa->atualizar();
    
    if ($res === FALSE) {
        $msg_retorno = "Erro ao atualizar imagem! Causado por:" . mysqli_error($conexao->conexao);
        mail("thyago.pacher@gmail.com", "Erro sistema de condominios - control/AtualizarTarefa.php", $msg_retorno);
        $sit_retorno = false;
    } else {
        if($tarefa->resolvido == 's'){
            $sql = "select codfuncionario from tarefa where codtarefa = '{$tarefa->codtarefa}'";
            $tarefap = $conexao->comandoArray($sql);
            $sql = "select nome, email from pessoa where codpessoa = '{$tarefap["codfuncionario"]}'";
            $funcionario = $conexao->comandoArray($sql);
            $origem = $conexao->comandoArray("select nome, email from pessoa where codpessoa = '{$_SESSION['codpessoa']}' and codempresa = '{$_SESSION['codempresa']}'");
            include("Email.php");
            $email = new Email();
            $email->origem = $origem["nome"];
            $email->origem_email = $origem["email"];
            $email->para = $funcionario["nome"];
            $email->para_email = $funcionario["email"];
            $email->assunto = "Tarefa terminada!";
            $email->mensagem = "Tarefa setada como resolvido - {$tarefap["nome"]} no dia ".date("d/m/Y H:i:s")."!";
            $email->mensagem .= "Link para acompanhar: http://{$_SERVER["SERVER_NAME"]}/sistema/visao/Tarefa.php?codtarefa=".$tarefa->codtarefa;
            $resenvio = $email->envia();
            if($resenvio == FALSE){
                $envioerro = "Não conseguiu enviar e-mail! Erro causado por:". $email->erro;
            }else{
                $envioerro = "E-mail enviado avisando!";
            }
        }
        $msg_retorno = "Tarefa atualizado com sucesso!{$envioerro}";
    }
}
if(isset($upload->erro) && $upload->erro != NULL && $upload->erro != ""){
    $msg_retorno .= " Problemas com o envio do arquivo: ". $upload->erro;
}
echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
