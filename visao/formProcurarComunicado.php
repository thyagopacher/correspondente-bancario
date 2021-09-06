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
                <form id="fPcomunicado" name="fPcomunicado" method="post" target="_blank" action='../control/ProcurarComunicadoRelatorio.php'>
                    <input type="hidden" name="tipo" id="tipo" value="pdf"/>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" value=""/>
                        </div>
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
                        <button class="btn btn-primary" onclick="procurarComunicado(false)">Procurar</button>
                        <?php
                        if(isset($nivelp["gerapdf"]) && $nivelp["gerapdf"] == 1){
                            echo '<button class="btn btn-primary" onclick="abreRelatorioComunicado()">Gera PDF</button> ';
                        }
                        if(isset($nivelp["geraexcel"]) && $nivelp["geraexcel"] == 1){
                            echo '<button class="btn btn-primary" onclick="abreRelatorioComunicado2()">Gera Excel</button>';
                        }
                        ?> 
                    </div>                                        
                </div>
            </div>
            <div id='listagemComunicado' class='col-lg-12'></div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->