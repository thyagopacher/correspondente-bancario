<?php
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
}

include "../model/Conexao.php";
$conexao = new Conexao();
$and = "";

if (isset($_POST["nome"]) && $_POST["nome"] != NULL && $_POST["nome"] != "") {
    $and .= " and pessoa.nome like '%{$_POST["nome"]}%'";
}
if (isset($_POST["codmorador"]) && $_POST["codmorador"] != NULL && $_POST["codmorador"] != "") {
    $and .= " and pessoa.codpessoa = '{$_POST["codmorador"]}'";
}
if (isset($_POST["bloco"]) && $_POST["bloco"] != NULL && $_POST["bloco"] != "") {
    $and .= " and pessoa.bloco = '{$_POST["bloco"]}'";
}
if (isset($_POST["apartamento"]) && $_POST["apartamento"] != NULL && $_POST["apartamento"] != "") {
    $and .= " and pessoa.apartamento = '{$_POST["apartamento"]}'";
}

if (isset($_POST["data1"]) && $_POST["data1"] != NULL && $_POST["data1"] != "") {
    $and .= " and acesso.data >= '{$_POST["data1"]}'";
}
if (isset($_POST["data2"]) && $_POST["data2"] != NULL && $_POST["data2"] != "") {
    $and .= " and acesso.data <= '{$_POST["data2"]}'";
}

$res = $conexao->comando("select codacesso, pessoa.nome, acesso.enderecoip, DATE_FORMAT(data, '%d/%m/%Y') as data2, acesso.quantidade 
        from acesso
        inner join pessoa on pessoa.codpessoa = acesso.codpessoa and pessoa.codempresa = acesso.codempresa
        where 1 = 1 {$and} 
        and acesso.codempresa = '{$_SESSION['codempresa']}'            
        order by acesso.data desc");
$qtd = $conexao->qtdResultado($res);
if ($qtd > 0) {
    ?>
    <table class="tabela_procurar table table-hover">
        <thead>
            <tr>
                <th>
                    Data
                </th>
                <th>
                    Nome
                </th>
                <th>
                    Endereço IP
                </th>
                <th>
                    Qtd.
                </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($acesso = $conexao->resultadoArray($res)) { ?>
                <tr>
                    <td>
                        <?= $acesso["data2"] ?>
                    </td>
                    <td>
                        <?= $acesso["nome"] ?>
                    </td>
                    <td>
                        <?= $acesso["enderecoip"] ?>
                    </td>
                    <td>
                        <?= $acesso["quantidade"] ?>
                    </td>

                </tr>
            <?php } ?>
        </tbody>

    </table>
    <?php
}
?>