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
                <form name="fhorariofilial" id="fhorariofilial" method="post" onsubmit="return false;">
                    <input type="hidden" name="codfilial" id="codfilial" value="<?php if(isset($_GET["codempresa"])){echo $_GET["codempresa"];}?>"/>
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
                            <input type="file" class="form-control" name="arquivo" id="arquivo" value="" title="Escolha arquivo de importação aqui"/>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            Atualizar cliente se o mesmo já existir<input type="checkbox" name="atualizar_cliente" id="atualizar_cliente" value="s"/>
                            Adicionar na carteira mesmo se o cliente já existir<input type="checkbox" name="adicionar_carteira" id="adicionar_carteira" value="s"/>
                            Atualizar somente telefones!<input type="checkbox" name="somente_telefone" id="somente_telefone" value="s"/>
                        </div>
                        <!-- /.form-group -->
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
                </form>
            </div>

            <div class="row">
* Tabela deve estar no formato .csv<br>
* Data de alteração formato dd/mm/YYYY<br>
<a href="/arquivos/layout_de_importacao_29_06_2015.xls" target="_blank">Download planilha padrão</a>                
                <div class="col-sm-12" id="listagemTabela"></div>
            </div>            
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->