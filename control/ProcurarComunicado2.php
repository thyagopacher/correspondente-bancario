<?php
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
include "../model/Manual.php";
$conexao = new Conexao();
$comunicado = new Manual($conexao);

$and = "";
if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $and .= " and comunicado.nome like '%{$_POST["nome"]}%'";
}
if (isset($_POST["data"]) && $_POST["data"] != NULL) {
    $and .= " and comunicado.dtcadastro >= '{$_POST["data"]}'";
}
if (isset($_POST["data2"]) && $_POST["data2"] != NULL) {
    $and .= " and comunicado.dtcadastro <= '{$_POST["data2"]}'";
}
$sql = "select codcomunicado, comunicado.nome, DATE_FORMAT(comunicado.dtcadastro, '%d/%m/%Y') as dtcadastro2, comunicado.arquivo
    from comunicado
    where 1 = 1 and codempresa = {$_SESSION["codempresa"]}
    {$and} order by comunicado.dtcadastro desc";
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
                    Download
                </th>
                <th>
                    Opções
                </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($comunicado = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td>
                        <?= $comunicado["dtcadastro2"] ?>
                    </td>
                    <td>
                        <?= $comunicado["nome"] ?>
                    </td>
                    <td>
                        <?php
                        echo '<a href="../arquivos/', $comunicado["arquivo"], '" target="_blank">Download</a>';
                        ?>
                    </td>
                    <td>
                        <?php
                        echo '<a href="Comunicado.php?codcomunicado=', $comunicado["codcomunicado"], '" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="#" onclick="excluirComunicado(', $comunicado["codcomunicado"], ')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>

    <?php
}
?>