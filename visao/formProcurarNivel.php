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
                <form action="<?= $action ?>" id="fProcurarNivel" name="fProcurarNivel" method="post">
                    <input type="hidden" name="codnivel" id="codnivel" value="<?=$_GET["codnivel"]?>"/>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">Nivel</label>
                            <select class="form-control" name="codnivel" id="codnivel">
                                <?php
                                $resnivel = $conexao->comando("select * from nivel where codempresa = '{$_SESSION["codempresa"]}'");
                                $qtdnivel = $conexao->qtdResultado($resnivel);
                                if($qtdnivel > 0){
                                    echo '<option value="">--Selecione--</option>';
                                    while($nivel = $conexao->resultadoArray($resnivel)){
                                        echo '<option value="',$nivel["codnivel"],'">',$nivel["nome"],'</option>';
                                    }
                                }else{
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sexo">Porcentagem</label>
                            <input type='text' class="form-control real" name="porcentagem" id="porcentagem" title="Digite porcentagem" value="<?php if(isset($nivelp["porcentagem"]) && $nivelp["porcentagem"] != NULL && $nivelp["porcentagem"] != ""){echo $nivelp["porcentagem"];}?>">
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="statusPessoa">Status</label>
                            <select class="form-control" name="padrao" id="padrao">
                                <option>--Selecione--</option>
                                <option value="s" <?php if(isset($nivelp["padrao"]) && $nivelp["padrao"] == "s"){echo "selected";}?>>SIM</option>
                                <option value="n" <?php if(isset($nivelp["padrao"]) && $nivelp["padrao"] == "n"){echo "selected";}?>>NÃO</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input class="btn btn-primary" type="button" name="submit" id="btProcurar" value="Procurar" onclick="procurarNivel3(false)"/>
                    </div>                                        
                </div>
            </div>
            
            <div class="row">
                <div id="listagemNivel"></div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>