<?php
ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);
session_start();
//validação caso a sessão caia
if (!isset($_SESSION)) {
    die(json_encode(array('mensagem' => 'Sua sessão caiu, por favor logue novamente!!!', 'situacao' => false)));
}


include "../model/Conexao.php";
$conexao = new Conexao();
?>

<a style="color: white;" class="btn btn-primary" href="javascript: importaTelefones();">Importar Telefones</a>
<?php
$sql = "select * from telefone where codpessoa = '{$_POST["codpessoa"]}' and numero <> '' and codempresa = '{$_SESSION['codempresa']}'";
$restelefone = $conexao->comando($sql);
$qtdtelefone = $conexao->qtdResultado($restelefone);
if ($qtdtelefone > 0) {
    echo "Encontrou {$qtdtelefone} telefones para esse cliente<br>";
    while ($telefonep = $conexao->resultadoArray($restelefone)) {
        $linha = 1;
        ?>
        <div class="col-md-12">
            <div class="col-md-3" style="padding-left: 0px">
                <div class="form-group">
                    <label for="exampleInputFile">Operadora</label>
                    <input class="form-control" type="text" name="operadora" id="operadora" value='<?php if (isset($telefonep["operadora"]) && $telefonep["operadora"] != NULL && $telefonep["operadora"] != "") {
            echo $telefonep["operadora"];
        } ?>'/>                                            </div>
            </div>
            <div class="col-md-3" style="padding-left: 0px">
                <div class="form-group">
                    <label for="exampleInputFile">Tipo Telefone</label>
                    <select name="tipotelefone[]" id="tipotelefone<?= $linha ?>" class="form-control">
                        <?php
                        $restipo = $conexao->comando("select * from tipotelefone order by nome");
                        $qtdtipo = $conexao->qtdResultado($restipo);
                        if ($qtdtipo > 0) {
                            $optionTipoTelefone = '<option value="">--Selecione--</option>';
                            while ($tipo = $conexao->resultadoArray($restipo)) {
                                if (isset($telefonep["codtipo"]) && $telefonep["codtipo"] == $tipo["codtipo"]) {
                                    $optionTipoTelefone .= '<option selected value="' . $tipo["codtipo"] . '">' . $tipo["nome"] . '</option>';
                                } else {
                                    $optionTipoTelefone .= '<option value="' . $tipo["codtipo"] . '">' . $tipo["nome"] . '</option>';
                                }
                            }
                        } else {
                            $optionTipoTelefone .= '<option value="">--Nada encontrado--</option>';
                        }
                        echo $optionTipoTelefone;
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="hidden" name="optionTipoTelefone" id="optionTipoTelefone" value='<?php
                    if (isset($optionTipoTelefone)) {
                        echo $optionTipoTelefone;
                    }
                    ?>'/>
                    <label for="exampleInputFile">
                        Telefone
                        <a style="margin: 0;padding: 0;" href='javascript:abrirPopUp("EnvioSMS2.php?numero=<?= $telefonep["numero"] ?>")'><img style='width: 25px;margin-bottom: -12px;padding: 0;height: auto;margin: 0;' src='../visao/recursos/img/SMS.png' alt='SMS telefone'/></a>                                  
                        <a style="margin: 0;padding: 0;" href='javascript:ligaTelefone("<?= reestruturandoTelefone($telefonep["numero"]) ?>")'><img style='width: 25px;margin-bottom: -12px;padding: 0;height: auto;margin: 0;' src='../visao/recursos/img/telefone.png' alt='liga telefone'/></a>                                  
                    </label>
                    <input style="width: 80%" class="form-control" type='text' name="telefone[]" id="telefone<?= $linha ?>" value="<?= reestruturandoTelefone($telefonep["numero"]) ?>" title="Digite aqui telefone" placeholder="(00)0000-0000">
                    <a style="float: right;margin-top: -35px;margin-right: 110px;" class="btn btn-primary" href="#" onclick="inserirTelefone(<?= $linha ?>);">+</a>
                    <a style="float: right;margin-top: -35px;margin-right: 65px;" class="btn btn-primary" href="#" onclick="removeTelefone(<?= $linha ?>);">-</a>
                </div>
            </div>                                            
        </div><!-- /.col -->
        <?php
    }
} else {
    ?>
    <div class="col-md-12">
        <div class="col-md-6" style="padding-left: 0px">
            <div class="form-group">
                <label for="exampleInputFile">Tipo Telefone</label>
                <select name="tipotelefone[]" id="tipotelefone1" class="form-control">
                    <?php
                    $restipo = $conexao->comando("select * from tipotelefone order by nome");
                    $qtdtipo = $conexao->qtdResultado($restipo);
                    if ($qtdtipo > 0) {
                        $optionTipoTelefone = '<option value="">--Selecione--</option>';
                        while ($tipo = $conexao->resultadoArray($restipo)) {
                            if (isset($telefonep["codtipo"]) && $telefonep["codtipo"] == $tipo["codtipo"]) {
                                $optionTipoTelefone .= '<option selected value="' . $tipo["codtipo"] . '">' . $tipo["nome"] . '</option>';
                            } else {
                                $optionTipoTelefone .= '<option value="' . $tipo["codtipo"] . '">' . $tipo["nome"] . '</option>';
                            }
                        }
                    } else {
                        $optionTipoTelefone .= '<option value="">--Nada encontrado--</option>';
                    }
                    echo $optionTipoTelefone;
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <input type="hidden" name="optionTipoTelefone" id="optionTipoTelefone" value='<?php
                if (isset($optionTipoTelefone)) {
                    echo $optionTipoTelefone;
                }
                ?>'/>
                <label for="exampleInputFile">Telefone</label>
                <input style="width: 80%" class="form-control" type='text' name="telefone[]" id="telefone1" value="" title="Digite aqui telefone" placeholder="(00)0000-0000">
                <a style="float: right;margin-top: -35px;margin-right: 110px;" class="btn btn-primary" href="#" onclick="inserirTelefone(1);">+</a>
                <a style="float: right;margin-top: -35px;margin-right: 65px;" class="btn btn-primary" href="#" onclick="removeTelefone(1);">-</a>
            </div>
        </div>                                            
    </div><!-- /.col -->                            
    <?php
}


