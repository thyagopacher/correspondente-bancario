<?php
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}
include "../model/Conexao.php";
$conexao = new Conexao();
$and     = '';

if (isset($_POST["codnivel"]) && $_POST["codnivel"] != NULL && $_POST["codnivel"] != "") {
    $and .= " and nivel.codnivel = '{$_POST["codnivel"]}'";
}

$sql = "select DATE_FORMAT(nivel.dtcadastro, '%d/%m/%Y') as dtcadastro2, nivel.nome, nivel.codnivel 
    from nivel 
    where nivel.codempresa = '{$_SESSION['codempresa']}'";
$res = $conexao->comando($sql)or die("<pre>$sql</pre>");
$qtd = $conexao->qtdResultado($res);
if ($qtd > 0) {
    ?>

                    <table class="tabela_procurar table table-hover">
                        <thead>
                            <tr>
                                <th>
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
                            <?php while ($nivel = $conexao->resultadoArray($res)) { ?>
                                <tr>
                                    <td>
                                        <?= $nivel["dtcadastro2"] ?>
                                    </td>
                                    <td>
                                        <?= $nivel["nome"] ?>
                                    </td>
                                   
                                    <td>
                                        <?php
                                        echo '<a href="Nivel.php?codnivel=', $nivel["codnivel"], '" title="Clique aqui para editar"><img style="width: 20px;" src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                                        echo '<a href="#" onclick="excluirNivel2(', $nivel["codnivel"], ')" title="Clique aqui para excluir"><img style="width: 20px;" src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        
                    </table>
    <?php
    
}
?>