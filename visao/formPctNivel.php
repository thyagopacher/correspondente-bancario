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

            <form action="<?= $action ?>" id="fpctnivel" name="fpctnivel" method="post">
                <div class="row">
                    <input type="hidden" name="codpct" id="codpct" value=""/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Nivel</label>
                            <select class="form-control" name="codnivel" id="codnivelPct">
                                <?php
                                $resnivel = $conexao->comando('select codnivel, nome from nivel where (padrao = "s" and codnivel <> 19) or codempresa = ' . $_SESSION["codempresa"]);
                                $qtdnivel = $conexao->qtdResultado($resnivel);
                                if ($qtdnivel > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($nivel = $conexao->resultadoArray($resnivel)) {
                                        echo '<option value="', $nivel["codnivel"], '">', $nivel["nome"], '</option>';
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>                        
                        </div>

                        <div class="form-group">
                            <label for="sexo">Porcentagem sobre Comissão</label>
                            <input type='text' class="form-control real" name="porcentagem" id="porcentagem" title="Digite porcentagem" value="<?php if (isset($nivel["porcentagem"]) && $nivel["porcentagem"] != NULL && $nivel["porcentagem"] != "") {
                                    echo $nivel["porcentagem"];
                                } ?>">
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php if (!isset($_GET["codnivel"])) { ?>
                                <input type="button" name="submit" id="submit" value="Salvar" class="btn btn-primary" onclick="inserirPctNivel();"/>
                            <?php } else { ?>
                                <input type="button" name="submit" id="submit" value="Salvar" class="btn btn-primary" onclick="atualizarNivel();"/>
                            <?php } ?>
                        </div>                                        
                    </div>
                </div>
            </form>
                <div class="row">
                    <div class="col-md-12" id="listagemPctNivel"></div>
                </div>
            <!-- /.row -->

        </div>
    </div>
    <!--/.col (right) -->
</div>