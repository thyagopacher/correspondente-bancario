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
                <form id="fconvenio" name="fconvenio" method="post">
                    <input type="hidden" name="codconvenio" id="codconvenio" value="<?= $_GET["codconvenio"] ?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" value="<?php if(isset($convenio["nome"])){echo $convenio["nome"];}?>"/>
                        </div>
                    </div>                    
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php if(isset($_GET["codconvenio"])){?>
                        <input type="button" name="submit" id="submit" value="Salvar" class="btn btn-primary" onclick="atualizar();"/>
                        <?php }else{?>
                        <input type="button" name="submit" id="submit" value="Salvar" class="btn btn-primary" onclick="inserir();"/>
                        <?php }?>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->