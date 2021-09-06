<?php

session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
}
include "../model/Conexao.php";
include "../model/Proposta.php";

$conexao = new Conexao();
$proposta = new Proposta($conexao);

$msg_retorno = "";
$sit_retorno = true;

$proposta = new Proposta($conexao);
$proposta->codproposta = $_POST["codproposta"];
if(isset($_POST["valor_contrato"]) && $_POST["valor_contrato"] != NULL && $_POST["valor_contrato"] != ""){
    $proposta->vlliberado  = $_POST["valor_contrato"];
    $proposta->vlsolicitado  = $_POST["valor_contrato"];
}
if(isset($_POST["valor_contrato_comissao"]) && $_POST["valor_contrato_comissao"] != NULL && $_POST["valor_contrato_comissao"] != ""){
    $proposta->comissao_funcionario  = $_POST["valor_contrato_comissao"];
}
if(isset($_POST["valor_contrato_comissao_empresa"]) && $_POST["valor_contrato_comissao_empresa"] != NULL && $_POST["valor_contrato_comissao_empresa"] != ""){
    $proposta->valor_contrato_comissao_empresa  = $_POST["valor_contrato_comissao_empresa"];
}

$resInserirProposta    = $proposta->atualizar();
if($resInserirProposta == FALSE){
    die(json_encode(array('mensagem' => 'Erro ao atualizar comissão! Causado por:'. mysqli_error($conexao->conexao), 'situacao' => false)));
}

echo json_encode(array('mensagem' => 'Comissões atualizadas com sucesso!!!', 'situacao' => true));