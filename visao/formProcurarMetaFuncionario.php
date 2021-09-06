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
                <form action="../control/ProcurarMetaFuncionarioRelatorio.php" id="fPmeta" name="fPmeta" method="post" onsubmit="return false;">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">Colaborador</label>
                            <select class="form-control" name="codfuncionario" id="codfuncionario">
                                <?php
                                $resFuncionario = $conexao->comando("select codpessoa, nome from pessoa where codcategoria not in(1,6) and codempresa = '{$_SESSION['codempresa']}' and status = 'a' order by nome");
                                $qtdFuncionario = $conexao->qtdResultado($resFuncionario);
                                if ($qtdFuncionario > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($funcionario = $conexao->resultadoArray($resFuncionario)) {
                                        if (isset($meta["codfuncionario"]) && $meta["codfuncionario"] == $funcionario["codpessoa"]) {
                                            echo '<option selected value="', $funcionario["codpessoa"], '">', $funcionario["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $funcionario["codpessoa"], '">', $funcionario["nome"], '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sexo">Meta</label>
                            <input type="text" class="form-control real" name="valor" id="valorMeta" value=""/>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Dt Inicio</label>
                            <input type="date" class="form-control" name="data1" id="data1" value=""/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Dt. Fim</label>
                            <input type="date" class="form-control" name="data2" id="data2" value=""/>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <button class="btn btn-primary" onclick="procurarMetaFuncionario(false)">Procurar</button>
                        <button class="btn btn-primary" onclick="abreRelatorioMetaFuncionario()">Gera PDF</button>
                        <button class="btn btn-primary" onclick="abreRelatorio2MetaFuncionario()">Gera Excel</button>
                    </div>                                        
                </div>
            </div>
            <div id="listagemMetaFuncionario" class="col-md-12"></div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->