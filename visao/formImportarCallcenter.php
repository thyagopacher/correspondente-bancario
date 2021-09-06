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
                <form id="fimportacao" name="fimportacao" method="post" action="../control/Importacao.php">
                    <input type="hidden" name="layout" id="layout" value="6"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Carteira</label>
                            <input class="form-control" type="text" name="nome" id="nome" list="nomes" maxlength="50" value=""/>
                            <datalist id="nomes"><!--para listar nomes dinamicamente no input de nome-->
                                <?php
                                $resnomes = $conexao->comando("select distinct nome from carteira where codempresa = '{$_SESSION['codempresa']}' order by nome");
                                $qtdnomes = $conexao->qtdResultado($resnomes);
                                if ($qtdnomes > 0) {
                                    while ($nome = $conexao->resultadoArray($resnomes)) {
                                        echo '<option>', $nome["nome"], '</option>';
                                    }
                                }
                                ?>                                    
                            </datalist>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Arquivo</label>
                            <input type="file" class="form-control" name="arquivo" id="arquivo" value="" title="A planilha deve estar em .xls"/>
                            <div class="bar"></div>
                            <div class="percent">0%</div>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="checkbox" name="atualizar_cliente" id="atualizar_cliente" value="s"/><label style="font-size: 12px; color: #666;"> Atualizar cliente se o mesmo já existir</label>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    
                    <div class="col-md-12" style="margin-top: -15px;">
                        <div class="form-group">
                            <input type="checkbox" name="adicionar_carteira" id="adicionar_carteira" value="s"/><label style="font-size: 12px; color: #666;">  Adicionar na carteira mesmo se o cliente já existir</label>
                        </div>
                    </div>
                    
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <?php
                                    if ($nivelp["inserir"] == 1) {
                                        echo '<input type="submit" style="margin-left: 15px;" name="submit" value="Importar" id="btInserirTabela" class="btn btn-primary"/>';
                                    }        
                                ?>
                                <a href="/arquivos/layout_de_importacao_29_06_2015.xls" target="_blank"> 
                                    <img src="../visao/recursos/img/download.png" style="width: 35px; height: 35px; margin-left: 100px;">
                                    <label style="font-size: 12px; color: #666;">Download planilha padrão</label>
                                </a>                                 
                                <a title='formato com ponto e virgula, excel deve estar configurado para .csv em ponto e virgula' href="/arquivos/modelo_viper.csv" target="_blank"> 
                                    <img src="../visao/recursos/img/download.png" style="width: 35px; height: 35px; margin-left: 100px;">
                                    <label style="font-size: 12px; color: #666;">Download planilha Linces</label>
                                </a>                                 
                            </div>   
                        </div> 
                    </div>                    
                </form> 
            </div>

         
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->