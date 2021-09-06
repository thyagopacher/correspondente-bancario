<?php
include "../model/Conexao.php";
include "../model/Pagina.php";
$conexao = new Conexao();
$pagina = new Pagina($conexao);

if (isset($_POST["nome"])) {
    $res = $pagina->procuraNome($_POST["nome"]);
} else {
    $res = $pagina->procuraNome("");
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
                    Link
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
            <?php while ($pagina = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td>
                        <?= $pagina["nome"] ?>
                    </td>
                    <td>
                        <?= $pagina["link"] ?>
                    </td>
                    <td>
                        <?= $pagina["titulo"] ?>
                    </td>
                    <td>
                        <?php
                        $arrayJavascript = "new Array('{$pagina["codpagina"]}', '{$pagina["nome"]}', '{$pagina["titulo"]}', '{$pagina["link"]}', '{$pagina["codmodulo"]}', '{$pagina["abreaolado"]}')";
                        echo '<a href="javascript:setaEditarPagina(', $arrayJavascript, ')" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="#" onclick="excluir(', $pagina["codpagina"], ')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
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