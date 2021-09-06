<?php

function __autoload($class_name) {
    if (file_exists('../model/' . $class_name . '.php')) {
        include '../model/' . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}

session_start();

if(!isset($_SESSION)){
    die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
}

if(isset($_GET["cpf"]) && $_GET["cpf"] != NULL && $_GET["cpf"] != ""){
    $_POST["cpf"] = $_GET["cpf"];
}

if (!isset($_POST["cpf"]) || $_POST["cpf"] == NULL || $_POST["cpf"] == "") {
    die(json_encode(array('mensagem' => "Não pode consultar sem cpf!!!", 'situacao' => false)));
}

$conexao = new Conexao();


$southconsultap = $conexao->comandoArray('select validade, dtcadastro, qtdconsulta  from southconsulta as sc where sc.codempresa = '. $_SESSION["codempresa"]. ' order by codconsulta desc limit 1');
if(isset($southconsultap["validade"]) && $southconsultap["validade"] != NULL && $southconsultap["validade"] != ""){
    $diaMais        = date('Y-m-d', strtotime('+'.$southconsultap["validade"].' days', strtotime($southconsultap["dtcadastro"])));
    $time_inicial   = strtotime(date("Y-m-d"));
    $time_final     = strtotime($diaMais);
    $diferenca      = $time_final - $time_inicial; // 19522800 segundos
    $diasExpira     = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
    
    $diasMaisBrasil = date("d/m/Y", strtotime($diaMais));
}

//consultas usadas
$consultassouthp = $conexao->comandoArray('select count(1) as qtd from consultassouth as cs where cs.codempresa = '. $_SESSION["codempresa"]);
$limiteConsulta  = $southconsultap["qtdconsulta"] - $consultassouthp["qtd"];
if($limiteConsulta <= 0){
    die(json_encode(array('mensagem' => 'Acabaram seus créditos por favor consulte a administração!!!', 'situacao' => false)));
}

$pessoa  = new Pessoa($conexao);
$info    = new InfoPesq();
$retorno = $info->procuraCPF($_POST["cpf"]);
$pessoap = $conexao->comandoArray('select codpessoa from pessoa where codempresa = '. $_SESSION["codempresa"]. ' and cpf = "'. $_POST["cpf"]. '"');

if(isset($retorno) && $retorno != NULL && (isset($retorno->FONESMOVEL->FONEMOVEL) || isset($retorno->FONESFIXO->FONEFIXO))){
    $telefone = new Telefone($conexao);
    $telefone->codpessoa = $pessoap["codpessoa"];
    $telefone->numero    = $retorno->FONESMOVEL->FONEMOVEL;
    $telefone->codtipo   = 3;
    $telefone->operadora = $retorno->FONESMOVEL->OPERADORA;
    $resAtualizaPessoa   = $telefone->inserir();
    
    $telefone = new Telefone($conexao);
    $telefone->codpessoa = $pessoap["codpessoa"];
    $telefone->numero    = $retorno->FONESFIXO->FONEFIXO;
    $telefone->codtipo   = 1;
    $telefone->operadora = $retorno->FONESFIXO->OPERADORA;
    $resAtualizaPessoa   = $telefone->inserir();
    if($resAtualizaPessoa != FALSE){
        $cs                 = new ConsultasSouth($conexao);
        $cs->campo          = 'cpf';
        $cs->valor          = $_POST["cpf"];
        $resInserirConsulta = $cs->inserir();
        if($resInserirConsulta == FALSE){
            die(json_encode(array('mensagem' => 'Não conseguiu gravar log de consulta!!!', 'situacao' => false)));
        }   
        $cliente  = (array)$retorno->CLIENTE;
        $msgCompl = ' - Obito: '. $cliente["CONS.OBITO"].'<br>';        
        die(json_encode(array('mensagem' => 'Pesquisa realizada com sucesso e dados importados para ficha cliente!'. $msgCompl, 'situacao' => true)));
    }else{
        die(json_encode(array('mensagem' => 'Erro ao atualizar. Causado por erro no banco de dados: ', mysqli_error($conexao->conexao), 'situacao' => false)));
    }
}elseif(isset($retorno[0])){
    die(json_encode(array('mensagem' => 'Msg. retorno South Busca:'. $retorno[0], 'situacao' => false)));
}else{
    die(json_encode(array('mensagem' => 'Nada retornou da pesquisa!!!', 'situacao' => false)));
}
    