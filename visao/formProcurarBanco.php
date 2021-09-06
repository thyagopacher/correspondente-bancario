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
                <form action="../control/ProcurarBancoRelatorio.php" target="_blank" id="fProcurarBanco" name="fProcurarBanco" method="post" onsubmit="return false;">
                    <input type="hidden" name="codnivel" id="codnivel" value="<?= $_GET["codnivel"] ?>"/>
                    <input type="hidden" name="tipo" id="tipo" value="pdf"/>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">Cód. Banco</label> 
                            <input type="text" class="form-control" name="numbanco" id="numbanco" value=""/>
                        </div>

                        <div class="form-group">
                            <label for="sexo">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" value=""/>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">CNPJ</label>
                            <input type="text" class="form-control" name="cnpj" id="cnpj" value=""/>
                        </div>
                        <div class="form-group">
                            <label for="sexo">Site</label>
                            <input type="url" class="form-control" name="site" id="site" value=""/>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <button class="btn btn-primary" onclick="procurarBanco(false)">Procurar</button>
                        <button class="btn btn-primary" onclick="abreRelatorioBanco()">Gera PDF</button>
                        <button class="btn btn-primary" onclick="abreRelatorioBanco2()">Gera Excel</button>
                    </div>                                        
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" id="listagemBanco"></div>
            </div>            
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->