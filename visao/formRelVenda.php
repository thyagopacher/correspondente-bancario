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
                <form action="../control/ProcurarVendaRelatorio.php" target="_blank" id="fvenda" name="fvenda" method="post">
                    <input type="hidden" name="tipo" id="tipo" value="pdf"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Operador</label>
                            <select class="form-control" name="codfuncionario" id="codfuncionario">
                                <?php
                                $resfuncionario = $conexao->comando("select codpessoa, nome from pessoa where senha <> '' and codcategoria not in(1,6) and codempresa = '{$_SESSION['codempresa']}'");
                                $qtdfuncionario = $conexao->qtdResultado($resfuncionario);
                                if ($qtdfuncionario > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($funcionario = $conexao->resultadoArray($resfuncionario)) {
                                        echo '<option value="', $funcionario["codpessoa"], '">', strtoupper($funcionario["nome"]), '</option>';
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sexo">Status</label>
                            <select class="form-control" name="codstatus" id="codstatus">
                                <?php
                                $resstatus = $conexao->comando("select * from statusproposta order by nome");
                                $qtdstatus = $conexao->qtdResultado($resstatus);
                                if ($qtdstatus > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($status = $conexao->resultadoArray($resstatus)) {
                                        echo '<option value="', $status["codstatus"], '">', $status["nome"], '</option>';
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Dt Inicio</label>
                            <input type="date" class="form-control" name="data1" id="data1" value=""/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Dt. Fim</label>
                            <input type="date" class="form-control" name="data2" id="data2" value=""/>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php if ($nivelp["procurar"] == 1) { ?>
                            <button class="btn btn-primary" onclick="procurarVenda(false)">Procurar</button>
                        <?php } elseif ($nivelp["gerapdf"] == 1) { ?>      
                            <button class="btn btn-primary" onclick="abreRelatorioVenda()">Gera PDF</button>
                        <?php } elseif ($nivelp["geraexcel"] == 1) { ?>      
                            <button class="btn btn-primary" onclick="abreRelatorioVenda2()">Gera Excel</button>
                        <?php } ?>
                    </div>                                        
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" id="listagemProposta"></div>
            </div>               
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->