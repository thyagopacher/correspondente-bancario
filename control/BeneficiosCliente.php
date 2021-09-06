<?php
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
}

function __autoload($class_name) {
    if (file_exists("../model/" . $class_name . '.php')) {
        include "../model/" . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}

$conexao = new Conexao();
$sql = 'select distinct bc.situacao, bc.salariobase, bc.numbeneficio, bc.margem, bc.cartao_rmc,
    bc.valor_cartao_rmc, bc.meio, bc.banco, bc.agencia, bc.contacorrente,
    especie.nome as especie, banco.nome as banco, bc.codbeneficio 
    from beneficiocliente as bc
    inner join especie on especie.codespecie = bc.codespecie
    left join banco on banco.codbanco = bc.codbanco
    where bc.codpessoa = ' . $_POST["codpessoa"] . ' 
    and salariobase > 0    
    and bc.numbeneficio <> "" and bc.codempresa = ' . $_SESSION["codempresa"];
$resbeneficio = $conexao->comando($sql);
$qtdbeneficio = $conexao->qtdResultado($resbeneficio);
if ($qtdbeneficio > 0) {

    while ($beneficio = $conexao->resultadoArray($resbeneficio)) {

        echo '<table style="height: 105px;background: #01BFEF;color: white;padding: 2px; border-radius: 5px;float: left; margin-right: 10px; width: 37%;">';
        echo '<tr><td style="padding-left: 15px;padding-right: 15px;">Situação: <span style="color: red">', strtoupper($beneficio["situacao"]), '</span></td></tr>';
        echo '<tr><td style="padding-left: 15px;padding-right: 15px;">Valor: R$ ', number_format($beneficio["salariobase"], 2, ',', ''), '</span></td></tr>';
        echo '<tr><td style="padding-left: 15px;padding-right: 15px;">Beneficio: ', $beneficio["numbeneficio"], '</td></tr>';
        echo '<tr><td style="padding-left: 15px;padding-right: 15px;">Espécie: ', $beneficio["especie"], '</td></tr>';
        echo '</table>';

        echo '<table style="height: 105px;background: #F58635;color: white;padding: 2px; border-radius: 5px;float: left; margin-right: 10px; width: 20%;">';
        echo '<tr><td style="padding-left: 15px;padding-right: 15px;">Margem Disponível: ', number_format($beneficio["margem"], 2, ',', ''), '</td></tr>';
        if (isset($beneficio["cartao_rmc"]) && $beneficio["cartao_rmc"] != NULL && $beneficio["cartao_rmc"] == "SIM") {
            echo '<tr><td style="padding-left: 15px;padding-right: 15px;">Possui RMC: SIM</span></td></tr>';
        } else {
            echo '<tr><td style="padding-left: 15px;padding-right: 15px;">Possui RMC: NÃO</span></td></tr>';
        }
        echo '<tr><td style="padding-left: 15px;padding-right: 15px;">Margem RMC: ', number_format($beneficio["valor_cartao_rmc"], 2, ',', ''), '</td></tr>';
        echo '</table>';

        echo '<table style="height: 105px;background: #00A85A;color: white;padding: 2px; border-radius: 5px;float: left; margin-right: 10px;width: 31%;">';
        echo '<tr><td style="padding-left: 15px;padding-right: 15px;">Meio de Pagamento: ', ucfirst($beneficio["meio"]), '</td></tr>';
        echo '<tr><td style="padding-left: 15px;padding-right: 15px;">Banco Pagador: ', $beneficio["banco"], '</span></td></tr>';
        echo '<tr><td style="padding-left: 15px;padding-right: 15px;">Agência: ', $beneficio["agencia"], '</td></tr>';
        echo '<tr><td style="padding-left: 15px;padding-right: 15px;">Conta: ', $beneficio["contacorrente"], '</td></tr>';
        echo '</table>';
        $sql = 'select distinct emprestimo.*, banco.nome as banco, banco.numbanco 
                                from emprestimo 
                                inner join banco on banco.codbanco = emprestimo.codbanco
                                inner join beneficiocliente as bc on bc.codbeneficio = emprestimo.codbeneficio and bc.codempresa = emprestimo.codempresa
                                where emprestimo.codempresa = ' . $_SESSION["codempresa"] .
                ' and emprestimo.codpessoa = ' . $_POST["codpessoa"] . ' and prazo > 0  and bc.numbeneficio = "' . $beneficio["numbeneficio"].'"';
//echo "<pre>{$sql}</pre>";
        $resemprestimo = $conexao->comando($sql);
        $qtdemprestimo = $conexao->qtdResultado($resemprestimo);
        if ($qtdemprestimo > 0) {
            ?>
            <table class="tabela_procurar">
                <thead>
                    <tr>
                        <th>
                            BANCO
                        </th>
                        <th>
                            COD.
                        </th>
                        <th>
                            CONTRATO
                        </th>
                        <th>
                            REST./TOTAL
                        </th>
                        <th>
                            PARCELA
                        </th>
                        <th>
                            SALDO APROX.
                        </th>
                        <th>
                            INCLUIR NA SIMULAÇÃO
                        </th>
                        <th>
                            VALOR LIBERADO
                        </th>
                    </tr>
                </thead>   
                <tbody>
                    <?php
                    while ($emprestimo = $conexao->resultadoArray($resemprestimo)) {

                        if (!isset($emprestimo["prazo"]) || $emprestimo["prazo"] == NULL || $emprestimo["prazo"] == "0") {
                            $emprestimo["prazo"] = $emprestimo["parcelasrestantes"] + $emprestimo["qtdpagas"];
                        }
                        ?>

                        <tr>
                            <td><?= $emprestimo["banco"] ?></td>
                            <td><?= $emprestimo["numbanco"] ?></td>
                            <td></td>
                            <td><?= $emprestimo["parcelasrestantes"] ?> / <?= $emprestimo["prazo"] ?></td>
                            <td id="vlparcela_<?= $emprestimo["codemprestimo"] ?>"><?= number_format($emprestimo["vlparcela"], 2, ',', '') ?></td>
                            <td id="saldo_aproximado_<?= $emprestimo["codemprestimo"] ?>"><?= number_format($emprestimo["quitacao"], 2, ',', '') ?></td>
                            <td style="text-align: center"><input onclick="calculaCoeficiente(<?= $emprestimo["codemprestimo"] ?>)" codemprestimo="<?= $emprestimo["codemprestimo"] ?>" class="parcela_incluir" type="checkbox" name='parcela_incluir[]' id='parcela_incluir_<?= $emprestimo["codemprestimo"] ?>'/></td>
                            <td id="vl_liberado_<?= $emprestimo["codemprestimo"] ?>"></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
        echo '<table class="tabela_procurar">';
        echo '<tr>';
        echo '<td colspan="5" style="text-align: right;">MARGEM DISPONIVEL</td>';
        echo '<td id="margem_disponivel_', $beneficio["codbeneficio"], '">', number_format($beneficio["margem"], 2, ',', ''), '</td>';
        echo '<td style="text-align: center"><input type="checkbox" onclick="calculaMargem(', $beneficio["codbeneficio"], ')" class="parcela_incluir_emprestimo" name="parcela_incluir[]" id="parcela_incluir_', $beneficio["codbeneficio"], '"/></td>';
        echo '<td style="width: 300px;" id="margem_liberado_', $beneficio["codbeneficio"], '"></td>';
        echo '</tr>';
        echo '</table>';
    }
}