<?php

session_start();
header('Content-Type: text/html; charset=utf-8');
echo '<link rel="stylesheet" type="text/css" href="/visao/recursos/css/style.min.css">';
echo '<link rel="stylesheet" type="text/css" href="/visao/recursos/css/tabela.css">';
$numero = $_GET["numero"];
if (isset($_POST["numero"])) {
    $numero = $_POST["numero"];
}
if (isset($numero) && $numero != NULL && $numero != "") {

    function __autoload($class_name) {
        if (file_exists("../model/" . $class_name . '.php')) {
            include "../model/" . $class_name . '.php';
        } elseif (file_exists("../visao/" . $class_name . '.php')) {
            include "../visao/" . $class_name . '.php';
        } elseif (file_exists("./" . $class_name . '.php')) {
            include "./" . $class_name . '.php';
        }
    }

    $conexao = new Conexao();
    $beneficio = new BeneficioCliente($conexao);

    if (strpos($numero, ";")) {
        $numeros = explode(";", $numero);
    } else {
        $numeros[0] = $numero;
    }
    foreach ($numeros as $key => $numero) {
        /*         * limpando NB para pesquisar */
        $numero = str_replace(".", "", $numero);
        $numero = str_replace("-", "", $numero);
        if (strlen($numero) == 9) {
            $numero = "0" . $numero;
        }
        echo '<h3>Informações de Beneficio</h3>';
        $consulta_nb = (array)$beneficio->consultaBenInss3($numero)[0];
        $log = new Log($conexao);
        $log->acao = "procurar";
        $log->codpagina = 4;
        $log->observacao = "Consulta Beneficio INSS : NB - {$numero}";
        $log->inserir();
        echo '<ul style="width: 100%;float: left;">';
        echo '<li>Beneficio: ', $consulta_nb['BENEFICIO'],' - Espécie: ', $consulta_nb['ESP'], ' - DIB: ', $consulta_nb['DIB'],'</li>';
        echo '<li>Nome: ',$consulta_nb['NOME'],' - Nascto: ',$consulta_nb["NASCTO"],' - CPF: ',$consulta_nb['CPF'],'</li>';
        echo '<li>MR: ',$consulta_nb['MR'], ' - Margem: ', $consulta_nb['MARGEM'], '</li>';
        echo '<li>Banco Pgto: ', $consulta_nb['BANCO_PAGTO'], ' - Agência Pgto: ',$consulta_nb['AGENCIA_PAGTO'],' - Conta Pgto: ',$consulta_nb['CONTA_PAGTO'],'</li>';
        echo '<li>Orgao Pagador: ', $consulta_nb['ORGAO_PAGADOR'], ' - Tipo Pgto: ', $consulta_nb['TIPO_PAGTO'], '</li>';
        echo '<li>Endereço: ', $consulta_nb["ENDERECO"], ' - Bairro: ', $consulta_nb['BAIRRO'], ' - Cidade: ', $consulta_nb['CIDADE'], '</li>';
        if($consulta_nb["Obito"] == 0){
            echo '<li>Óbito: NÃO </li>';
        }else{
            echo '<li>Óbito: SIM </li>';
        }
        if($consulta_nb["Cessado"] == 0){
            echo '<li>Cessado: NÃO </li>';
        }else{
           echo '<li>Cessado: SIM </li>'; 
        }
        echo '</ul>';
    }
} else {
    echo 'Não pode consultar sem número de beneficio INSS...';
}
    
