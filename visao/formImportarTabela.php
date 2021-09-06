<div class="row">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Importar</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <form id="fpproeficiencia" name="fpproeficiencia" method="post" onsubmit="return false;">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="sexo">Arquivo</label>
                            <input type="file" class="form-control" name="arquivo" id="arquivo" value="" title="Escolha aqui sua tabela .csv"/>
                        </div>
                        <!-- /.form-group -->
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php
                        if ($nivelp["inserir"] == 1) {
                            echo '<input type="submit" name="submit" value="Importar" id="btInserirTabela" class="btn btn-primary"/>';
                        }
                        ?>
                    </div>                                        
                </div>
            </div>
            <div class="row">
                * Tabela deve estar no formato .csv, * Data de alteração formato dd/mm/YYYY<br>
                <a href="/arquivos/importacao_tabela_prazo.csv" target="_blank"><img style="width: 30px;" src="../visao/recursos/img/download.png" alt="icone download"/>Download planilha padrão</a>                
                <div class="col-sm-12" id="listagemTabela"></div>
            </div>            
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->