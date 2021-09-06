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
                <form action="../control/ProcurarFluxoCaixaRelatorio.php" target="_blank" id="ffluxo" name="ffluxo" method="post" onsubmit="return false;">
                    <input type="hidden" name="codnivel" id="codnivel" value="<?= $_GET["codnivel"] ?>"/>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="sexo">Movimentação</label>
                            <select class="form-control" name="movimentacao" id="movimentacao" title="Selecione aqui a movimentação do caixa">
                                <option value="T" title="Traz créditos e débitos juntos">Todos</option>
                                <option value="R" title="Traz somente créditos">Crédito</option>
                                <option value="D" title="Traz somente débitos">Débito</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sexo">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" value=""/>
                        </div>                        
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="sexo">Dt. Inicio</label>
                            <input type="url" class="form-control" name="data" id="data" value=""/>
                        </div>
                        <div class="form-group">
                            <label for="sexo">Dt. Fim</label>
                            <input type="url" class="form-control" name="data2" id="data2" value=""/>
                        </div>                        
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="sexo">Unidade</label>
                            <select class="form-control" name="unidade" id="unidade">
                            <?php
                            $sql = "select codempresa, razao from empresa order by razao";
                            $resempresa2 = $conexao->comando($sql);
                            $qtdempresa2 = $conexao->qtdResultado($resempresa2);
                            if($qtdempresa2 > 0){
                                echo '<option value="">--Selecione--</option>';
                                while($empresa2 = $conexao->resultadoArray($resempresa2)){
                                    echo '<option value="',$empresa2["codempresa"],'">',$empresa2["razao"],'</option>';
                                }
                            }else{
                                echo '<option value="">--Nada encontrado--</option>';
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button onclick="procurarFluxo(false)" class="btn btn-primary">Procurar</button>
                        <button onclick="abreRelatorioFluxo()" class="btn btn-primary">Gera PDF</button>
                        <button onclick="abreRelatorio2Fluxo()" class="btn btn-primary">Gera Excel</button>
                    </div>                                        
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" id="listagem"></div>
            </div>            
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->