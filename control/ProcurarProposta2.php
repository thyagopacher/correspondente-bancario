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
    $and .= " and proposta.codcliente = '{$_POST["codcliente"]}'";
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
if (isset($_SESSION["codnivel"]) && $_SESSION["codnivel"] != 1 && $_SESSION["codnivel"] != 18) {
    $and .= " and proposta.codfuncionario = '{$_SESSION['codpessoa']}'";
}
$sql = "select proposta.codproposta, proposta.nome, DATE_FORMAT(proposta.dtcadastro, '%d/%m/%Y') as dtcadastro2, proposta.codfuncionario, 
    funcionario.nome as funcionario, cliente.nome as cliente, cliente.cpf, proposta.vlsolicitado, convenio.nome as convenio, proposta.codbanco, proposta.codconvenio, 
    proposta.codtabela, proposta.prazo, banco.nome as banco, tabela.nome as tabela, status.nome as status, proposta.codstatus, proposta.codcliente, proposta.vlparcela, proposta.vlliberado, proposta.codbeneficio,
    status.cor, proposta.codbanco2, proposta.agencia, proposta.conta, proposta.operacao, proposta.poupanca, proposta.dtvenda, proposta.observacao, proposta.pendente,
    DATE_FORMAT(proposta.dtpago, '%d/%m/%Y') as dtpago2, proposta.dtpago
    from proposta
    inner join pessoa as funcionario on funcionario.codpessoa = proposta.codfuncionario 
    inner join pessoa as cliente on cliente.codpessoa = proposta.codcliente and cliente.codempresa = proposta.codempresa
    inner join convenio on convenio.codconvenio = proposta.codconvenio
    inner join banco on banco.codbanco = proposta.codbanco
    inner join tabela on tabela.codtabela = proposta.codtabela
    inner join statusproposta as status on status.codstatus = proposta.codstatus
    where 1 = 1 and proposta.codempresa = {$_SESSION["codempresa"]}
    {$and} order by statusprioritario desc,proposta.dtcadastro desc";

$res = $conexao->comando($sql);
$qtd = $conexao->qtdResultado($res);
if ($qtd > 0) {
    ?>
                                        
                <table class="tabela_procurar table table-hover">
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
                                <th
                                    colspan="1" aria-label="Engine version: activate to sort column ascending">
                                    Opções
                                </th>
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
                                        <?= number_format($proposta["vlsolicitado"], 2, ",", "")?>
                                    </td>
                                    <td>
                                        <?= $proposta["dtpago2"] ?>
                                    </td>
                                    <td title="<?= $observacao["observacao"] ?>">
                                        <?= $proposta["status"] ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo '<a href="', $link, '?codconta=', $conta["codconta"], '&master=', $_POST["master"], '" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                                        echo '<a href="#" onclick="excluirConta(', $conta["codconta"], ')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                                        echo '<a href="#" onclick="pagaConta(', $conta["codconta"], ')" title="Clique aqui para definir como paga para o dia de hoje"><img style="width: 50px;" src="../visao/recursos/img/dinheiro.png" alt="botão dinheiro"/></a>';
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        
</table>
    <?php
    
}
?>