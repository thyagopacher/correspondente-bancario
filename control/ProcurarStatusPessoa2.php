<?php
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
include "../model/StatusPessoa.php";

$conexao = new Conexao();
$status = new StatusPessoa($conexao);

if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $res = $status->procuraNome($_POST["nome"]);
} else {
    $res = $status->procuraNome("");
}
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
                    Opções
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($status = $conexao->resultadoArray($res)) {
                $arrayJavascript = "new Array('{$status["codstatus"]}', '{$status["nome"]}')";
                ?>
                <tr>
                    <td>
                        <?= $status["nome"] ?>
                    </td>
                    <td>
                        <?php
                        echo '<a href="javascript:setaEditarStatus(', $arrayJavascript, ')" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="#" onclick="excluirStatus(', $status["codstatus"], ')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>
    <?php
}
?>