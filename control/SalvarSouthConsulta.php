<?php

session_start();


function __autoload($class_name) {
    if (file_exists('../model/' . $class_name . '.php')) {
        include '../model/' . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}

$conexao = new Conexao();
$sc      = new SouthConsulta($conexao);

$msg_retorno = '';
$sit_retorno = true;

$variables = (strtolower($_SERVER['REQUEST_METHOD']) == 'GET') ? $_GET : $_POST;
foreach ($variables as $key => $value) {
    $sc->$key = $value;
}

if(isset($sc->codconsulta) && $sc->codconsulta != NULL && $sc->codconsulta != ""){
    $resInserirConsulta = $sc->atualizar();
}else {
    
    $southconsultap = $conexao->comandoArray('select sc.* from southconsulta as sc where sc.codempresa = '. $sc->codempresa. ' order by sc.codconsulta desc limit 1');
    $diaMais        = date('Y-m-d', strtotime('+'.$southconsultap["validade"].' days', strtotime($southconsultap["dtcadastro"])));
    
    $scp = $conexao->comandoArray('select codconsulta 
    from southconsulta 
    where codempresa = '. $sc->codempresa. ' and dtcadastro < '. $diaMais);
    if(isset($scp["codconsulta"]) && $scp["codconsulta"] != NULL && $scp["codconsulta"] != ""){
        die(json_encode(array('mensagem' => 'Consulta ja cadastrada, por favor verifique!', 'situacao' => false)));
    }
    $resInserirConsulta = $sc->inserir();
}

if($resInserirConsulta == FALSE){
    die(json_encode(array('mensagem' => 'Erro ao salvar:'. mysqli_error($conexao->conexao), 'situacao' => false)));
}else{
    die(json_encode(array('mensagem' => 'Consulta South salva com sucesso!!!', 'situacao' => true)));
}


