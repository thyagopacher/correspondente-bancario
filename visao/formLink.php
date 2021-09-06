<div class="row">

    <!-- left column -->
    <div class="col-md-6">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">

            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="<?= $action ?>" id="flink" name="flink" method="post">
                <input type="hidden" name="codlink" id="codlink" value="<?= $_GET["codlink"] ?>"/>
                <div class="box-body">

                    <div class="form-group">
                        <label for="localnascimento">Nome</label>
                        <input type='text' class="form-control" name="nome" id="nome" placeholder="Digite nome do link" value="<?php if(isset($link["nome"])){echo $link["nome"];}?>">
                    </div>
                    <div class="form-group">
                        <label for="localnascimento">Link</label>
                        <input type='url' class="form-control" name="link" id="link" placeholder="Digite link" value="<?php if(isset($link["link"])){echo $link["link"];}?>">
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