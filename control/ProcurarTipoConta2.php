<?php
ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);

session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
include "../model/TipoConta.php";

$conexao = new Conexao();
$tipo = new TipoConta($conexao);
if (!isset($_POST["codempresa"]) || $_POST["codempresa"] == NULL || $_POST["codempresa"] == "") {
    $tipo->codempresa = $_SESSION['codempresa'];
} else {
    $tipo->codempresa = $_POST["codempresa"];
}

if (isset($_POST["nome"])) {
    $res = $tipo->procuraNome($_POST["nome"]);
} else {
    $res = $tipo->procuraNome("");
}
$qtd = $conexao->qtdResultado($res);

if ($qtd > 0) {
    ?>


    </div>

    </div>
    </div>

    <table class=" table table-hover">
        <thead>
            <tr>
                <th>
                    Nome
                </th>
                <th
                    colspan="1" aria-label="Engine version: activate to sort column ascending">
                    Opções
                </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($conta = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td>
                        <?= $conta["nome"] ?>
                    </td>
                    <td>
                        <?php
                        $arrayJavascript = "new Array('{$conta["codtipo"]}', '{$conta["nome"]}')";
                        echo '<a href="javascript: setaEditarTipo(', $arrayJavascript, ')" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="#" onclick="excluirTipo(', $conta["codtipo"], ')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>
    <?php
} else {
    echo 'Nada encontrado!';
}

