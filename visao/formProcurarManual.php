<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form" id="fPmanual" action="../control/ProcurarManualRelatorio.php" target="_blank"  name="fPmanual" method="post" onsubmit="return false;">
                <input type="hidden" name="codmanual" id="codmanual" value="<?= $_GET["codmanual"] ?>"/>
                <input type="hidden" name="tipo" id="tipo" value="pdf"/>
                <div class="box-body">
                    <div class="form-group col-md-3">
                        <label for="nome">Código Banco</label>
                        <input type='text' class="form-control" name="codbanco1" id="codbanco1">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="statusPessoa">Banco</label>
                        <select class="form-control" name="codbanco" id="codbanco" title="Escolha aqui o banco para o manual">
                            <?php
                            $resbanco = $conexao->comando("select * from banco where nome <> '' order by nome");
                            $qtdbanco = $conexao->qtdResultado($resbanco);
                            if ($qtdbanco > 0) {
                                echo '<option value="">--Selecione--</option>';
                                while ($banco = $conexao->resultadoArray($resbanco)) {
                                    echo '<option value="', $banco["codbanco"], '">', $banco["nome"], '</option>';
                                }
                            } else {
                                echo '<option value="">--Nada encontrado--</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="localnascimento">Nome</label>
                        <input type='text' class="form-control" name="nome" id="nome" placeholder="Digite nome do manual">
                    </div>                                 

                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button class="btn btn-primary" onclick="procurarManual(false)">Procurar</button>
                    <?php
                    if(isset($nivelp["gerapdf"]) && $nivelp["gerapdf"] == 1){
                        echo '<button class="btn btn-primary" onclick="abreRelatorioManual()">Gera PDF</button> ';
                    }
                    if(isset($nivelp["geraexcel"]) && $nivelp["geraexcel"] == 1){
                        echo '<button class="btn btn-primary" onclick="abreRelatorioManual2()">Gera Excel</button>';
                    }
                    ?>
                </div>
            </form>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
    <!--/.col (right) -->
</div>   <!-- /.row -->

<div class="row">
    <div class="col-sm-12" id="listagemManual"></div>
</div>