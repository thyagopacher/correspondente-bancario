<?php
session_start();
include "../model/Conexao.php";
include "../model/BeneficioCliente.php";
$conexao = new Conexao();

$beneficio = new BeneficioCliente($conexao);
if (isset($_GET["cpf"]) && $_GET["cpf"] != NULL && $_GET["cpf"] != "") {
    $num = $beneficio->consultaCpfInss3($_GET["cpf"]);
}
?>
<div class="box-body">
    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
        <div class="row">
            
            </div>
            
            </div>
        </div>
        <div class="row">
            
                <table
                       >
                    <thead>
                        <tr>
                            <th class="sorting_asc" tabindex="0" aria-controls="example2" rowspan="1"
                                colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">
                                Beneficio
                            </th>
                            <th
                                >
                                Espécie
                            </th>
                            <th
                                >
                                DIB
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($num->result as $key => $dadosBeneficio) {
                            $dadosBeneficio = (array) $dadosBeneficio;
                            ?>
                            <tr>
                                <td>
                                    <a style='color: blue;' href='javascript: consultaBeneficioInssAnaliseInfo(<?= $dadosBeneficio['nb'] ?>)'><?= $dadosBeneficio['nb'] ?></a>
                                </td>
                                <td>
                                    <?= $dadosBeneficio['esp'] ?>
                                </td>
                                <td>
                                    <?= $dadosBeneficio['dib'] ?>
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


