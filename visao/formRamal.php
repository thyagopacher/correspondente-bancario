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
                <form action="<?= $action ?>" id="framal" name="framal" method="post">
                    <input type="hidden" name="codramal" id="codramal" value="<?= $_GET["codramal"] ?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Ramal</label>
                            <input type="text" class="form-control" name="ramal" id="ramal" value="<?php if (isset($ramal["ramal"])) {echo $ramal["ramal"];} ?>"/>
                        </div>

                        <div class="form-group">
                            <label for="sexo">Telefone</label>
                            <input type="text" class="form-control telefone" name="telefone" id="telefone" value="<?php if (isset($ramal["telefone"])) {echo $ramal["telefone"];} ?>"/>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Empresa</label>
                            <select class="form-control" name='codempresa' id="codempresa">
                                <?php
                                $resempresa = $conexao->comando("select * from empresa order by razao");
                                $qtdempresa = $conexao->qtdResultado($resempresa);
                                if($qtdempresa > 0){
                                    echo '<option value="">--Selecione--</option>';
                                    while($empresa2 = $conexao->resultadoArray($resempresa)){
                                        if(isset($ramal["codempresa"]) && $ramal["codempresa"] == $empresa2["codempresa"]){
                                            echo '<option selected value="',$empresa2["codempresa"],'">',$empresa2["razao"],'</option>';
                                        }else{
                                            echo '<option value="',$empresa2["codempresa"],'">',$empresa2["razao"],'</option>';
                                        }
                                    }
                                }else{
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sexo">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" value="<?php if (isset($ramal["nome"])) {echo $ramal["nome"];} ?>"/>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php if(!isset($_GET["codramal"])){?>
                        <input class="btn btn-primary" type="button" name="submit" id="submit" value="Salvar" onclick="inserirRamal();"/>
                        <?php }else{?>
                        <input class="btn btn-primary" type="button" name="submit" id="submit" value="Salvar" onclick="atualizarRamal();"/>
                        <?php }?>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->