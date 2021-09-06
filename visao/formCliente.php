<form action="<?= $action ?>" id="fpessoa" name="fpessoa" method="post">
    <div class="row">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Dados Cadastrais</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">

                    <input type="hidden" name="tempoocioso" id="tempoocioso" value="<?= $configuracaop["tempoocioso"] ?>"/>
                    <input type="hidden" name="codcarteira" id="codcarteiraPessoa" value="<?= $pessoap["codcarteira"] ?>"/>
                    <input type="hidden" name="codpessoa" id="codpessoa" value="<?= $pessoap["codpessoa"] ?>"/>
                    <?php
                    if (isset($_GET["callcenter"]) && $_GET["callcenter"] == "true") {
                        echo '<input type="hidden" name="codcategoria" id="codcategoria" value="6"/>';
                    } else {
                        echo '<input type="hidden" name="codcategoria" id="codcategoria" value="1"/>';
                    }
                    ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type='text' class="form-control" name="nome" id="nome" placeholder="" value="<?php
                            if (isset($pessoap["nome"]) && $pessoap["nome"] != NULL && $pessoap["nome"] != "") {
                                echo $pessoap["nome"];
                            }
                            ?>">
                        </div>
                    </div>
                    <div class="col-md-6">                        
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" class="form-control" name='email' id="email" placeholder="" value="<?php
                            if (isset($pessoap["email"])) {
                                echo $pessoap["email"];
                            }
                            ?>">
                        </div>  

                        <!-- /.form-group -->
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mae">Mãe</label>
                            <input type='text' class="form-control" name="mae" id="mae" placeholder="" value="<?php
                            if (isset($pessoap["mae"])) {
                                echo $pessoap["mae"];
                            }
                            ?>">
                        </div>          
                    </div><!-- /.col -->

                    <div class="col-md-6">                        
                        <div class="form-group">
                            <label for="pai">Pai</label>
                            <input type='text' class="form-control" name="pai" id="pai" placeholder="" value="<?php
                            if (isset($pessoap["pai"])) {
                                echo $pessoap["pai"];
                            }
                            ?>">
                        </div>                                         
                    </div><!-- /.col -->                      
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="cpf">CPF</label>
                            <a href="javascript: consultaCpfBeneficio()">
                                <img style="width: 20px;" src="../visao/recursos/img/lupa.png" alt="lupa para procurar"/>
                            </a>                            
                            <input type='text' class="form-control" name="cpf" id="cpf" placeholder="" value="<?php
                            if (isset($pessoap["cpf"]) && $pessoap["cpf"] != NULL && $pessoap["cpf"] != "") {
                                echo $pessoap["cpf"];
                            }
                            ?>">                            
                        </div>
                    </div><!-- /.col -->

                    <div class="col-md-3">                        
                        <div class="form-group">
                            <label for="rg">RG</label>
                            <input type='text' class="form-control" name="rg" id="rg" placeholder="" value="<?php
                            if (isset($pessoap["rg"]) && $pessoap["rg"] != NULL && $pessoap["rg"] != "") {
                                echo $pessoap["rg"];
                            }
                            ?>">
                        </div>  
                        <!-- /.form-group -->
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Sexo</label>
                            <select class="form-control" name="sexo" id="sexo">
                                <option value="m" <?php
                                if (isset($pessoap["sexo"]) && $pessoap["sexo"] == "m") {
                                    echo "selected";
                                }
                                ?>>Masculino</option>
                                <option value="f" <?php
                                if (isset($pessoap["sexo"]) && $pessoap["sexo"] == "f") {
                                    echo "selected";
                                }
                                ?>>Feminino</option>
                            </select>
                        </div>  
                    </div><!-- /.col -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="orgaoemissor">Org. Emissor</label>
                            <input type='text' class="form-control" name="orgaoemissor" id="orgaoemissor" placeholder="" value="<?php
                            if (isset($pessoap["orgaoemissor"]) && $pessoap["orgaoemissor"] != NULL && $pessoap["orgaoemissor"] != "") {
                                echo $pessoap["orgaoemissor"];
                            }
                            ?>">
                        </div>    
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ufrg">UF</label>
                            <select class="form-control" name="ufrg" id="ufrg" title="Escolha o estado onde foi emitido o RG">
                                <?php
                                $resestado = $conexao->comando("select codestado, nome from estado order by nome");
                                $qtdestado = $conexao->qtdResultado($resestado);
                                if ($qtdestado > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($estado = $conexao->resultadoArray($resestado)) {
                                        if (isset($pessoap["ufrg"]) && $pessoap["ufrg"] == $estado["codestado"]) {
                                            echo '<option selected value="', $estado["codestado"], '">', $estado["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $estado["codestado"], '">', $estado["nome"], '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>                                                
                            </select>
                        </div>
                    </div><!-- /.col -->

                    <div class="col-md-3">                        
                        <div class="form-group">
                            <label for="dtemissaorg">Dt. Emissão</label>
                            <input type="date" class="form-control" name="dtemissaorg" id="dtemissaorg" title="Digite data de emissão do RG" value="<?php
                            if (isset($pessoap["dtemissaorg"])) {
                                echo $pessoap["dtemissaorg"];
                            }
                            ?>">
                        </div>                           
                    </div><!-- /.col -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="dtnascimento">Dt. Nascimento</label>
                            <input type="date" class="form-control" name="dtnascimento" id="dtnascimento" placeholder="" value="<?php
                            if (isset($pessoap["dtnascimento"])) {
                                echo $pessoap["dtnascimento"];
                            }
                            ?>">
                        </div>
                    </div><!-- /.col -->

                    <div class="col-md-3">                        
                        <div class="form-group">
                            <label for="localnascimento">Local Nasc.</label>
                            <input type='text' class="form-control" name="localnascimento" id="localnascimento" placeholder="" value="<?php
                            if (isset($pessoap["localnascimento"])) {
                                echo $pessoap["localnascimento"];
                            }
                            ?>">
                        </div>                          
                    </div><!-- /.col -->                    


                    <div class="col-md-3">

                        <div class="form-group">
                            <label for="cep">CEP</label>
                            <input type='text' class="form-control" name="cep" id="cep" maxlength="8" value="<?php
                            if (isset($pessoap["cep"])) {
                                echo $pessoap["cep"];
                            }
                            ?>">
                        </div>
                    </div><!-- /.col -->

                    <div class="col-md-3">                        
                        <div class="form-group">
                            <label for="tipologradouro">Tip. Logradouro</label>
                            <input type='text' class="form-control" name="tipologradouro" id="tipologradouro" maxlength="8" placeholder="" value="<?php
                            if (isset($pessoap["tipologradouro"])) {
                                echo $pessoap["tipologradouro"];
                            }
                            ?>">
                        </div>                         
                    </div><!-- /.col -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="logradouro">Logradouro</label>
                            <input type='text' class="form-control" name="logradouro" id="logradouro" placeholder="" value="<?php
                            if (isset($pessoap["logradouro"])) {
                                echo $pessoap["logradouro"];
                            }
                            ?>">
                        </div>
                    </div><!-- /.col -->

                    <div class="col-md-3">                        
                        <div class="form-group">
                            <label for="numero">Número</label>
                            <input type='text' class="form-control" name="numero" id="numero" placeholder="" value="<?php
                            if (isset($pessoap["numero"])) {
                                echo $pessoap["numero"];
                            }
                            ?>">
                        </div>                                        
                    </div><!-- /.col -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="bairro">Bairro</label>
                            <input type='text' class="form-control" name="bairro" id="bairro" placeholder="" value="<?php
                            if (isset($pessoap["bairro"])) {
                                echo $pessoap["bairro"];
                            }
                            ?>">
                        </div>
                    </div><!-- /.col -->

                    <div class="col-md-3">                        
                        <div class="form-group">
                            <label for="cidade">Cidade</label>
                            <input type='text' class="form-control" name="cidade" id="cidade" placeholder="" value="<?php
                            if (isset($pessoap["cidade"])) {
                                echo $pessoap["cidade"];
                            }
                            ?>">
                        </div>                          
                    </div><!-- /.col -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select class="form-control" name="estado" id="estado" title="Escolha estado onde ele mora">
                                <?php
                                $resestado = $conexao->comando("select * from estado order by nome");
                                $qtdestado = $conexao->qtdResultado($resestado);
                                if ($qtdestado > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($estado = $conexao->resultadoArray($resestado)) {
                                        if (isset($pessoap["ufrg"]) && $pessoap["ufrg"] == $estado["codestado"]) {
                                            echo '<option selected value="', $estado["codestado"], '">', $estado["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $estado["codestado"], '">', $estado["nome"], '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>                                                
                            </select>
                        </div>                        
                    </div><!-- /.col -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="estadocivil">Est. Civil</label>
                            <select class="form-control" name="estadocivil" id="estadocivil" title="Digite estado civil">
                                <?php
                                $resestado = $conexao->comando("select * from estadocivil order by nome");
                                $qtdestado = $conexao->qtdResultado($resestado);
                                if ($qtdestado > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($estado = $conexao->resultadoArray($resestado)) {
                                        if (isset($pessoap["estadocivil"]) && $pessoap["estadocivil"] == $estado["codestado"]) {
                                            echo '<option selected value="', $estado["codestado"], '">', $estado["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $estado["codestado"], '">', $estado["nome"], '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>
                        </div>                                  

                    </div><!-- /.col -->   

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="observacao">Observação</label>
                            <textarea class="form-control" name="observacao" id="observacao" placeholder=""></textarea>
                        </div>                                        
                    </div><!-- /.col -->
                </div>
            </div>
        </div>
        <!--/.col (right) -->
    </div>
    <div class="row">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Telefones</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">   

                    <div id="telefones">
                        <?php if (isset($pessoap["codpessoa"]) && $pessoap["codpessoa"] != NULL && $pessoap["codpessoa"] != "") { ?>
                            <a style="color: white;" class="btn btn-primary" href="javascript: importaTelefones();">Importar Telefones</a>
                        <?php } ?>
                        <?php
                        $sql = "select * from telefone where codpessoa = '{$pessoap["codpessoa"]}' and numero <> '' and codempresa = '{$_SESSION['codempresa']}'";
                        $restelefone = $conexao->comando($sql);
                        $qtdtelefone = $conexao->qtdResultado($restelefone);
                        if ($qtdtelefone > 0) {
                            echo "<span style='color: #337ab7; font-size: 12px;'>Encontrou {$qtdtelefone} telefones para esse cliente</span><br>";
                            while ($telefonep = $conexao->resultadoArray($restelefone)) {
                                $linha = 1;
                                ?>
                                <div class="col-md-12">
                                    <div class="col-md-3" style="padding-left: 0px">
                                        <div class="form-group">
                                            <label for="exampleInputFile">Operadora</label>
                                            <input class="form-control" type="text" name="operadora" id="operadora" value='<?php
                                            if (isset($telefonep["operadora"]) && $telefonep["operadora"] != NULL && $telefonep["operadora"] != "") {
                                                echo $telefonep["operadora"];
                                            }
                                            ?>'/>                                            </div>
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
                                            <input style="width: 80%" class="form-control telefone" type='text' name="telefone[]" id="telefone<?= $linha ?>" value="<?= reestruturandoTelefone($telefonep["numero"]) ?>" title="Digite aqui telefone" placeholder="(00)0000-0000">
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
                        ?>

                    </div>  

                </div>
            </div> 
        </div>
    </div>

    <div class="row">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Detalhadamento de Crédito</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">   
                    <div class="col-md-12">
                        <div class="form-group">
                            <div>
                                <div style="float: left;width: 100%;">
                                    <?php
                                    $configuracao = $conexao->comandoArray('select consultade from configuracao where codempresa = ' . $_SESSION["codempresa"]);
                                    if ($configuracao["consultade"] == 0) {
                                        $metodoImportar = 'ConsultaCpfInss2()';
                                    } elseif ($configuracao["consultade"] == 2) {
                                        $metodoImportar = 'consultaCPFAnaliseInfo()';
                                    } else {
                                        $metodoImportar = 'consultaCPFMultiBR()';
                                    }
                                    ?>
                                    <a class="btn btn-primary" style='float:left; margin-right: 50px;color: white;margin-bottom: 10px;' href="javascript: <?= $metodoImportar ?>">
                                        Importar / Atualizar
                                    </a>    

                                    <?php
                                    $coeficiente = new Coeficiente($conexao);
                                    $coeficientep = $coeficiente->procuraCoeficienteHoje();
                                    $coeficienteEmprestimoFinal = str_replace(".", ",", $coeficientep["valor"]);

                                    echo '</div>';
                                    echo '</div>';
                                    echo '<br>';
                                    echo '<table style="width: 70%;float: left;margin-bottom: 10px;">';
                                    echo '<tr>';
                                    echo '<td><label>Beneficiário:', $pessoap["nome"], '</label></td>';

                                    echo '</tr>';
                                    echo '<tr>';

                                    if ($pessoap["idade"] != NULL && $pessoap["idade"] != "" && $pessoap["idade"] < 100) {
                                        $idade = $pessoap["idade"];
                                    } else {
                                        $idade = "Sem dt. nascimento";
                                    }
                                    echo '<td><label>Idade:', $idade, '</label></td>';
                                    echo '<td><label>COEFICIENTE</label><input class="porcentagemMaisCasas" style="float: right;width: 90px;" type="text" size="20" name="coeficiente" id="coeficiente" value="', $coeficienteEmprestimoFinal, '"/></td>';
                                    echo '</tr>';
                                    echo '</table>';
                                    ?>
                                </div>
                            </div>
                            <?php
                            echo '<div id="beneficio_aposentado">';

                            echo '</div>';
                            ?>

                        </div> 
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Agendamentos</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">   
                        <div class="col-md-6">
                            <div class="form-group" style="float: left;margin-right: 40px;">
                                <label style="float: left;margin-bottom: 0;">Agendar</label><br>
                                <input style="width: 100%;float: left;" class="form-control" type="date" name="dtagenda" id="dtagenda" min="<?= date('Y-m-d') ?>" value="" title="defina a data de agendamento para o cliente voltar a aparecer na tela"/> 
                            </div>
                            <div class="form-group" style="float: left;">
                                <label style="float: left;margin-bottom: 0;">Situação</label><br>
                                <select style="width: 100%;float: left;" name="codstatus" id="codstatus" class="form-control" title="escolha aqui um status da pessoa para o agendamento">
<?php
$resstatus = $conexao->comando("select codstatus, nome from statuspessoa order by nome");
$qtdstatus = $conexao->qtdResultado($resstatus);
if ($qtdstatus > 0) {
    echo '<option value="">--Selecione--</option>';
    while ($status = $conexao->resultadoArray($resstatus)) {
        if (isset($pessoa["codstatus"]) && $pessoa["codstatus"] == $status["codstatus"]) {
            echo '<option selected value="', $status["codstatus"], '">', $status["nome"], '</option>';
        } else {
            echo '<option value="', $status["codstatus"], '">', $status["nome"], '</option>';
        }
    }
} else {
    echo '<option value="">--Nada encontrado--</option>';
}
?>
                                </select>                            
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Hora Agenda</label>
                            <input class="form-control" style="width: 80%" type="time" name="horaagenda" id="horaagenda" value=""/>
                            <a style="float: right;margin-top: -35px;margin-right: 20px; color: white" href="javascript:inserirAgenda()" class="btn btn-primary" title="Clique aqui para agendar retorno ao cliente" onclick="">Agendar</a>                                   
                        </div>
                        <div id="observacao" class="col-md-12">
                            <label>OBSERVAÇÃO</label>
                            <p>
                                <textarea class="form-control" name="observacaoLigacao" id="observacaoLigacao" placeholder=""></textarea>     
                            </p>
<?php if (isset($_GET["codpessoa"]) && $_GET["codpessoa"] != NULL && $_GET["codpessoa"] != "") { ?>
                                <div id="observacao_anterior">
                                    <label>Histórico de Ligações</label>
    <?php
    $sql = 'select agenda.*, DATE_FORMAT(agenda.dtcadastro, "%d/%m/%Y") as dtcadastro2, pessoa.nome as funcionario, status.nome as status,
                                DATE_FORMAT(agenda.dtagenda, "%d/%m/%Y") as dtagenda2    
                                from agenda
                                left join pessoa on pessoa.codpessoa = agenda.codfuncionario and pessoa.codempresa = agenda.codempresa
                                left join statuspessoa as status on status.codstatus = agenda.codstatus
                                where agenda.codempresa = ' . $_SESSION['codempresa'] . ' and agenda.codpessoa = ' . $_GET["codpessoa"] . '
                                order by agenda.dtagenda desc';
//                                        echo "<pre>{$sql}</pre>";
    $resobservacaoLigacao = $conexao->comando($sql)or die("Erro ao procurar histórico de chamadas!!! Causado por:" . mysqli_error($conexao->conexao));
    $qtdobservacaoLigacao = $conexao->qtdResultado($resobservacaoLigacao);
    if ($qtdobservacaoLigacao > 0) {
        echo '<table class="form-control" style="height: auto;">';
        echo '<thead>';
        echo '<tr>';
        echo '<th style="width: 15%;">Data</th>';
        echo '<th style="width: 15%;">Agendamento</th>';
        echo '<th style="width: 25%;">Atendente</th>';
        echo '<th>Status</th>';
        echo '<th>Observações</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        while ($observacaoLigacao = $conexao->resultadoArray($resobservacaoLigacao)) {
            echo '<tr>';
            echo '<td>', $observacaoLigacao["dtcadastro2"], '</td>';
            echo '<td>', $observacaoLigacao["dtagenda2"], '</td>';
            echo '<td>', $observacaoLigacao["funcionario"], '</td>';
            echo '<td>', $observacaoLigacao["status"], '</td>';
            echo '<td>', $observacaoLigacao["observacao"], '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    }
    ?>
                                </div>
                                <?php } ?>
                        </div> 
                    </div>
                </div> 
            </div>
        </div>
<?php if (isset($pessoap["codcategoria"]) && $pessoap["codcategoria"] != NULL && $pessoap["codcategoria"] == 1) { ?>
            <div class="row">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Propostas</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">   
                            <div id="listagemProposta2" class="col-md-12"></div>
                        </div>
                    </div> 
                </div>
            </div>     
<?php } ?>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
<?php if ($nivelp["inserir"] == 1 || $nivelp["atualizar"] == 1) { ?>
                        <input type="submit" name="submit" value="Salvar" class="btn btn-primary"/>
                        <?php if(isset($_GET["callcenter"])){?>
                        <input type="button" name="submit" id="btProximo" title="Faça um agendamento para o cliente antes!!!" value="Próximo" onclick="location.reload();" disabled class="btn btn-primary"/>
                        <?php
                        }
                    }
                    if ($nivelp["inserir"] == 1 || $nivelp["atualizar"] == 1) {
                        ?>
                        <button style="margin-left: 5px;" onclick="btNovoPessoa()" id="btnovoPessoa"  class="btn btn-primary">Novo</button>
                    <?php } ?>
                </div>                                        
            </div>
        </div>      
</form>