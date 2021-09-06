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
                <form action="<?= $action ?>" id="fnivel" name="fnivel" method="post">
                    <input type="hidden" name="codnivel" id="codnivel" value="<?=$_GET["codnivel"]?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type='text' class="form-control" name="nome" id="nome" placeholder="Digite nome" value="<?php if(isset($nivel["nome"]) && $nivel["nome"] != NULL && $nivel["nome"] != ""){echo $nivel["nome"];}?>">
                        </div>
                                        
                        <div class="form-group">
                            <label for="sexo">Porcentagem</label>
                            <input type='text' class="form-control real" name="porcentagem" id="porcentagem" title="Digite porcentagem" value="<?php if(isset($nivel["porcentagem"]) && $nivel["porcentagem"] != NULL && $nivel["porcentagem"] != ""){echo $nivel["porcentagem"];}?>">
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="statusPessoa">Status</label>
                            <select class="form-control" name="padrao" id="padrao">
                                <option>--Selecione--</option>
                                <option value="s" <?php if(isset($nivel["padrao"]) && $nivel["padrao"] == "s"){echo "selected";}?>>SIM</option>
                                <option value="n" <?php if(isset($nivel["padrao"]) && $nivel["padrao"] == "n"){echo "selected";}?>>NÃO</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php if(!isset($_GET["codnivel"])){?>
                        <input type="button" name="submit" id="submit" value="Salvar" class="btn btn-primary" onclick="inserirNivel();"/>
                        <?php }else{?>
                        <input type="button" name="submit" id="submit" value="Salvar" class="btn btn-primary" onclick="atualizarNivel();"/>
                        <?php }?>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>