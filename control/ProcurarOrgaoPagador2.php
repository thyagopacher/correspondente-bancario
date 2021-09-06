<?php
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
include "../model/OrgaoPagador.php";
$conexao = new Conexao();
$orgao = new OrgaoPagador($conexao);

$and = "";
if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $and .= " and orgao.nome like '%{$_POST["nome"]}%'";
}
if (isset($_POST["data1"]) && $_POST["data1"] != NULL) {
    $and .= " and orgao.dtcadastro >= '{$_POST["data1"]}'";
}
if (isset($_POST["data2"]) && $_POST["data2"] != NULL) {
    $and .= " and orgao.dtcadastro <= '{$_POST["data2"]}'";
}
$sql = "select codorgao, nome, DATE_FORMAT(dtcadastro, '%d/%m/%Y') as dtcadastro2
    from orgaopagador as orgao
    where 1 = 1
    {$and} order by orgao.dtcadastro desc";
$res = $conexao->comando($sql);
$qtd = $conexao->qtdResultado($res);

if ($qtd > 0) {
    ?>


    <table class="tabela_procurar table table-hover">
        <thead>
            <tr>
                <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1"
                    colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                    Data Cad.
                </th>
                <th>
                    Nome
                </th>
                <th>
                    Opções
                </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($orgao = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td>
                        <?= $orgao["dtcadastro2"] ?>
                    </td>
                    <td>
                        <?= $orgao["nome"] ?>
                    </td>
                    <td>
                        <?php
                        echo '<a href="OrgaoPagador.php?codorgao=', $orgao["codorgao"], '" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="#" onclick="excluirOrgaoPagador(', $orgao["codorgao"], ')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>

    <?php
}
?>