<div class="row">
    <!-- left column -->
    <div class="col-md-6">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="<?= $action ?>" id="fcomunicado" name="fcomunicado" method="post">
                <input type="hidden" name="codcomunicado" id="codcomunicado" value="<?= $_GET["codcomunicado"] ?>"/>
                
                <div class="box-body">

                    <div class="form-group">
                        <label for="localnascimento">Nome</label>
                        <input type='text' class="form-control" name="nome" id="nome" placeholder="Digite nome do comunicado" value="<?php if (isset($comunicado["nome"])) {echo $comunicado["nome"];}?>">
                    </div>

                    <div class="form-group">
                        <label for="exampleInputFile">Arquivo</label>
                        <input type="file" id="arquivo" name="arquivo">
                        <?php
                        if(isset($comunicado["arquivo"]) && $comunicado["arquivo"] != NULL && $comunicado["arquivo"] != ""){
                            echo '<a id="link_imagem" target="_blank" href="../arquivos/',$comunicado["arquivo"],'"><img width="40" src="../visao/recursos/img/download.png" alt="Imagem da comunicado"/> Arquivo de comunicado</a>';
                            
                        }else{
                            echo '<p class="help-block">Escolha arquivo do comunicado</p>';
                        }
                        ?>                        
                    </div>                                        

                </div><!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div><!-- /.box -->
    </div><!--/.col (left) -->
    <!--/.col (right) -->
</div>   <!-- /.row -->