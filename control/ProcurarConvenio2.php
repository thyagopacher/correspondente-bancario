<?php
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
include "../model/Convenio.php";
$conexao = new Conexao();
$convenio = new Convenio($conexao);

$and = "";
if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $and .= " and convenio.nome like '%{$_POST["nome"]}%'";
}
if (isset($_POST["data1"]) && $_POST["data1"] != NULL) {
    $and .= " and convenio.dtcadastro >= '{$_POST["data1"]}'";
}
if (isset($_POST["data2"]) && $_POST["data2"] != NULL) {
    $and .= " and convenio.dtcadastro <= '{$_POST["data2"]}'";
}
$sql = "select codconvenio, nome, DATE_FORMAT(dtcadastro, '%d/%m/%Y') as dtcadastro2
    from convenio
    where 1 = 1 and nome <> ''
    {$and} order by convenio.dtcadastro desc";
$res = $conexao->comando($sql);
$qtd = $conexao->qtdResultado($res);

if ($qtd > 0) {
    ?>


    <table class="tabela_procurar table table-hover">
        <thead>
            <tr>
                <th>
                    Nome
                </th>
                <th>
                    Dt. Cadastro
                </th>
                <th>
                    Opções
                </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($convenio = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td>
                        <?= $convenio["nome"] ?>
                    </td>
                    <td>
                        <?= $convenio["dtcadastro2"] ?>
                    </td>
                    <td>
                        <?php
                        echo '<a href="Convenio.php?codconvenio=', $convenio["codconvenio"], '" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="#" onclick="excluirConvenio(', $convenio["codconvenio"], ')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
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