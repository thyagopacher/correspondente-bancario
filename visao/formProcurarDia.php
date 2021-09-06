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
                <form action="../control/ProcurarDiaRelatorio.php" id="fpdia" name="fpdia" method="post">
                    <input type="hidden" name="tipo" id="tipo" value="pdf"/>
                    <div class="col-md-12">
                        <div class="form-group col-md-6">
                            <label for="nome">Dt. Inicio</label>
                            <input type="date" class="form-control" name="data1" id="data1" value=""/>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nome">Dt. Fim</label>
                            <input type="date" class="form-control" name="data2" id="data2" value=""/>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <button class="btn btn-primary" onclick="procurarDia(false)">Procurar</button>
                        <button class="btn btn-primary" onclick="abreRelatorioDia()">Gera PDF</button>
                        <button class="btn btn-primary" onclick="abreRelatorioDia2()">Gera Excel</button>
                    </div>                                        
                </div>
            </div>
            <div id="listagemDia" class="col-md-12"></div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->