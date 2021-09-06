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
                <form action="<?= $action ?>" id="fdia" name="fdia" method="post">
                    <input type="hidden" name="coddia" id="coddia" value="<?= $_GET["coddia"] ?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Data</label>
                            <input type="date" class="form-control" name="data" id="data" value="<?php if (isset($dia["data"])) {echo $dia["data"];} ?>"/>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
        <?php
        if (!isset($dia["coddia"])) {
            if($nivelp["inserir"] == 1){
                echo '<input type="submit" name="button" id="Cadastrar" value="Cadastrar" class="btn btn-primary" onclick="inserirDia()"/>';
            }
        } elseif (isset($dia["coddia"])) {
            if($nivelp["atualizar"] == 1){
                echo '<input style="margin-left: 5px;" type="submit" name="submit" value="Atualizar" class="btn btn-primary" onclick="atualizarDia()"/>';
            }
            if($nivelp["excluir"] == 1){
                echo '<button style="margin-left: 5px;" class="btn btn-primary" onclick="excluirDia()">Excluir</button>';
            }
            echo '<button style="margin-left: 5px;" class="btn btn-primary" onclick="btNovoDia()">Novo</button>';
        } 
        ?>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->