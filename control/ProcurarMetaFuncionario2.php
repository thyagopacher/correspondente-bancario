<?php
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
include "../model/MetaFuncionario.php";
$conexao = new Conexao();
$meta = new MetaFuncionario($conexao);

$and = "";
if (isset($_POST["codfuncionario"]) && $_POST["codfuncionario"] != NULL && $_POST["codfuncionario"] != "") {
    $and .= " and meta.codfuncionario = '{$_POST["codfuncionario"]}'";
}
if (isset($_POST["data1"]) && $_POST["data1"] != NULL) {
    $and .= " and meta.dtcadastro >= '{$_POST["data1"]}'";
}
if (isset($_POST["data2"]) && $_POST["data2"] != NULL) {
    $and .= " and meta.dtcadastro <= '{$_POST["data2"]}'";
}
$sql = "select codmeta, DATE_FORMAT(meta.dtcadastro, '%d/%m/%Y') as dtcadastro2, pessoa.nome as funcionario, meta.valor, meta.codfuncionario, DATE_FORMAT(meta.dtinicio, '%d/%m/%Y') as dtinicio2,
    DATE_FORMAT(meta.dtfim, '%d/%m/%Y') as dtfim2, meta.dtinicio, meta.dtfim
    from metafuncionario as meta
    inner join pessoa on pessoa.codpessoa = meta.codfuncionario 
    where 1 = 1
    and meta.codempresa = '{$_SESSION['codempresa']}'
    {$and} order by meta.dtcadastro desc";
$res = $conexao->comando($sql);
$qtd = $conexao->qtdResultado($res);

if ($qtd > 0) {
    ?>


    <table class="tabela_procurar table table-hover">
<thead>
            <tr>
                <th>
                    Data Cad.
                </th>
                <th>
                    Nome
                </th>
                <th>
                    Meta
                </th>
                <th>
                    Inicio
                </th>
                <th>
                    Fim
                </th>
                <th>
                    Opções
                </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($meta = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td>
                        <?= $meta["dtcadastro2"] ?>
                    </td>
                    <td>
                        <?= $meta["funcionario"] ?>
                    </td>
                    <td>
                        <?= number_format($meta["valor"], 2, ",", "") ?>
                    </td>
                    <td>
                        <?= $meta["dtinicio2"] ?>
                    </td>
                    <td>
                        <?= $meta["dtfim2"] ?>
                    </td>
                    <td>
                        <?php
                        echo '<a href="MetaFuncionario.php?codmeta=', $meta["codmeta"], '" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="#" onclick="excluirMetaFuncionario(', $meta["codmeta"], ')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>

    <?php
} else {
    echo '';
}
    