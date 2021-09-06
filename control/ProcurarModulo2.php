<?php
include "../model/Conexao.php";
include "../model/Modulo.php";
$conexao = new Conexao();
$modulo = new Modulo($conexao);

if (isset($_POST["nome"])) {
    $res = $modulo->procuraNome($_POST["nome"]);
} else {
    $res = $modulo->procuraNome("");
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
                    Titulo
                </th>
                <th>
                    Opções
                </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($modulo = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td>
                        <?= $modulo["nome"] ?>
                    </td>
                    <td>
                        <?= $modulo["titulo"] ?>
                    </td>
                    <td>
                        <?php
                        $arrayJavascript = "new Array('{$modulo["codmodulo"]}', '{$modulo["nome"]}', '{$modulo["titulo"]}')";
                        echo '<a href="javascript:setaEditarModulo(', $arrayJavascript, ')" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="#" onclick="excluirModulo2(', $modulo["codmodulo"], ')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
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