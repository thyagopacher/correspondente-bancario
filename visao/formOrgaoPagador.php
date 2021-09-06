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
                <form id="forgao" name="forgao" method="post">
                    <input type="hidden" name="codorgao" id="codorgao" value="<?= $_GET["codorgao"] ?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" value="<?php if(isset($orgao["nome"])){echo $orgao["nome"];}?>"/>
                        </div>
                    </div>                    
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php if(!isset($_GET["codorgao"])){?>
                        <input type="button" name="submit" id="submit" value="Salvar" class="btn btn-primary" onclick="inserir();"/>
                        <?php }else{?>
                        <input type="button" name="submit" id="submit" value="Atualizar" class="btn btn-primary" onclick="atualizar();"/>
                        <input type="button" name="submit" id="submit" value="Excluir" class="btn btn-primary" onclick="excluirOrgaoPagador();"/>
                        <?php }?>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->