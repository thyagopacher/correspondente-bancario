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
                <form action="<?= $action ?>" id="fsmspadrao" name="fsmspadrao" method="post" onsubmit="return false;">
                    <input type="hidden" name="codsmspadrao" id="codsmspadrao" value="<?= $_GET["codsmspadrao"] ?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Texto</label>
                            <input type="text" class="form-control area-row" name="texto" id="texto" value="<?php if (isset($smspadrao["texto"])) {echo $smspadrao["texto"];} ?>"/>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php if(!isset($_GET["codsmspadrao"])){?>
                        <input class="btn btn-primary" type="button" name="submit" id="submit" value="Salvar" onclick="inserirSmsPadrao()"/>
                        <?php }else{?>
                        <input class="btn btn-primary" type="button" name="submit" id="submit" value="Salvar" onclick="atualizarSmsPadrao()"/>
                        <?php }?>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->