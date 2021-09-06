<?php
session_start();
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
$conexao = new Conexao();
$orderBy = '';
$and = "";
$innerConta = 'left';

if(isset($_POST["situacaoComissao"]) && $_POST["situacaoComissao"] != NULL && $_POST["situacaoComissao"] != ""){
    if($_POST["situacaoComissao"] == "pagar"){
        if($_POST["tipo_comissao"] == '2'){
            $and .= " and conta.codstatus = 1";
        }elseif($_POST["tipo_comissao"] == '3'){
            $and .= " and conta.codstatus <> 1";
        }
    }
}

/* * data de pgto da comissão */
if (isset($_POST["data_recebimento1"]) && $_POST["data_recebimento1"] != NULL && $_POST["data_recebimento1"] != "") {
    $innerConta = "inner";
    $and .= " and conta.dtpagamento >= '{$_POST["data_recebimento1"]}'";
} elseif ($_POST["tipo_comissao"] == '2') {
    $innerConta = "inner";
    $and .= " and conta.dtpagamento > '0000-00-00 00:00:00'";
} elseif ($_POST["tipo_comissao"] == '3') {
    $innerConta = "inner";
    $and .= " and conta.dtpagamento = '0000-00-00 00:00:00'";
}
if (isset($_POST["data_recebimento2"]) && $_POST["data_recebimento2"] != NULL && $_POST["data_recebimento2"] != "") {
    $innerConta = "inner";
    $and .= " and conta.dtpagamento <= '{$_POST["data_recebimento2"]}'";
}

/* data de contrato */
if (isset($_POST["data_contrato1"]) && $_POST["data_contrato1"] != NULL && $_POST["data_contrato1"] != "") {
    $innerConta = "inner";
    $and .= " and conta.dtcadastro >= '{$_POST["data_contrato1"]}'";
}
if (isset($_POST["data_contrato2"]) && $_POST["data_contrato2"] != NULL && $_POST["data_contrato2"] != "") {
    $innerConta = "inner";
    $and .= " and conta.dtcadastro <= '{$_POST["data_contrato2"]}'";
}

if (isset($_POST["codbanco"]) && $_POST["codbanco"] != NULL && $_POST["codbanco"] != "") {
    $and .= " and banco.codbanco = {$_POST["codbanco"]}";
}

if (isset($_POST["codtabela"]) && $_POST["codtabela"] != NULL && $_POST["codtabela"] != "") {
    $and .= " and tabela.codtabela = {$_POST["codtabela"]}";
}
if (isset($_POST["codbanco"]) && $_POST["codbanco"] != NULL && $_POST["codbanco"] != "") {
    $and .= " and banco.codbanco = {$_POST["codbanco"]}";
}
if (isset($_POST["codfuncionario"]) && $_POST["codfuncionario"] != NULL && $_POST["codfuncionario"] != "") {
    $and .= " and funcionario.codpessoa = {$_POST["codfuncionario"]}";
}
if (isset($_POST["codfuncionario"]) && $_POST["codfuncionario"] != NULL && $_POST["codfuncionario"] != "") {
    $and .= " and funcionario.codpessoa = {$_POST["codfuncionario"]}";
}

if (isset($_POST["cpf"]) && $_POST["cpf"] != NULL && $_POST["cpf"] != "") {
    $and .= " and cliente.cpf = '{$_POST["cpf"]}'";
}
if(isset($_POST["codfilial"]) && $_POST["codfilial"] != NULL && $_POST["codfilial"] != ""){
    $and .= " and proposta.codempresa = {$_POST["codfilial"]}";
}else{
    $and .= " and (proposta.codempresa = {$_SESSION["codempresa"]} or proposta.codempresa in(select codempresa from empresa where codpessoa in(select codpessoa from pessoa where codempresa = {$_SESSION["codempresa"]})))";
}
$sql = "select ((funcionario.porcentagem * (tabelaprazo.comissao / 100)) * proposta.vlliberado) as comissao_funcionario, 
        (empresa.porcentagem * proposta.vlliberado) as comissao_correspondente, 
        cliente.nome as cliente, cliente.cpf, proposta.codstatus, banco.nome as banco, tabela.nome as tabela, convenio.nome as convenio,
        proposta.vlliberado, proposta.vlsolicitado, conta.codconta, tabelaprazo.pgto_liquido, (funcionario.porcentagem * (tabelaprazo.comissao / 100)) as pct_funcionario,
        empresa.porcentagem as pct_empresa, proposta.codproposta, proposta.comissao_funcionario, funcionario.nome as funcionario, conta.codstatus as status_conta, proposta.valor_contrato_comissao_empresa,
        tabelaprazo.comissao as comissao_Geral
    from proposta 
    inner join pessoa as cliente on cliente.codpessoa = proposta.codcliente and cliente.codempresa = proposta.codempresa
    inner join banco on banco.codbanco = proposta.codbanco
    inner join tabela on tabela.codtabela = proposta.codtabela
    inner join tabelaprazo on tabelaprazo.codtabelap = proposta.codtabelap
    inner join convenio on convenio.codconvenio = proposta.codconvenio
    inner join pessoa as funcionario on funcionario.codpessoa = proposta.codfuncionario
    inner join empresa on empresa.codempresa = proposta.codempresa
    inner join conta on conta.codproposta = proposta.codproposta
    where proposta.codstatus <> 9 {$and} $orderBy";

    $res = $conexao->comando($sql);
