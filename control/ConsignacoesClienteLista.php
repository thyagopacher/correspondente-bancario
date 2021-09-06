
<?php
session_start();
include "../model/Conexao.php";
include "../model/Coeficiente.php";
$conexao = new Conexao();

$sql = "select especie.nome as beneficiocliente, beneficiocliente.codbeneficio, banco.nome as banco, beneficiocliente.meio, beneficiocliente.salariobase, 
            beneficiocliente.agencia, beneficiocliente.contacorrente, beneficiocliente.margem, especie.numinss as especie, beneficiocliente.codbeneficio
            from beneficiocliente 
            left join especie on especie.codespecie = beneficiocliente.codespecie
            left join banco on banco.codbanco = beneficiocliente.codbanco
            where beneficiocliente.codpessoa = '{$_POST["codpessoa"]}' and beneficiocliente.situacao = 'ativo' and beneficiocliente.codempresa = '{$_SESSION['codempresa']}'";
$resbeneficios = $conexao->comando($sql);
$qtdbeneficios = $conexao->qtdResultado($resbeneficios);
if ($qtdbeneficios > 0) {
    $linhaBeneficio = 0;
    echo '<input type="hidden" name="qtd_beneficio" id="qtd_beneficio" value="', $qtdbeneficios, '"/>';
    while ($beneficio = $conexao->resultadoArray($resbeneficios)) {
        echo '<table class="tabela_formulario" style="width: 100%;float: left;">';
        echo '<tr>';
        echo '<td title="', $beneficio["codbeneficio"], '">Espécie(', $beneficio["especie"], ')</td>';
        echo '<td colspan="4">BANCO PAGADOR</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>', $beneficio["beneficiocliente"], '</td>';
        echo '<td>', $beneficio["meio"], '</td>';
        echo '<td>', $beneficio["banco"], '</td>';
        echo '<td> AG:', $beneficio["agencia"], '</td>';
        echo '<td> C/C:', $beneficio["contacorrente"], '</td>';
        echo '<td> Salário:', number_format($beneficio["salariobase"], 2, ",", ""), '</td>';
        echo '</tr>';
        echo '</table>';
        $sql = "select distinct emprestimo.*, banco.nome as banco
                    from emprestimo
                    left join beneficiocliente as beneficio on beneficio.codpessoa = emprestimo.codpessoa and beneficio.codempresa = emprestimo.codempresa 
                    left join banco on banco.codbanco = emprestimo.codbanco
                    where emprestimo.codempresa = '{$_SESSION['codempresa']}' 
                    and emprestimo.codbeneficio = '{$beneficio["codbeneficio"]}'    
                    and emprestimo.codpessoa = '{$_POST["codpessoa"]}' order by dtparcela";
        $resemprestimo = $conexao->comando($sql);
        $qtdemprestimo = $conexao->qtdResultado($resemprestimo);
        if ($qtdemprestimo > 0) {

            $coeficiente = new Coeficiente($conexao);
            $coeficientep = $coeficiente->procuraCoeficienteHoje();
            $coeficienteEmprestimoFinal = str_replace(".", ",", $coeficientep["valor"]);
            $linhaEmprestimo = 0;
            $totalCoeficiente = 0.0;
            $totalTroco = 0.0;
            echo '<table  class="tabela_formulario" style="width: 100%;float: left;">';
            echo '<tr>';
            echo '<td colspan="7" style="text-align: center;background: orange; color: white; font-size: 15px;">PORTABILIDADE</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>Banco</td>';
            echo '<td>Parcela</td>';
            echo '<td>Quitação</td>';
            echo '<td>Prazo</td>';
            echo '<td>Restante</td>';
            echo '<td>Coeficiente</td>';
            echo '<td>Liberado</td>';
            echo '</tr>';
            while ($emprestimo = $conexao->resultadoArray($resemprestimo)) {
                echo '<tr>';
                echo '<td title="banco empréstimo">', $emprestimo["banco"], '</td>';
                echo '<td id="vl_parcela_', $linhaBeneficio, '_', $linhaEmprestimo, '" title="vl parcela">', number_format($emprestimo["vlparcela"], 2, ",", ""), '</td>';
                echo '<td id="vl_quitacao_', $linhaBeneficio, '_', $linhaEmprestimo, '" title="vl para quitação">', number_format($emprestimo["quitacao"], 2, ",", ""), '</td>';
                echo '<td title="prazo">', $emprestimo["prazo"], '</td>';
                echo '<td title="prazo">', $emprestimo["parcelas_aberto"], '</td>';
                if (!isset($emprestimo["coeficiente"]) || $emprestimo["coeficiente"] == NULL || $emprestimo["coeficiente"] == "") {
                    $emprestimo["coeficiente"] = $coeficienteEmprestimoFinal;
                }
                echo '<td><input type="text" class="coeficiente" name="coeficiente[]" id="coeficiente_', $linhaBeneficio, '_', $linhaEmprestimo, '" title="Coeficiente" placeholder="Coeficiente" value="', $emprestimo["coeficiente"], '"/></td>';
                if (isset($emprestimo["coeficiente"]) && $emprestimo["coeficiente"] != NULL && $emprestimo["coeficiente"] != "" && $emprestimo["coeficiente"] > 0) {
                    $troco = $emprestimo["vlparcela"] / ($emprestimo["coeficiente"] - $emprestimo["quitacao"]);
                }
                $totalCoeficiente += $emprestimo["coeficiente"];
                $totalTroco += $troco;
                echo '<td><input type="text" disabled class="troco" name="troco[]" id="troco_', $linhaBeneficio, '_', $linhaEmprestimo, '" title="Troco do cliente" placeholder="troco" value="', $troco, '"/></td>';
                echo '</tr>';
                $linhaEmprestimo++;
            }

            echo '</tbody>';
            echo '<tfoot>';
            echo '<tr>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>--</td>';
            echo '<td>Total da renovação</td>';
            echo '<td id="totalTroco', $linhaBeneficio, '" title="Total troco do cliente">R$ ', number_format($totalTroco, 2, ",", ""), '</td>';
            echo '</tr>';
            echo '</tfoot>';
            echo '</table>';
            echo '<table  class="tabela_formulario" style="width: 100%;float: left;">';
            echo '<tr>';
            echo '<td colspan="6" style="text-align: center;background: orange; color: white; font-size: 15px;">MARGEM ESTIMADA</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td style="width:265px;">--</td>';
            echo '<td style="width:75px;">--</td>';
            echo '<td style="width:95px;">--</td>';
            echo '<td id="margem', $linhaBeneficio, '" style="width:60px;">R$ ', number_format($beneficio["margem"], 2, ",", ""), '</td>';
            echo '<td style="width: 245px;"><input type="text" class="coeficiente_margem" name="coeficiente_margem[]" id="coeficiente_margem', $linhaBeneficio, '" title="Coeficiente" placeholder="Coeficiente" value="', $coeficienteEmprestimoFinal, '"/></td>';
            echo '<td id="total_margem', $linhaBeneficio, '">R$ 0,00</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td style="width:265px;">--</td>';
            echo '<td style="width:75px;">--</td>';
            echo '<td style="width:95px;">--</td>';
            echo '<td style="width:60px;">--</td>';
            echo '<td style="width: 245px;">Total Liberado</td>';
            echo '<td id="total_liberado', $linhaBeneficio, '">R$ 0,00</td>';
            echo '</tr>';
            echo '</table>';
        }
        $linhaBeneficio++;
    }
} else {
    echo '<div>Nada encontrado!!!</div>';
}
?>