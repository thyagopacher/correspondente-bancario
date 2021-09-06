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
                <form id="fpSouthConsulta" name="fpSouthConsulta" method="post" onsubmit="return false;">
                    <input type="hidden" name="tipo" id="tipo" value="pdf"/>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">Razão</label>
                            <input type='text' class="form-control" name="razao" id="razao" placeholder="Digite razão" value="">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Dt. Inicio</label>
                            <input type="date" name="data1" id="data1" class="form-control" value=""/>                            
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Dt. Fim</label>
                            <input type="date" name="data2" id="data2" class="form-control" value=""/>                            
                        </div>
                    </div>
                    
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button class="btn btn-primary" onclick="procurarSouthConsulta(false)">Procurar</button>
                    </div>                                        
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" id="listagemSouthConsulta"></div>
            </div>            
        </div>
    </div>
    <!--/.col (right) -->
</div>