<?php
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
include "../model/SmsPadrao.php";
$conexao = new Conexao();
$smspadrao = new SmsPadrao($conexao);

if (isset($_POST["texto"]) && $_POST["texto"] != NULL && $_POST["texto"] != "") {
    $and .= " and texto = '{$_POST["texto"]}'";
}
if (isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != "") {
    $and .= " and dtcadastro >= '{$_POST["data1"]}'";
}
if (isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != "") {
    $and .= " and dtcadastro = '{$_POST["data2"]}'";
}
$res = $conexao->comando("select smspadrao.*, DATE_FORMAT(dtcadastro, '%d/%m/%Y') as dtcadastro2  from smspadrao where 1 = 1 {$and}");
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
                <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1"
                    colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                    Nome
                </th>
                <th>
                    Texto
                </th>
                <th>
                    Opções
                </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($sms = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td>
                        <?= $sms["dtcadastro2"] ?>
                    </td>
                    <td>
                        <?= $sms["nome"] ?>
                    </td>
                    <td>
                        <?= $sms["texto"] ?>
                    </td>

                    <td>
                        <?php
                        echo '<a href="SmsPadrao.php?codsmspadrao=', $sms["codsmspadrao"], '" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="#" onclick="excluirSmsPadrao2(', $sms["codsmspadrao"], ')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>

    <?php
}
?>