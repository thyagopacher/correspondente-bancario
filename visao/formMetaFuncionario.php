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
                <form action="<?= $action ?>" id="fmeta" name="fmeta" method="post">
                    <input type="hidden" name="codnivel" id="codnivel" value="<?= $_GET["codnivel"] ?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Colaborador</label>
                            <select class="form-control" name="codfuncionario" id="codfuncionario">
                                <?php
                                $sql = "select pessoa.nome, pessoa.codpessoa 
                                from pessoa 
                                inner join nivel on nivel.codnivel = pessoa.codnivel
                                where pessoa.codempresa = {$_SESSION["codempresa"]} and pessoa.codcategoria not in(1,6) order by pessoa.nome";
                                echo "<pre>{$sql}</pre>";
                                $respessoa = $conexao->comando($sql);
                                $qtdpessoa = $conexao->qtdResultado($respessoa);
                                if($qtdpessoa > 0){
                                    echo '<option value="">--Selecione--</option>';
                                    while($pessoa = $conexao->resultadoArray($respessoa)){
                                        if (isset($meta["codfuncionario"]) && $meta["codfuncionario"] == $pessoa["codpessoa"]) {
                                            echo '<option selected value="', $pessoa["codpessoa"], '">', $pessoa["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $pessoa["codpessoa"], '">', $pessoa["nome"], '</option>';
                                        }
                                    }
                                }else{
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sexo">Meta</label>
                            <input type="text" class="form-control real" name="valor" id="valorMeta" value="<?php if (isset($meta["valor"])) {echo $meta["valor"];} ?>"/>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Dt Inicio</label>
                            <input type="date" class="form-control" name="dtinicio" id="dtinicio" value="<?php if (isset($meta["dtinicio"])) {echo $meta["dtinicio"];} else {echo date('Y-m-d');} ?>"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Dt. Fim</label>
                            <input type="date" class="form-control" name="dtfim" id="dtfim" value="<?php if (isset($meta["dtfim"])) {echo $meta["dtfim"];} else {echo date('Y-m-d');} ?>"/>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php
                        if ($nivelp["inserir"] == 1) {
                            echo '<input type="button" name="submit" id="btinserirMetaFuncionario" value="Cadastrar" onclick="inserirMetaFuncionario()" class="btn btn-primary"/>';
                        }
                        if ($nivelp["atualizar"] == 1) {
                            echo '<input style="margin-left: 5px; display: none" type="button" name="submit" value="Atualizar" id="btatualizarMetaFuncionario" onclick="atualizarMetaFuncionario()" class="btn btn-primary"/>';
                        }
                        if ($nivelp["excluir"] == 1) {
                            echo '<button style="margin-left: 5px; display: none" id="btexcluirMetaFuncionario" onclick="excluirMetaFuncionario()" class="btn btn-primary">Excluir</button>';
                        }
                        echo '<button style="margin-left: 5px; display: none" id="btnovoMetaFuncionario" onclick="btNovoMetaFuncionario()" class="btn btn-primary">Novo</button>';
                        ?>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->