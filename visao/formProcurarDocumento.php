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
                <form id="fdocumento" name="fdocumento" method="post">
                    <input type="hidden" name="coddocumento" id="coddocumento" value="<?= $_GET["coddocumento"] ?>"/>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" value=""/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">Banco</label>
                            <select class="form-control" name="codbanco" id="codbanco">
                                <?php
                                $resbanco = $conexao->comando("select * from banco order by nome");
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
                    </div>                    
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <button class="btn btn-primary" onclick="procurarDocumento(false)">Procurar</button>
                        <?php
                        if(isset($nivelp["gerapdf"]) && $nivelp["gerapdf"] == 1){
                            echo '<button class="btn btn-primary" onclick="abreRelatorioDocumento()">Gera PDF</button> ';
                        }
                        if(isset($nivelp["geraexcel"]) && $nivelp["geraexcel"] == 1){
                            echo '<button class="btn btn-primary" onclick="abreRelatorio2Documento()">Gera Excel</button>';
                        }
                        ?>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->