function reestruturandoTelefone($telefonepessoa2) {
    $telefone = str_replace("-", "", str_replace("(", "", str_replace(")", "", str_replace('.', '', $telefonepessoa2))));
    $telefonepessoa = trim($telefone);
    if (strlen($telefonepessoa) == 10) {
        $mascaraTelefone = "(##)####-####";
    } else {
        $mascaraTelefone = "(##)#####-####";
    }
    if (strlen($telefonepessoa) > 8 && $telefonepessoa{0} == "0") {
        $ddd = substr($telefonepessoa, 0, 3);
        if ($ddd !== "021") {
            $telefone = mask($telefonepessoa, $mascaraTelefone);
        } else {
            $telefone = mask($telefonepessoa, $mascaraTelefone);
        }
    } elseif (strlen($telefonepessoa) > 8 && $telefonepessoa{0} != "0") {
        $ddd = substr($telefonepessoa, 0, 2);
        if ($ddd !== "21") {
            $telefone = mask($telefonepessoa, $mascaraTelefone);
        } else {
            $telefone = mask($telefonepessoa, $mascaraTelefone);
        }
    } elseif (strlen($telefonepessoa) == 8) {
        $telefone = mask("21" . $telefonepessoa, $mascaraTelefone);
    }
    return $telefone;
}


/* * mascara para inputs html */
function mask($val, $mask = "(##)####-####") {
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i++) {
        if ($mask[$i] == '#') {
            if (isset($val[$k])) {
                $maskared .= $val[$k++];
            }
        } else {
            if (isset($mask[$i])) {
                $maskared .= $mask[$i];
            }
        }
    }
    return $maskared;
}


?>