$qtd = $conexao->qtdResultado($res);
$totalConta = 0.0;
if ($qtd > 0) {
    echo '<form style="font-size: 14px !important;" action="../control/SalvarComissaoNova.php" id="fModificaComissao" method="post">';
    ?>
    <div  class="box-body">
                                    
                <table class="tabela_procurar table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    Cliente
                                </th>
                                <th>
                                    CPF
                                </th>
                                <th>
                                    Banco
                                </th>
                                <th>
                                    Tabela
                                </th>
                                <th>
                                    Valor Contrato
                                </th>
                                <th>
                                    Convênio
                                </th>
                                <th>
                                    Comissão recebida
                                </th>
                                <th>
                                    Operador/Agente
                                </th>
                                <th>
                                    Comissão a Pagar
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Opções
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($conta = $conexao->resultadoArray($res)) {
                                if ($conta["pct_empresa"] == 0) {
                                    if (isset($conta["comissao_Geral"]) && $conta["comissao_Geral"] != NULL && $conta["comissao_Geral"] > 0) {
                                        $conta["pct_empresa"] = $conta["comissao_Geral"];
                                    } else {
                                        $conta["pct_empresa"] = 100;
                                    }
                                }
                                if (isset($conta["pgto_liquido"]) && $conta["pgto_liquido"] != NULL && $conta["pgto_liquido"] == "s") {
                                    $valorContrato = $conta["vlliberado"];
                                } else {
                                    $valorContrato = $conta["vlsolicitado"];
                                }

                                if (isset($conta["valor_contrato_comissao_empresa"]) && $conta["valor_contrato_comissao_empresa"] != NULL && $conta["valor_contrato_comissao_empresa"] != "" && (double) $conta["valor_contrato_comissao_empresa"] > 0) {
                                    $valorEmpresaComissao = $conta["valor_contrato_comissao_empresa"];
                                } else {
                                    $valorEmpresaComissao = $valorContrato * ($conta["pct_empresa"] / 100);
                                }
                                $valorFuncionarioComissao = $valorEmpresaComissao * ($conta["pct_funcionario"] / 100);
                                ?>
                                <tr>
                            <input type="hidden" name="codproposta[]" value="<?= $conta["codproposta"] ?>"/>
                            <td>
                                <?= $conta["cliente"] ?>
                            </td>
                            <td>
                                <?= $conta["cpf"] ?>
                            </td>
                            <td>
                                <?= $conta["banco"] ?>
                            </td>
                            <td>
                                <?= $conta["tabela"] ?>
                            </td>

                            <td>
                                <?= number_format($valorContrato, 2, ',', '') ?>
                            </td>                                    
                            <td>
                                <?= $conta["convenio"] ?>
                            </td>
                            <td>
                                <?= number_format($valorEmpresaComissao, 2, ',', '') ?>
                            </td>
                            <td>
                                <?= $conta["funcionario"] ?>
                            </td>                            
                            <td>
                                <input size="15" maxlength="50" type="text" name="valor_contrato_comissao[]" id="valor_contrato_comissao<?= $conta["codproposta"] ?>" onchange="salvarComissao(<?= $conta["codproposta"] ?>);"  value="<?= number_format($valorFuncionarioComissao, 2, ',', '') ?>"/>
                            </td>
                            <td>
                                <?php
                                if ($conta["status_conta"] == 1) {
                                    echo "comissão paga";
                                } elseif(!isset($conta["status_conta"]) || $conta["status_conta"] != 1){
                                    echo "baixada";
                                }
                                ?>
                            </td>
                            <td>
                                <?php if (isset($conta["codconta"]) && $conta["status_conta"] == 1) { ?>
                                    <a style="color: white;" class="btn btn-primary" href="#" onclick="pagaConta(<?= $conta["codconta"] ?>)" title="Clique aqui para definir como paga para o dia de hoje">
                                        Pagar
                                    </a>
                                        <?php
                                    } elseif(!isset($conta["status_conta"])){
                                        echo 'Proposta não paga';
                                    }
                                    ?>
                            </td>                                    
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                        
</table>
    <?php
    echo '</form>';
    
}
?>