<?php
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
$conexao = new Conexao();

$and = "";
if (isset($_POST["numinss"]) && $_POST["numinss"] != NULL && $_POST["numinss"] != "") {
    $and .= " and especie.numinss = '{$_POST["numinss"]}'";
}
if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $and .= " and especie.nome like '%{$_POST["nome"]}%'";
}

$sql = "select codespecie, nome, numinss from especie
    where 1 = 1 and nome <> '' {$and}";
$res = $conexao->comando($sql);
if ($res == FALSE) {
    die("Erro ocasionado por:" . mysqli_error($conexao->conexao));
}
$qtd = $conexao->qtdResultado($res);

if ($qtd > 0) {
    ?>


    <table class="tabela_procurar table table-hover">
        <thead>
            <tr>
                <th>
                    Num
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
            <?php while ($especie = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td>
                        <?= $especie["numinss"] ?>
                    </td>
                    <td>
                        <?= $especie["nome"] ?>
                    </td>
                    <td>
                        <?php
                        echo '<a href="Especie.php?codespecie=', $especie["codespecie"], '" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="#" onclick="excluirEspecie(', $especie["codespecie"], ')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>

    <?php
}
?>