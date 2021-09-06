<?php
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
$conexao = new Conexao();



$and = "";
//    if(isset($_POST["codnivel"]) && $_POST["codnivel"] != NULL && $_POST["codnivel"] != ""){
//        $and .= " and tabela.codtabela in(select codtabela from tabelanivel where codempresa = '{$_SESSION['codempresa']}' and codnivel = '{$_POST["codnivel"]}')";
//    }
$empresap = $conexao->comandoArray('select codcategoria, codpessoa from empresa where codempresa = ' . $_SESSION["codempresa"]);
$and .= ' and (empresa.codempresa = ' . $_SESSION["codempresa"] . ' or empresa.codpessoa in(select codpessoa from pessoa where codempresa = ' . $_SESSION["codempresa"] . '))';

if (isset($_POST["codconvenio"]) && $_POST["codconvenio"] != NULL && $_POST["codconvenio"] != "") {
    $and .= " and tabela.codconvenio = '{$_POST["codconvenio"]}'";
}
if (isset($_POST["prazode"]) && $_POST["prazode"] != NULL && $_POST["prazode"] != "") {
    $and .= " and tabelaprazo.prazode = '{$_POST["prazode"]}'";
}
if (isset($_POST["prazode"]) && $_POST["prazode"] != NULL && $_POST["prazode"] != "") {
    $and .= " and tabelaprazo.prazode = '{$_POST["prazode"]}'";
}
if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $and .= " and tabela.nome like '%{$_POST["nome"]}%'";
}
if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $and .= " and tabela.nome like '%{$_POST["nome"]}%'";
}
if (isset($_POST["codbanco"]) && $_POST["codbanco"] != NULL && $_POST["codbanco"] != "") {
    $and .= " and banco.codbanco = '{$_POST["codbanco"]}'";
}

if (isset($_POST["data1"]) && $_POST["data1"] != NULL) {
    $and .= " and tabela.dtcadastro >= '{$_POST["data1"]}'";
}
if (isset($_POST["data2"]) && $_POST["data2"] != NULL) {
    $and .= " and tabela.dtcadastro <= '{$_POST["data2"]}'";
}
$sql = "select tabela.codtabela, tabela.nome, DATE_FORMAT(tabela.dtcadastro, '%d/%m/%Y') as dtcadastro2, 
    banco.nome as banco, convenio.nome as convenio, pessoa.nome as funcionario
    from tabela
    inner join banco on banco.codbanco = tabela.codbanco
    inner join convenio on convenio.codconvenio = tabela.codconvenio
    inner join pessoa on pessoa.codpessoa = tabela.codfuncionario
    inner join empresa on empresa.codempresa = tabela.codempresa
    where 1 = 1 {$and} and tabela.codempresa = {$_SESSION["codempresa"]} order by tabela.dtcadastro desc";

$res = $conexao->comando($sql);
$qtd = $conexao->qtdResultado($res);

if ($qtd > 0) {
    echo "Encontrou {$qtd} resultados<br>";
    ?>


    <table class="tabela_procurar table table-hover">
        <thead>
            <tr>
                <th>
                    Nome
                </th>
                <th>
                    Dt. Cadastro
                </th>
                <th>
                    Convênio
                </th>
                <th>
                    Banco
                </th>
                <th>
                    Por
                </th>
                <th>
                    Opções
                </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($tabela = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td>
                        <?= $tabela["nome"] ?>
                    </td>
                    <td>
                        <?= $tabela["dtcadastro2"] ?>
                    </td>
                    <td>
                        <?= $tabela["convenio"] ?>
                    </td>
                    <td>
                        <?= $tabela["banco"] ?>
                    </td>
                    <td>
                        <?= $tabela["funcionario"] ?>
                    </td>

                    <td>
                        <?php
                        echo '<a href="Tabela.php?codtabela=', $tabela["codtabela"], '" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="#" onclick="excluirTabela(', $tabela["codtabela"], ')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                        if ($_SESSION["codnivel"] == 1) {
                            $sql = "select codtabela from tabelanivel where codtabela = '{$tabela["codtabela"]}' and codnivel = '{$_POST["codnivel"]}'";
                            $tabelnivelp = $conexao->comandoArray($sql);
                            if (isset($tabelnivelp["codtabela"]) && $tabelnivelp["codtabela"] != NULL && $tabelnivelp["codtabela"] != "") {
                                echo '<input checked class="tabela_selecao" type="checkbox" name="tabela_selecao[]" id="tabela_selecao', $tabela["codtabela"], '" value="', $tabela["codtabela"], '"/>';
                            } else {
                                echo '<input class="tabela_selecao" type="checkbox" name="tabela_selecao[]" id="tabela_selecao', $tabela["codtabela"], '" value="', $tabela["codtabela"], '"/>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>
    <?php
}
?>