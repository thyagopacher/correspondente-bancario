<?php
session_start();
    //validação caso a sessão caia
    if(!isset($_SESSION)){
        die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
    }   
include "../model/Conexao.php";
$conexao = new Conexao();
$resbeneficio = $conexao->comando("select * from beneficiocliente where codpessoa = '{$_POST["codpessoa"]}' and codempresa = '{$_SESSION['codempresa']}'");
$qtdbeneficio = $conexao->qtdResultado($resbeneficio);
if ($qtdbeneficio > 0) {
    $linhaBeneficio = 1;
    while ($beneficio = $conexao->resultadoArray($resbeneficio)) {
        ?> 
        <div id="beneficio_aposentado<?= $linhaBeneficio ?>">
            <div id="orgao_pagador<?= $linhaBeneficio ?>" style="width: 195px; float: left;">
                Órgão Pagador 
                <select name="orgaopagador[]" id="orgaopagador<?= $linhaBeneficio ?>" title="Órgão pagador de aposentadoria" onclick="orgaoPagadorChange(<?= $linhaBeneficio ?>)">
                    <?php
                    $resorgao = $conexao->comando("select * from orgaopagador order by nome");
                    $qtdorgao = $conexao->qtdResultado($resorgao);
                    if ($qtdorgao > 0) {
                        $optionorgaopagador = '<option value="">--Selecione--</option>';
                        while ($orgao = $conexao->resultadoArray($resorgao)) {
                            if (isset($beneficio["codorgao"]) && $beneficio["codorgao"] == $orgao["codorgao"]) {
                                $optionorgaopagador .= '<option selected value="' . $orgao["codorgao"] . '">' . $orgao["nome"] . '</option>';
                            } else {
                                $optionorgaopagador .= '<option value="' . $orgao["codorgao"] . '">' . $orgao["nome"] . '</option>';
                            }
                        }
                    } else {
                        $optionorgaopagador .= '<option value="">--Nada encontrado--</option>';
                    }
                    echo $optionorgaopagador;
                    ?>
                </select>
                <input type="hidden" name="optionorgaopagador" id="optionorgaopagador" value='<?php
            if (isset($optionorgaopagador)) {
                echo $optionorgaopagador;
            }
                    ?>'/>
            </div>
            <div id="beneficio_inss<?= $linhaBeneficio ?>" style="width: 380px;float: left;">
                ESPÉCIE
                <select style="width: 100px;" name="especie[]" id="especie<?= $linhaBeneficio ?>">
                    <?php
                    $resespecie = $conexao->comando("select * from especie order by nome");
                    $qtdespecie = $conexao->qtdResultado($resespecie);
                    if ($qtdespecie > 0) {
                        $optionespecie .= '<option value="">--Selecione--</option>';
                        while ($especie = $conexao->resultadoArray($resespecie)) {
                            if (isset($beneficio["codespecie"]) && $beneficio["codespecie"] == $especie["codespecie"]) {
                                $optionespecie .= '<option selected value="' . $especie["codespecie"] . '">' . $especie["nome"] . '</option>';
                            } else {
                                $optionespecie .= '<option value="' . $especie["codespecie"] . '">' . $especie["nome"] . '</option>';
                            }
                        }
                    } else {
                        $optionespecie .= '<option value="">--Nada encontrado--</option>';
                    }
                    echo $optionespecie;
                    ?>
                    <input type="hidden" name="optionespecie" id="optionespecie" value='<?php
                           if (isset($optionespecie)) {
                               echo $optionespecie;
                           }
                           ?>'/>
                </select>
                NUM. BENEFICIO
                <input type='text' name="numbeneficio[]" id="numbeneficio<?= $linhaBeneficio ?>" value="<?php
            if (isset($beneficio["numbeneficio"])) {
                echo $beneficio["numbeneficio"];
            }
            ?>"/>
            </div>
            <?php
            if ((isset($beneficio["numbeneficio"]) && $beneficio["numbeneficio"] != NULL && $beneficio["numbeneficio"] != "") || (isset($beneficio["codorgao"]) && $beneficio["codorgao"] == 3)) {
                $displayMatricula = "display: none;";
            } else {
                $displayMatricula = "";
            }
            ?>
            <div id="beneficio_outro<?= $linhaBeneficio ?>" style="<?= $displayMatricula ?>width: 205px;float: left;">
                MATRICULA
                <input type='text' name="matricula[]" id="matricula<?= $linhaBeneficio ?>" value="<?php
               if (isset($beneficio["matricula"])) {
                   echo $beneficio["matricula"];
               }
            ?>"/>
            </div> 
            <div id="salario_base<?= $linhaBeneficio ?>" style="float: left;">
                SALÁRIO BASE
                <input type='text' name="salariobase[]" id="salariobase<?= $linhaBeneficio ?>" class="real" value="<?php
               if (isset($beneficio["salariobase"])) {
                   echo number_format($beneficio["salariobase"], 2, ",", "");
               }
            ?>"/>
                <button onclick="inserirBeneficio(<?= $linhaBeneficio ?>);" title="Adiciona novo beneficio">+</button>
                <button onclick="removeBeneficio(<?= $linhaBeneficio ?>);" title="Remove beneficio da linha">-</button>            
            </div>
        <?php if (isset($beneficio["numbeneficio"])) { ?>
                <div id="consulta_beneficio<?= $linhaBeneficio ?>" style="width: 70px;  height: 15px;  float: left;">
                    <a class="botao" href="javascript: consultaBeneficioInss2(<?= $beneficio["numbeneficio"] ?>)" title="Clique aqui para consultar o histórico de empréstimos do beneficio">Consulta</a>
                </div>
        <?php } ?>
        </div>        
        <?php
        $linhaBeneficio++;
    }
}