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
                <form action="<?= $action ?>" id="fremaneja" name="fremaneja" method="post">
                    <input type="hidden" name="codnivel" id="codnivel" value="<?=$_GET["codnivel"]?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Operador</label>
                            <select class="form-control" name="operador" id="operador">
                                <?php
                                $sql = "select pessoa.nome, pessoa.codpessoa 
                                from pessoa 
                                inner join nivel on nivel.codnivel = pessoa.codnivel
                                where pessoa.codempresa = '{$_SESSION['codempresa']}' and pessoa.codcategoria not in(1,6) order by pessoa.nome";
                                $respessoa = $conexao->comando($sql);
                                $qtdpessoa = $conexao->qtdResultado($respessoa);
                                if($qtdpessoa > 0){
                                    echo '<option value="">--Selecione--</option>';
                                    while($pessoa = $conexao->resultadoArray($respessoa)){
                                        echo '<option value="',$pessoa["codpessoa"],'">',$pessoa["nome"],'</option>';
                                    }
                                }else{
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>
                        </div>
                                        
                        <div class="form-group">
                            <label for="sexo">Data</label>
                            <input type='date' class="form-control" name="dtagenda" id="dtagenda" title="Digite dt.agenda" value="">
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="statusPessoa">Cliente</label>
                            <select class="form-control" name="cliente" id="cliente">
                                <?php
                                $rescliente = $conexao->comando("select pessoa.codpessoa, pessoa.nome from pessoa where pessoa.codcategoria in(1,6) and codempresa = '{$_SESSION['codempresa']}'");
                                $qtdcliente = $conexao->qtdResultado($rescliente);
                                if($qtdcliente > 0){
                                    echo '<option value="">--Selecione--</option>';
                                    while($cliente = $conexao->resultadoArray($rescliente)){
                                        echo '<option value="',$cliente["codpessoa"],'">',$cliente["nome"],'</option>';
                                    }
                                }else{
                                    echo '<option value="">--Nada encontradoo--</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="submit" name="submit" id="submit" value="Salvar" class="btn btn-primary"/>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>