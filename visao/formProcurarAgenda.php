<div class="row">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Procurar Agenda</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <form action="../control/ProcurarAgendaRelatorio.php" name="fpagenda" id="fpagenda" onsubmit="return false;" target="_blank">
                    <input type="hidden" name="tipoAgenda" id="tipoAgenda" value="pdf"/>
<?php
        if(isset($_GET["callcenter"]) && $_GET["callcenter"] != NULL && $_GET["callcenter"] == "true"){
            echo '<input type="hidden" name="codcategoria" id="codcategoria" value="6"/>';
        }else{
            echo '<input type="hidden" name="codcategoria" id="codcategoria" value="1"/>';
        }
?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">Dt. Inicio</label>
                            <input type="date" class="form-control" name="data1" id="data1" title="Digite data de inicio onde foi feito o cadastro">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">Dt. Fim</label>
                            <input type="date" class="form-control" name="data2" id="data2" title="Digite data de fim onde foi feito o cadastro">
                        </div>
                    </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button class="btn btn-primary" type="button" onclick="procurarAgenda2(false)" class="btn btn-primary">Procurar</button>
                        <button class="btn btn-primary" type="button" onclick="abreRelatorioAgenda()" class="btn btn-primary">Gerar PDF</button>
                        <button class="btn btn-primary" type="button" onclick="abreRelatorio2Agenda()" class="btn btn-primary">Gerar Excel</button>
                    </div>                                        
                </div>
            </div>
            </form>
            <div class="row" id="listagemAgenda"></div>
        </div>
    </div>
    <!--/.col (right) -->
</div>