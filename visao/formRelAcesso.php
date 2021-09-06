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
                <form action="../control/ProcurarAcessoRelatorio.php" id="fpacesso" name="fpacesso" method="post">
                    <input type="hidden" name="codnivel" id="codnivel" value="<?= $_GET["codnivel"] ?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" value=""/>
                        </div>

                        <div class="form-group">
                            <label for="sexo">Nivel</label>
                            <select class="form-control"  name="codnivel" id="codnivel">
                            <?php
                            $sql = "select * from nivel where codempresa = {$_SESSION['codempresa']} order by nome";
                            $resnivelProcurar = $conexao->comando($sql);
                            $qtdnivelProcurar = $conexao->qtdResultado($resnivel);
                            if($qtdnivelProcurar > 0){
                                echo '<option value="" title="Deixando selecione ele procura de qualquer nivel">--Selecione--</option>';
                                while($nivelProcurar = $conexao->resultadoArray($resnivelProcurar)){
                                    echo '<option value="',$nivelProcurar["codnivel"],'">',$nivelProcurar["nome"],'</option>';
                                }

                            }else{
                                echo '<option value="">--Nada encontrado--</option>';
                            }
                            ?>
                            </select> 
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Dt Inicio</label>
                            <input type="date" class="form-control" name="data1" id="data1" value=""/>
                        </div>
                    </div>
                    <div class="col-md-6">
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
                    <?php if ($nivelp["procurar"] == 1) { ?>
                        <button class="btn btn-primary" onclick="procurarAcesso(false)">Procurar</button>
                        <button class="btn btn-primary" onclick="abreRelatorio()">Gera PDF</button>
                        <button class="btn btn-primary" onclick="abreRelatorio2()">Gera Excel</button>
                    <?php } ?>  
                    </div>                                        
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" id="listagem"></div>
            </div>               
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->