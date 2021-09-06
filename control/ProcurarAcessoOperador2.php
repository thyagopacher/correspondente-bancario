<?php
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}

include "../model/Conexao.php";
$conexao = new Conexao();
$and = "";

if (isset($_POST["codoperador"]) && $_POST["codoperador"] != NULL && $_POST["codoperador"] != "") {
    $and .= " and pessoa.codpessoa = '{$_POST["codoperador"]}'";
}

if (isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != "") {
    $and .= " and acesso.dtcadastro >= '{$_POST["data1"]}'";
}
if (isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != "") {
    $and .= " and acesso.dtcadastro <= '{$_POST["data2"]}'";
}
$sql = "select acesso.codacesso, pessoa.nome as operador, DATE_FORMAT(acesso.dtcadastro, '%d/%m/%Y') as dtcadastro2, carteira.nome as carteira, carteira.codcarteira, acesso.codoperador
        from acessooperador as acesso
        inner join pessoa on pessoa.codpessoa = acesso.codoperador and pessoa.codempresa = acesso.codempresa
        left join carteira on carteira.codcarteira = acesso.codcarteira and carteira.codempresa = acesso.codempresa
        where 1 = 1 {$and} 
        and acesso.codempresa = '{$_SESSION['codempresa']}'            
        order by acesso.dtcadastro desc";
$res = $conexao->comando($sql)or die("<pre>$sql</pre>");
$qtd = $conexao->qtdResultado($res);

if ($qtd > 0) {
    ?>

    <table id="table_acesso_operador" class="tabela_procurar table table-hover">
        <thead>
            <tr>
                <th>
                    Data Cad.
                </th>
                <th>
                    Operador
                </th>
                <th>
                    Carteira
                </th>
                <th>
                    Opções
                </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($acesso = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td>
                        <?= $acesso["dtcadastro2"] ?>
                    </td>
                    <td>
                        <?= $acesso["operador"] ?>
                    </td>
                    <td>
                        <?= $acesso["carteira"] ?>
                    </td>
                    <td>
                        <?php
                        $arrayJavascript = "new Array('{$acesso["codacesso"]}', '{$acesso["codcarteira"]}', '{$acesso["codoperador"]}')";
                        echo '<a href="#" onclick="setaEditarAcessoOperador(', $arrayJavascript, ')" title="Clique aqui para editar"><img src="../visao/recursos/img/editar.png" alt="botão editar"/></a>';
                        echo '<a href="#" onclick="excluirAcessoOperador(', $acesso["codacesso"], ')" title="Clique aqui para excluir"><img src="../visao/recursos/img/excluir.png" alt="botão excluir"/></a>';
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>

    <?php
}
?>