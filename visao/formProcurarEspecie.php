<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="../control/ProcurarEspecieRelatorio.php"  target="_blank" id="fpespecie" name="fpespecie" method="post"  onsubmit="return false;">
                <input type="hidden" name="codespecie" id="codespecie" value="<?= $_GET["codespecie"] ?>"/>
                <input type="hidden" name="tipo" id="tipo" value="pdf"/>
                <div class="box-body">

                    <div class="form-group">
                        <label for="localnascimento">Nome</label>
                        <input type='text' class="form-control" name="nome" id="nome" placeholder="Digite nome do especie">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputFile">Num. INSS</label>
                        <input type='text' class="form-control" name="numinss" id="numinss" placeholder="Digite numinss da especie">
                    </div>                                        

                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button class="btn btn-primary" onclick="procurarEspecie(false)">Procurar</button>
                    <button class="btn btn-primary" onclick="abreRelatorioEspecie()">Gera PDF</button>
                    <button class="btn btn-primary" onclick="abreRelatorio2Especie()">Gera Excel</button>    
                </div>
            </form>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
</div><!--/.col (left) -->
<!--/.col (right) -->

<div class="row">
    <div class="col-sm-12" id="listagem"></div>
</div>