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
                <form id="fplano" name="fplano" method="post">
                    <input type="hidden" name="codplano" id="codplano" value="<?= $_GET["codplano"] ?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" value="<?php if (isset($plano["nome"])) {echo $plano["nome"];} ?>"/>
                        </div>
                        <div class="form-group">
                            <label for="nome">Qtd. Filial</label>
                            <input type="text" class="form-control inteiro" name="qtdfilial" id="qtdfilial" value="<?php if (isset($plano["qtdfilial"])) {echo $plano["qtdfilial"];} ?>"/>
                        </div>                        
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Qtd. Usuário Correspondente</label>
                            <input type="text" class="form-control inteiro" name="qtdusuariomatriz" id="qtdusuariomatriz" value="<?php if (isset($plano["qtdusuariomatriz"])) {echo $plano["qtdusuariomatriz"];} ?>"/>
                        </div>
                        <div class="form-group">
                            <label for="sexo">Qtd. Usuário Filial</label>
                            <input type="url" class="form-control inteiro" name="qtdusuariofilial" id="qtdusuariofilial" value="<?php if (isset($plano["qtdusuariofilial"])) {echo $plano["qtdusuariofilial"];} ?>"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Vl. Mensalidade</label>
                            <input type="text" class="form-control real" name="vlmensalidade" id="vlmensalidade" value="<?php if (isset($plano["vlmensalidade"])) {echo $plano["vlmensalidade"];} ?>"/>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php if(!isset($_GET["codplano"])){?>
                        <input class="btn btn-primary" type="button" name="submit" id="submit" value="Salvar" onclick="inserir()"/>
                        <?php }else{?>
                        <input class="btn btn-primary" type="button" name="submit" id="submit" value="Atualizar" onclick="atualizar()"/>
                        <input class="btn btn-primary" type="button" name="submit" id="submit" value="Excluir" onclick="excluirPlano()"/>
                        <?php }?>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->