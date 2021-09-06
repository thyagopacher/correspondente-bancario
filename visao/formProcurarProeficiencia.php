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
                <form action="../control/ProcurarProeficienciaRelatorio.php" target="_blank" id="fpproeficiencia" name="fpproeficiencia" method="post" onsubmit="return false;">
                    <input type="hidden" name="codnivel" id="codnivel" value="<?= $_GET["codnivel"] ?>"/>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="sexo">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" value=""/>
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
                        <button class="btn btn-primary" onclick="procurarProeficiencia(false)">Procurar</button>
                        <?php
                        if (isset($nivelp["gerapdf"]) && $nivelp["gerapdf"] == 1) {
                            echo '<button class="btn btn-primary" onclick="abreRelatorioProeficiencia()">Gera PDF</button> ';
                        }
                        if (isset($nivelp["geraexcel"]) && $nivelp["geraexcel"] == 1) {
                            echo '<button class="btn btn-primary" onclick="abreRelatorio2Proeficiencia()">Gera Excel</button>';
                        }
                        ?>
                    </div>                                        
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" id="listagemProeficiencia"></div>
            </div>            
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->