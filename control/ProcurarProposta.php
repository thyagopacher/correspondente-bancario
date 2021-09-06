<?php
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
include "../model/Proposta.php";
$conexao = new Conexao();
$proposta = new Proposta($conexao);

$and = "";
if (isset($_POST["codcliente"]) && $_POST["codcliente"] != NULL && $_POST["codcliente"] != "") {
    $and .= " and proposta.codcliente = '{$_POST["codcliente"]}' and proposta.codstatus <> 22";
}
if (isset($_POST["codstatus"]) && $_POST["codstatus"] != NULL && $_POST["codstatus"] != "") {
    $and .= " and proposta.codstatus = '{$_POST["codstatus"]}'";
}
if (isset($_POST["cpf"]) && $_POST["cpf"] != NULL && $_POST["cpf"] != "") {
    $and .= " and cliente.cpf like '{$_POST["cpf"]}%'";
}
if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $and .= " and proposta.nome like '%{$_POST["nome"]}%'";
}
if (isset($_POST["data1"]) && $_POST["data1"] != NULL) {
    $and .= " and proposta.dtcadastro >= '{$_POST["data1"]}'";
}
if (isset($_POST["data2"]) && $_POST["data2"] != NULL) {
    $and .= " and proposta.dtcadastro <= '{$_POST["data2"]}'";
}
if (isset($_SESSION["codnivel"]) && $_SESSION["codnivel"] == "16") {
    $and .= " and proposta.codfuncionario = '{$_SESSION['codpessoa']}'";
}
$sql = "select proposta.codproposta, proposta.nome, DATE_FORMAT(proposta.dtcadastro, '%d/%m/%Y') as dtcadastro2, proposta.codfuncionario, 
    funcionario.nome as funcionario, cliente.nome as cliente, cliente.cpf, proposta.vlsolicitado, convenio.nome as convenio, proposta.codbanco, proposta.codconvenio, 
    proposta.codtabela, proposta.prazo, banco.nome as banco, tabela.nome as tabela, status.nome as status, proposta.codstatus, proposta.codcliente, proposta.vlparcela, proposta.vlliberado, proposta.codbeneficio,
    status.cor, proposta.codbanco2, proposta.agencia, proposta.conta, proposta.operacao, proposta.poupanca, proposta.dtvenda, proposta.observacao, proposta.pendente,
    DATE_FORMAT(proposta.dtpago, '%d/%m/%Y') as dtpago2, proposta.dtpago, cliente.codcategoria as categoria_cliente, proposta.beneficio, proposta.codespecie, proposta.corrente
    from proposta
    inner join pessoa as funcionario on funcionario.codpessoa = proposta.codfuncionario 
    inner join pessoa as cliente on cliente.codpessoa = proposta.codcliente
    inner join convenio on convenio.codconvenio = proposta.codconvenio
    inner join banco on banco.codbanco = proposta.codbanco
    inner join tabela on tabela.codtabela = proposta.codtabela
    inner join statusproposta as status on status.codstatus = proposta.codstatus
    inner join empresa on empresa.codempresa = proposta.codempresa
    where 1 = 1 and (empresa.codempresa = {$_SESSION["codempresa"]} or empresa.codpessoa in(select codpessoa from pessoa where codempresa = {$_SESSION["codempresa"]}))
    {$and} order by statusprioritario desc,proposta.dtcadastro desc";

$res = $conexao->comando($sql);
$qtd = $conexao->qtdResultado($res);
if ($qtd > 0) {
    ?>
                                        
                <table class="tabela_procurar">
                        <thead>
                            <tr>
                                <th>
                                    Dt. Cadastro
                                </th>
                                <th>
                                    Banco
                                </th>
                                <th>
                                    Convênio
                                </th>
                                <th>
                                    Tabela
                                </th>
                                <th>
                                    Prazo
                                </th>
                                <th>
                                    Valor
                                </th>
                                <th>
                                    Dt. Pgto
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Cliente
                                </th>
                                <?php if (isset($_POST["codcliente"])) { ?>
<th>
                                        Opções
                                    </th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($proposta = $conexao->resultadoArray($res)) {
                                $classe_linha = $proposta["cor"]; 
                                
                                $sql = "select observacao from observacaoproposta as obs where obs.codempresa = '{$_SESSION['codempresa']}' and obs.codcliente = '{$proposta["codcliente"]}' and obs.observacao <> '' and codstatus = '7' order by codobservacao desc";
                                $observacao = $conexao->comandoArray($sql);
                                ?>
                                <tr>
                                    <td>
                                        <?= $proposta["dtcadastro2"] ?>
                                    </td>
                                    <td>
                                        <?= $proposta["banco"] ?>
                                    </td>
                                    <td>
                                        <?= $proposta["convenio"] ?>
                                    </td>
                                    <td>
                                        <?= $proposta["tabela"] ?>
                                    </td>
                                    <td>
                                        <?= $proposta["prazo"] ?>
                                    </td>
                                    <td>
                                        <?= number_format($proposta["vlsolicitado"], 2, ",", "") ?>
                                    </td>
                                    <td>
                                        <?= $proposta["dtpago2"] ?>
                                    </td>
                                    <td title="<?= $observacao["observacao"] ?>">
                                        <?= $proposta["status"] ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($proposta["categoria_cliente"] == 6) {
                                            $complementoCaminho = "&callcenter=true";
                                        }
                                        ?>                                        
                                        <a href="Cliente.php?codpessoa=<?= $proposta["codcliente"] ?><?= $complementoCaminho ?>&codproposta=<?=$proposta["codproposta"]?>" title="Clique aqui para abrir perfil do cliente"><?= $proposta["codcliente"] ?></a>
                                    </td>
                                    <?php if (isset($_POST["codcliente"])) { ?>
                                        <td>
                                            <?php
                                            echo '<a href="Cliente.php?codpessoa=', $proposta["codcliente"] , $complementoCaminho ,'&codproposta=',$proposta["codproposta"],'" title="Clique aqui para editar"><img style="width: 25px;" src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                                            echo '<a href="#" onclick="excluirProposta(', $proposta["codproposta"], ')" title="Clique aqui para excluir"><img style="width: 25px;" src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                                            if ($_SESSION["codnivel"] == 1 || $_SESSION["codnivel"] == 18) {
                                                echo '<a href="#" onclick="procurarObservacaoProposta(', $proposta["codproposta"], ',', $proposta["codcliente"], ')" title="Clique aqui para abrir as observações a proposta"><img style="width: 25px;" src="../visao/recursos/img/livro2.png" alt="botão excluir"/></a>';
                                            }
                                            ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th rowspan="1" colspan="1">
                                    Dt. Cadastro
                                </th>
                                <th rowspan="1" colspan="1">
                                    Banco
                                </th>
                                <th rowspan="1" colspan="1">
                                    Convênio
                                </th>
                                <th rowspan="1" colspan="1">
                                    Tabela
                                </th>
                                <th rowspan="1" colspan="1">
                                    Prazo
                                </th>
                                <th rowspan="1" colspan="1">
                                    Valor
                                </th>
                                <th rowspan="1" colspan="1">
                                    Dt. Pgto
                                </th>
                                <th rowspan="1" colspan="1">
                                    Status
                                </th>
                                <th rowspan="1" colspan="1">
                                    Cliente
                                </th>
                                <?php if (isset($_POST["codcliente"])) { ?>
                                    <th rowspan="1" colspan="1">
                                        Opções
                                    </th>
                                <?php } ?>
                            </tr>
                        </tfoot>
</table>
    <?php
    
}
?>