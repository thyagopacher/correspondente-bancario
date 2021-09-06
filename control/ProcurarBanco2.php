<?php
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
include "../model/Banco.php";
$conexao = new Conexao();
$banco = new Banco($conexao);

$and = "";
if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $and .= " and banco.nome like '%{$_POST["nome"]}%'";
}
if (isset($_POST["data1"]) && $_POST["data1"] != NULL) {
    $and .= " and banco.dtcadastro >= '{$_POST["data1"]}'";
}
if (isset($_POST["data2"]) && $_POST["data2"] != NULL) {
    $and .= " and banco.dtcadastro <= '{$_POST["data2"]}'";
}
$sql = "select codbanco, nome, DATE_FORMAT(dtcadastro, '%d/%m/%Y') as dtcadastro2, banco.site
    from banco
    where 1 = 1
    {$and} order by banco.dtcadastro desc";
$res = $conexao->comando($sql);
$qtd = $conexao->qtdResultado($res);
if ($qtd > 0) {
    ?>


    <table class=" table table-hover">
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
            <?php while ($banco = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td>
                        <?= $banco["dtcadastro2"] ?>
                    </td>
                    <td>
                        <?= $banco["nome"] ?>
                    </td>
                    <td>
                        <?php
                        echo '<a href="Banco.php?codbanco=', $banco["codbanco"], $complementoCaminho, '" title="Clique aqui para editar"><img style="width: 20px;" src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="#" onclick="excluirBanco(', $banco["codbanco"], ')" title="Clique aqui para excluir"><img style="width: 20px;" src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                        ?>
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
?>