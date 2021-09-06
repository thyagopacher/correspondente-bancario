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
                <form action="<?= $action ?>" id="fbaixa" name="fbaixa" method="post">
                    <input type="hidden" name="codpessoa" id="codpessoa" value="<?=$_GET["codpessoa"]?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">CPF</label>
                            <input type='text' class="form-control" name="cpf" id="cpf" placeholder="Digite cpf" value="<?php if(isset($pessoap["cpf"]) && $pessoap["cpf"] != NULL && $pessoap["cpf"] != ""){echo $pessoap["cpf"];}?>">
                        </div>
                                        
                        <div class="form-group">
                            <label for="sexo">Valor</label>
                            <input type='text' class="form-control real" name="valor" id="valor" value="<?php if(isset($pessoap["valor"]) && $pessoap["valor"] != NULL && $pessoap["valor"] != ""){echo $pessoap["valor"];}?>">
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="statusPessoa">Data</label>
                            <input type="date" class="form-control" name="dtcadastro" id="dtcadastro" value="<?=date("Y-m-d")?>">
                        </div>
                    </div>
                    <!-- /.col -->
                    
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                            <?php
                    if($nivelp["inserir"] == 1){
                        echo '<input type="button" name="submit" value="Cadastrar" id="btinserirBaixa" onclick="inserirBaixa();" class="btn btn-primary"/>';
                    }            
                    if($nivelp["atualizar"] == 1){
                        echo '<input style="margin-left: 5px; display: none" type="button" name="submit" id="btatualizarBaixa" value="Atualizar" onclick="atualizarBaixa();" class="btn btn-primary"/>';
                    }
                    if($nivelp["excluir"] == 1){
                        echo '<button style="margin-left: 5px; display: none" onclick="excluirBaixa()" id="btexcluirBaixa" class="btn btn-primary">Excluir</button>';
                    }
                    echo '<button style="margin-left: 5px; display: none" onclick="btNovoBaixa()" id="btnovoBaixa" class="btn btn-primary">Novo</button>';
                            ?>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>