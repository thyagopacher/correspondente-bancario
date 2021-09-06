<?php
session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   


include "../model/Conexao.php";
include "../model/Pessoa.php";

$conexao = new Conexao();
$pessoa = new Pessoa($conexao);

$msg_retorno = "";
$sit_retorno = true;

$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_REQUEST;
foreach ($variables as $key => $value) {
    $pessoa->$key = $value;
}
$conexao->comando("delete from acessooperador where codoperador = '{$pessoa->codpessoa}' and codempresa = '{$_SESSION['codempresa']}'");
$qtdAcesso = $conexao->comandoArray("select count(1) as qtd from acessooperador where codoperador = '{$pessoa->codpessoa}' and codempresa = '{$_SESSION['codempresa']}'");
if($qtdAcesso["qtd"] > 0){
    
    die(json_encode(array('mensagem' => "Pessoa não pode ser excluida pois tem acessos vinculados a ela, primeiro exclua seus acessos!!!", 'situacao' => false)));
}

$qtdAcesso = $conexao->comandoArray("select count(1) as qtd from atendimento where codfuncionario = '{$pessoa->codpessoa}' and codempresa = '{$_SESSION['codempresa']}'");
if($qtdAcesso["qtd"] > 0){
    die(json_encode(array('mensagem' => "Pessoa não pode ser excluida pois tem atendimentos vinculados a ela, primeiro exclua seus atendimentos!!!", 'situacao' => false)));
}

$sql = "select count(1) as qtd from telefone where codfuncionario = '{$pessoa->codpessoa}' and codempresa = '{$_SESSION['codempresa']}'";
$qtdTelefone = $conexao->comandoArray($sql);
if($qtdTelefone["qtd"] > 0){
    die(json_encode(array('mensagem' => "Pessoa não pode ser excluida pois tem telefones que foram cadastrados para clientes por ela, primeiro exclua seus telefones!!!", 'situacao' => false)));
}

$qtdAcesso = $conexao->comandoArray("select count(1) as qtd from estadocivil where codfuncionario = '{$pessoa->codpessoa}'");
if($qtdAcesso["qtd"] > 0){
    die(json_encode(array('mensagem' => "Pessoa não pode ser excluida pois ela cadastro estado civil, primeiro exclua esses dados!!!", 'situacao' => false)));
}

$sql = "delete from atendimento where codpessoa = '{$pessoa->codpessoa}' and codempresa = '{$_SESSION['codempresa']}'";
$conexao->comando($sql)or die("<pre>$sql</pre>");

$sql = "delete from telefone where codpessoa = '{$pessoa->codpessoa}' and codempresa = '{$_SESSION['codempresa']}'";
$conexao->comando($sql)or die("<pre>$sql</pre>");

$sql = "delete from beneficiocliente where codpessoa = '{$pessoa->codpessoa}' and codempresa = '{$_SESSION['codempresa']}'";
$conexao->comando($sql)or die("<pre>$sql</pre>");

$pessoa->codempresa = $_SESSION['codempresa'];
$res = $pessoa->excluir($_POST["codpessoa"]);

if ($res === FALSE) {
    $msg_retorno = "Erro ao excluir pessoa! Causado por:" . mysqli_error($conexao->conexao);
    $sit_retorno = false;
} else{
    $msg_retorno = "Pessoa excluida com sucesso!";
}

echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
