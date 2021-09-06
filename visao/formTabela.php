

<form id="ftabela" name="ftabela" method="post">
    <input type="hidden" name="codtabela" id="codtabela" value="<?= $_GET["codtabela"] ?>"/>
    <div class="row">
        <div class="box box-default">
            <div class="box-header with-border">


                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Tabela</label>
                            <input type="text" class="form-control" name="nome" id="nome" value="<?php
                            if (isset($tabela["nome"])) {
                                echo $tabela["nome"];
                            }
                            ?>"/>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Código</label>
                            <input class="form-control" type="text" name="codbanco1" id="codbanco1" value=""/>
                        </div>
                    </div>
                    <div class="col-md-3">                        
                        <div class="form-group">
                            <label>Banco</label>
                            <select class="form-control" name="codbanco" id="codbanco" required>
                                <?php
                                $resBanco = $conexao->comando("select * from banco where nome <> '' order by nome");
                                $qtdBanco = $conexao->qtdResultado($resBanco);
                                if ($qtdBanco > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($banco = $conexao->resultadoArray($resBanco)) {
                                        if (isset($tabela["codbanco"]) && $tabela["codbanco"] != NULL && $tabela["codbanco"] == $banco["codbanco"]) {
                                            echo '<option numero="', $banco["numbanco"], '" selected value="', $banco["codbanco"], '">', $banco["numbanco"], ' - ', $banco["nome"], '</option>';
                                        } else {
                                            echo '<option numero="', $banco["numbanco"], '" value="', $banco["codbanco"], '">', $banco["numbanco"], ' - ', $banco["nome"], '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>                            
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Convênio</label>
                            <select class="form-control" name="codconvenio" id="codconvenio" required>
                                <?php
                                $resBanco = $conexao->comando("select * from convenio where nome <> '' order by nome");
                                $qtdBanco = $conexao->qtdResultado($resBanco);
                                if ($qtdBanco > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($convenio = $conexao->resultadoArray($resBanco)) {
                                        if (isset($tabela["codconvenio"]) && $tabela["codconvenio"] != NULL && $tabela["codconvenio"] == $convenio["codconvenio"]) {
                                            echo '<option selected value="', $convenio["codconvenio"], '">', $convenio["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $convenio["codconvenio"], '">', $convenio["nome"], '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>                            
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <div class="row">
                <div class="box-default">
                    <!--<div class="box-header with-border"></div>-->
                    <div class="box-body" id="linhas_tabela_prazo">
                        <?php
                        if (isset($_GET["codtabela"])) {
                            $and .= ' and codtabela = ' . $_GET["codtabela"];
                            $linha_tabela_prazo = 0;
                            $sql = 'select * from tabelaprazo where 1 = 1 and (prazode > 0  or prazoate > 0) ' . $and;
                            $restabelaprazo = $conexao->comando($sql);
                            $qtdtabelaprazo = $conexao->qtdResultado($restabelaprazo);
                            echo '<input type="hidden" name="qtdtabelaprazo" id="qtdtabelaprazo" value="',$qtdtabelaprazo,'"/>';
                            if ($qtdtabelaprazo > 0) {
                                while ($tabelaprazo = $conexao->resultadoArray($restabelaprazo)) {
                                    $mostrar_tabela_prazo = "s";
                                    include './linhaTabelaPrazo.php';
                                    $linha_tabela_prazo++;
                                }
                            }
                        }
                        ?>
                    </div> 
                </div>
            </div>                

            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php
                        if (!isset($_GET["codtabela"])) {
                            if ($nivelp["inserir"] == 1) {
                                echo '<input class="btn btn-primary" type="button" name="submit" value="Cadastrar" id="btinserirTabela" onclick="inserirTabela();"/>';
                            }
                        } else {
                            if ($nivelp["atualizar"] == 1) {
                                echo '<input class="btn btn-primary" style="margin-left: 5px;" type="button" name="submit" id="btatualizarTabela" value="Atualizar" onclick="atualizarTabela();"/>';
                            }
                            if ($nivelp["excluir"] == 1) {
                                echo '<button class="btn btn-primary" style="margin-left: 5px;" onclick="excluirTabela()" id="btexcluirTabela">Excluir</button>';
                            }
                            echo '<button class="btn btn-primary" style="margin-left: 5px; display: none" onclick="btNovoTabela()" id="btnovoTabela">Novo</button>';
                        }
                        ?>
                    </div>                                        
                </div>
            </div>                    
            </form>


        </div>
    </div>
    <!--/.col (right) -->
    <?php
    //modelo da tabela prazo que é pego para facilitar a manipulação do DOM
    $mostrar_tabela_prazo = "";
    include './linhaTabelaPrazo.php';
    ?>