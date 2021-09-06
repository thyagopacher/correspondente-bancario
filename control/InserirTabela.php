<?php

session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
}

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
$tabela = new Tabela($conexao);

$msg_retorno = "";
$sit_retorno = true;

$variables = (strtolower($_SERVER["REQUEST_METHOD"]) == "GET") ? $_GET : $_POST;
foreach ($variables as $key => $value) {
    $tabela->$key = $value;
}
$tabela->codempresa = $_SESSION['codempresa'];
$tabela->codfuncionario = $_SESSION['codpessoa'];

$sql = 'select codtabela from tabela where nome = "' . $tabela->nome . '" and codbanco = '.$tabela->codbanco.' and codconvenio = '.$tabela->codconvenio.' and codempresa = ' . $_SESSION["codempresa"];
$tabelap = $conexao->comandoArray($sql);

if (isset($tabelap["codtabela"])) {
    die(json_encode(array('mensagem' => "Já inseriu tabela com esse nome, vá em procurar e clique em editar caso queira mudar seus prazos!!!", 'situacao' => false)));
}
$res = $tabela->inserir();
$codigo_tabela = mysqli_insert_id($conexao->conexao);
if ($res === FALSE) {
    $msg_retorno = "Erro ao cadastrar tabela! Causado por:" . mysqli_error($conexao->conexao);
    mail("thyago.pacher@gmail.com", "Erro sistema de condominios - control/InserirTabela.php", $msg_retorno);
    $sit_retorno = false;
} else {
    $msg_retorno = "Tabela cadastrada com sucesso!";
    $sit_retorno = true;

    foreach ($_POST["prazode"] as $key => $prazode) {
        if (isset($_POST["comissao"][$key]) && $_POST["comissao"][$key] != NULL && $_POST["comissao"][$key] != "") {
            $tabelap = new TabelaPrazo($conexao);
            $tabelap->dtinicio = $_POST["dtinicio"][$key];
            $tabelap->dtfim = $_POST["dtfim"][$key];

            $tabelap->prazoate = $_POST["prazoate"][$key];
            $tabelap->prazode = $prazode;

            $tabelap->comissao = $_POST["comissao"][$key];
            $tabelap->bonus = $_POST["bonus"][$key];
            $tabelap->rco = str_replace(',', '.', $_POST["rco"][$key]);
            $tabelap->codfuncionario = $_SESSION['codpessoa'];
            $tabelap->codtabela = $codigo_tabela;
            $tabelap->dtcadastro = date("Y-m-d H:i:s");
            $tabelap->pgto_liquido = $_POST["pgto_liquido"][$key];
            $tabelap->rco = $_POST["rco"][$key];
            $resInserirPrazo = $tabelap->inserir();
            $codigo_tabelap = mysqli_insert_id($conexao->conexao);
            if ($resInserirPrazo == FALSE) {
                die(json_encode(array('mensagem' => "Erro ao inserir tabela X prazo", 'situacao' => false)));
            } else {
                $sql = 'select codnivel, nome from nivel where ((padrao = "s" and codnivel = 16) or (codempresa = ' . $_SESSION["codempresa"] . ' and padrao = "n")) and codnivel <> 1 and codnivel <> 19';
                $resnivel = $conexao->comando($sql);
                $qtdnivel = $conexao->qtdResultado($resnivel);
                if ($qtdnivel > 0) {

                    while ($nivel = $conexao->resultadoArray($resnivel)) {

                        $pctNivel = new PctNivelTabelaPrazo($conexao);
                        $pctNivel->codnivel = $nivel["codnivel"];
                        $pctNivel->codtabelap = $codigo_tabelap;
                        $pctNivel->porcentagem = $_POST["pctnivel"][$key];
                        $pctNivel->inserir();
                    }
                }
            }
        }
    }
}

echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
