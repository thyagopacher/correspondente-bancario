<div class="row">
    <!-- left column -->
    <div class="col-md-6">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form" id="fplink" action="../control/ProcurarLinkRelatorio.php" target="_blank"  name="fplink" method="post" onsubmit="return false;">
                <input type="hidden" name="tipo" id="tipo" value="pdf"/>
                <div class="box-body">
                   

                    <div class="form-group">
                        <label for="localnascimento">Nome</label>
                        <input type='text' class="form-control" name="nome" id="nome" placeholder="Digite nome do link">
                    </div>                                 

                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button class="btn btn-primary" onclick="procurarLink(false)">Procurar</button>
                    <?php
                    if(isset($nivelp["gerapdf"]) && $nivelp["gerapdf"] == 1){
                        echo '<button class="btn btn-primary" onclick="abreRelatorioLink()">Gera PDF</button> ';
                    }
                    if(isset($nivelp["geraexcel"]) && $nivelp["geraexcel"] == 1){
                        echo '<button class="btn btn-primary" onclick="abreRelatorioLink2()">Gera Excel</button>';
                    }
                    ?>
                </div>
            </form>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
    <!--/.col (right) -->
</div>   <!-- /.row -->

<div class="row">
    <div class="col-sm-12" id="listagemLink"></div>
</div>