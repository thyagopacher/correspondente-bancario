<div class="row">
    <!-- left column -->
    <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                
            </div><!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="<?= $action ?>" id="fmanual" name="fmanual" method="post">
                <input type="hidden" name="codmanual" id="codmanual" value="<?= $_GET["codmanual"] ?>"/>
                <div class="box-body">
                    <div class="form-group col-md-3">
                        <label for="nome">Código Banco</label>
                        <input type='text' class="form-control" name="codbanco1" id="codbanco1" value="<?php if(isset($manual["numbanco"])){echo $manual["numbanco"];}?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="statusPessoa">Banco</label>
                        <select class="form-control" name="codbanco" id="codbanco" title="Escolha aqui o banco para o manual">
                            <?php
                            $resbanco = $conexao->comando("select * from banco where nome <> '' order by nome");
                            $qtdbanco = $conexao->qtdResultado($resbanco);
                            if ($qtdbanco > 0) {
                                echo '<option value="">--Selecione--</option>';
                                while ($banco = $conexao->resultadoArray($resbanco)) {
                                    if(isset($manual["codbanco"]) && $manual["codbanco"] != NULL && $manual["codbanco"] == $banco["codbanco"]){
                                        echo '<option selected value="', $banco["codbanco"], '">', $banco["nome"], '</option>';
                                    }else{
                                        echo '<option value="', $banco["codbanco"], '">', $banco["nome"], '</option>';
                                    }
                                }
                            } else {
                                echo '<option value="">--Nada encontrado--</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="localnascimento">Nome</label>
                        <input type='text' class="form-control" name="nome" id="nome" placeholder="Digite nome do manual" value="<?php if(isset($manual["nome"])){echo $manual["nome"];}?>">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="exampleInputFile">Arquivo</label>
                        <input type="file" id="arquivo" name="arquivo" value="http://southnegocios.com/arquivos/<?=$manual["arquivo"]?>">
                        <p class="help-block">Escolha arquivo do manual</p>
                        <?php
                        if(isset($manual["arquivo"]) && $manual["arquivo"] != NULL && $manual["arquivo"] != ""){
                            echo '<a target="_blank" href="../arquivos/',$manual["arquivo"],'">Download</a>';
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