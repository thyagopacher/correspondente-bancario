<?php
if(isset($_POST["codempresa"]) && $_POST["codempresa"] != NULL && $_POST["codempresa"] != ""){
    session_start();
    include("../model/Conexao.php");
    $conexao = new Conexao();
    $_SESSION['codempresa'] = $_POST["codempresa"];
    $empresa = $conexao->comandoArray("select * from empresa where codempresa = '{$_SESSION['codempresa']}'");
    die(json_encode(array('mensagem' => "Mudança efetuada com sucesso para empresa {$empresa["razao"]}!", 'situacao' => true)));
}else{
    die(json_encode(array('mensagem' => "Não passou parametro da empresa", 'situacao' => false)));
}

  