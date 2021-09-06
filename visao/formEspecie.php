<div class="row">
<!-- left column -->
<div class="col-md-6">
    <!-- general form elements -->
    <div class="box box-primary">
        <div class="box-header with-border">
            
        </div><!-- /.box-header -->
        <!-- form start -->
        <form role="form" id="fespecie" name="fespecie" method="post">
            <input type="hidden" name="codespecie" id="codespecie" value="<?= $_GET["codespecie"] ?>"/>
            <div class="box-body">

                <div class="form-group">
                    <label for="localnascimento">Nome</label>
                    <input type='text' class="form-control" name="nome" id="nome" placeholder="Digite nome do especie" value="<?php if(isset($especie["nome"])){echo $especie["nome"];}?>">
                </div>

                <div class="form-group">
                    <label for="exampleInputFile">Num. INSS</label>
                    <input type='text' class="form-control" name="numinss" id="numinss" placeholder="Digite numinss da especie" value="<?php if(isset($especie["numinss"])){echo $especie["numinss"];}?>">
                </div>                                        

            </div><!-- /.box-body -->
            <div class="box-footer">
                <?php if(isset($_GET["codespecie"])){?>
                <button type="button" onclick="atualizarEspecie();" class="btn btn-primary">Salvar</button>
                <?php }else{?>
                <button type="button" onclick="inserirEspecie();" class="btn btn-primary">Salvar</button>
                <?php }?>
            </div>
        </form>
    </div><!-- /.box -->
</div><!--/.col (left) -->
</div><!--/.col (left) -->
<!--/.col (right) -->