<?php
header('Content-type: text/html; charset=UTF-8');
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
}
include "../model/Conexao.php";
$conexao = new Conexao();
$and = '';

if (isset($_POST["codfuncionario"]) && $_POST["codfuncionario"] != NULL && $_POST["codfuncionario"] != "") {
    $and .= " and proposta.codfuncionario = '{$_POST["codfuncionario"]}'";
} 
if (isset($_POST["codloja"]) && $_POST["codloja"] != NULL && $_POST["codloja"] != "") {
    $and .= " and proposta.codempresa = '{$_POST["codloja"]}'";
} else {
    $and .= " and (empresa.codempresa = '{$_SESSION["codempresa"]}' or empresa.codpessoa in(select codpessoa from pessoa where codempresa = {$_SESSION["codempresa"]}))";
}
if (isset($_POST["codstatus"]) && $_POST["codstatus"] != NULL && $_POST["codstatus"] != "") {
    $and .= " and proposta.codstatus = '{$_POST["codstatus"]}'";
}
if (isset($_POST["cpf"]) && $_POST["cpf"] != NULL && $_POST["cpf"] != "") {
    $and .= " and proposta.codcliente in (select codpessoa from pessoa where cpf = '{$_POST["cpf"]}')";
}
if (isset($_SESSION["codnivel"]) && $_SESSION["codnivel"] != '1' && $_SESSION["codnivel"] != "19") {
    $and .= " and proposta.codfuncionario = '{$_SESSION['codpessoa']}'";
}

if(isset($_POST["tipopesquisa"]) && $_POST["tipopesquisa"] != NULL && $_POST["tipopesquisa"] != ""){
    if($_POST["tipopesquisa"] == 1){
        $and .= " and proposta.codstatus not in (3, 10, 11, 22, 26)";
    }elseif($_POST["tipopesquisa"] == 2){
        $and .= " and proposta.codstatus in (3, 22)";
    }elseif($_POST["tipopesquisa"] == 3){
        $and .= " and proposta.codstatus in (select codstatus from statusproposta)";
    }elseif($_POST["tipopesquisa"] == 4){
        $and .= " and proposta.codstatus in (10, 11, 26)";
    }
}

$sql = "select proposta.*, 
    DATE_FORMAT(proposta.dtcadastro, '%d/%m/%Y') as dtcadastro2, tabela.nome as tabela, 
    banco.nome as banco, cliente.nome as cliente, cliente.cpf, funcionario.login as funcionario, 
    convenio.nome as convenio, status.nome as status, empresa.razao as unidade, status.cor  
    from proposta 
    inner join pessoa as cliente on cliente.codpessoa = proposta.codcliente
    left join pessoa as funcionario on funcionario.codpessoa = proposta.codfuncionario
    left join banco on banco.codbanco = proposta.codbanco
    left join statusproposta as status on status.codstatus = proposta.codstatus    
    left join empresa on empresa.codempresa = proposta.codempresa
    left join tabela on tabela.codtabela = proposta.codtabela
    left join convenio on convenio.codconvenio = proposta.codconvenio
    where 1 = 1 {$and} order by dtcadastro desc";  
//echo "<pre>{$sql}</pre>"; 
$resproposta = $conexao->comando($sql);
$qtdproposta = $conexao->qtdResultado($resproposta);

if ($qtdproposta > 0) {
    ?>

    <table id="table_esteira">
        <thead>
            <tr>
                <th>
                    Cliente
                </th>
                <th>
                    Unidade
                </th>
                <th>
                    Colaborador
                </th>
                <th>
                    Banco
                </th>
                <th>
                    Tabela
                </th>
                <th>
                    Convênio
                </th>
                <th>
                    Prazo
                </th>
                <th>
                    Vl Liberado
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
            while ($proposta = $conexao->resultadoArray($resproposta)) {
                $totalGanho += $proposta["vlsolicitado"];
                ?>
                <tr>
                    <td class="<?= $proposta["cor"] ?>">
                        <?php
                        if (strlen($proposta["cliente"]) > 18) {
                            echo substr($proposta["cliente"], 0, 18) . '...';
                        } else {
                            echo $proposta["cliente"];
                        }
                        ?>
                    </td>
                    <td class="<?= $proposta["cor"] ?>">
                        <?php
                        if (strlen($proposta["unidade"]) > 12) {
                            echo substr($proposta["unidade"], 0, 12) . '...';
                        } else {
                            echo $proposta["unidade"];
                        }
                        ?>
                    </td>
                    <td class="<?= $proposta["cor"] ?>">
                        <?php
                        echo $proposta["funcionario"];
                        ?>
                    </td>
                    <td class="<?= $proposta["cor"] ?>">
                        <?php
                        if (strlen($proposta["banco"]) > 10) {
                            echo substr($proposta["banco"], 0, 10) . '...';
                        } else {
                            echo $proposta["banco"];
                        }
                        ?>
                    </td>
                    <td title="<?=$proposta["tabela"]?>" class="<?= $proposta["cor"] ?>">
                        <?php
                        if (strlen($proposta["tabela"]) > 14) {
                            echo substr($proposta["tabela"], 0, 14) . '...';
                        } else {
                            echo $proposta["tabela"];
                        }
                        ?>
                    </td>
                    <td class="<?= $proposta["cor"] ?>">
                        <?php
                        if (strlen($proposta["convenio"]) > 6) {
                            echo substr($proposta["convenio"], 0, 6) . '...';
                        } else {
                            echo $proposta["convenio"];
                        }
                        ?>
                    </td>
                    <td class="<?= $proposta["cor"] ?>"><?= $proposta["prazo"] ?></td>
                    <td class="<?= $proposta["cor"] ?>"><?= number_format($proposta["vlsolicitado"], 2, ",", ",") ?></td>

                    <td title="<?=$proposta["status"]?>" class="<?= $proposta["cor"] ?>">
                        <?php
                        if (strlen($proposta["status"]) > 14) {
                            echo substr($proposta["status"], 0, 14) . '...';
                        } else {
                            echo $proposta["status"];
                        }
                        ?>
                    </td>       
                    <td class="<?= $proposta["cor"] ?>">
                        <a href="javascript: editarCliente('<?= trim($proposta["codcliente"]) ?>', <?= $proposta["codproposta"] ?>)"><img src="/visao/recursos/img/editar.png" alt="Editar Proposta"/></a>
                        <a href="javascript: excluirProposta('<?= $proposta["codproposta"] ?>')"><img src="/visao/recursos/img/excluir.png" alt="Excluir Proposta"/></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>
    </div>
    </div>
    </div>
    </div>
    <?php
}
include "../model/Log.php";
$log = new Log($conexao);
$log->codpessoa = $_SESSION['codpessoa'];

$log->acao = "procurar";
$log->observacao = "Aberto acompanhar esteira - em " . date('d/m/Y') . " - " . date('H:i');
$log->codpagina = "0";

$log->hora = date('H:i:s');
$log->inserir();
