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
                <form action="../control/ProcurarEmpresaRelatorio.php" target="_blank" id="fpempresa" name="fpempresa" method="post">
                    <input type="hidden" name="codnivel" id="codnivel" value="<?= $_GET["codnivel"] ?>"/>
                    <input type="hidden" name="tipoRel" id="tipoRel" value="pdf"/>
                    <?php if(!isset($_GET["codramo"]) && !isset($_GET["tipo"])){?>
                    <input type="hidden" name="codcategoria" id="codcategoria" value="1"/>
                    <?php }elseif(isset($_GET["codramo"]) && !isset($_GET["tipo"])){?>
                    <input type="hidden" name="codcategoria" id="codcategoria" value="2"/>
                    <?php }elseif(isset($_GET["codramo"]) && !isset($_GET["tipo"]) && isset($_GET["tipo"])){?>
                    <input type="hidden" name="codcategoria" id="codcategoria" value="4"/>
                    <?php }?>
                    <input type="hidden" name="codramo" id="codramo" value="<?php if(isset($_GET["codramo"])){echo $_GET["codramo"];}?>"/>
                    <input type="hidden" name="tipo" id="tipo" value="<?php if(isset($_GET["tipo"])){echo $_GET["tipo"];}?>"/>
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
                <div class="col-md-6">
                    <div class="form-group">
                        <button class="btn btn-primary" onclick="procurarEmpresa(false)">Procurar</button>
                        <button class="btn btn-primary" onclick="abreRelatorio()">Gera PDF</button>
                        <button class="btn btn-primary" onclick="abreRelatorio2()">Gera Excel</button> 
                    </div>                                        
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" id="listagemEmpresa"></div>
            </div>            
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->