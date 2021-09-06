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
                <form id="fbanco" name="fbanco" method="post">
                    <input type="hidden" name="codbanco" id="codbanco" value="<?= $_GET["codbanco"] ?>"/>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">Cód. Banco</label>
                            <input type="text" class="form-control" name="numbanco" id="numbanco" value="<?php if (isset($banco["numbanco"])) {echo $banco["numbanco"];} ?>"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" value="<?php if (isset($banco["nome"])) {echo $banco["nome"];} ?>"/>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">CNPJ</label>
                            <input type="text" class="form-control" name="cnpj" id="cnpj" value="<?php if (isset($banco["cnpj"])) {
    echo $banco["cnpj"];
} ?>"/>
                        </div>
                        </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Site</label>
                            <input type="url" class="form-control" name="site" id="site" value="<?php if (isset($banco["site"])) {
    echo $banco["site"];
} ?>"/>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php
                        if (!isset($banco["codbanco"])) {
                            if($nivelp["inserir"] == 1){
                                echo '<input type="button" name="submit" value="Cadastrar" onclick="inserir()" class="btn btn-primary"/>';
                            }
                        } elseif (isset($banco["codbanco"])) {
                            if($nivelp["atualizar"] == 1){
                                echo '<input style="margin-left: 5px;" type="button" name="submit" value="Atualizar" onclick="atualizar();" class="btn btn-primary"/>';
                            }
                            if($nivelp["excluir"] == 1){
                                echo '<button style="margin-left: 5px;" onclick="excluirBanco()" class="btn btn-primary">Excluir</button>';
                            }
                            echo '<button style="margin-left: 5px;" onclick="btNovoBanco()" class="btn btn-primary">Novo</button>';
                        } 
                        ?>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->