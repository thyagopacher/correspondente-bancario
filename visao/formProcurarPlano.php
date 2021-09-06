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
                <form action="../control/ProcurarPlanoRelatorio.php" target="_blank" id="fPplano" name="fPplano" method="post" onsubmit="return false;">
                    <input type="hidden" name="codnivel" id="codnivel" value="<?= $_GET["codnivel"] ?>"/>
                    <input type="hidden" name="tipo" id="tipo" value="pdf"/>
                    <div class="col-md-3">

                        <div class="form-group">
                            <label for="sexo">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" value=""/>
                        </div>
                        <!-- /.form-group -->
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <button class="btn btn-primary" onclick="procurarPlano(false)">Procurar</button>
                        <button class="btn btn-primary" onclick="abreRelatorioPlano()">Gera PDF</button>
                        <button class="btn btn-primary" onclick="abreRelatorioPlano2()">Gera Excel</button>
                    </div>                                        
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" id="listagemPlano"></div>
            </div>            
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->