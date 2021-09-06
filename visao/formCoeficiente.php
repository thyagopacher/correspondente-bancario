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
                <form id="fcoeficiente" name="fcoeficiente" method="post" onsubmit="return false;">
                    <input type="hidden" name="codcoeficiente" id="codcoeficiente" value="<?= $_GET["codcoeficiente"] ?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Coeficiente</label>
                            <input type="text" class="form-control porcentagemMaisCasas" name="valor" id="valor" title="Digite aqui o coeficiente do dia" value="<?php if(isset($coeficiente["valor"]) && $coeficiente["valor"] != NULL && $coeficiente["valor"] != ""){echo $coeficiente["valor"];}?>"/>
                        </div>
                    </div>                    
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php
                        if(isset($nivelp["inserir"]) && $nivelp["inserir"] == 1 && !isset($_GET["codcoeficiente"])){
                            echo '<button id="btIniciarChat" onclick="inserirCoeficiente();" class="btn btn-primary">Cadastrar</button> ';
                        }
                        if(isset($_GET["codcoeficiente"])){
                            if(isset($nivelp["atualizar"]) && $nivelp["atualizar"] == 1){
                                echo '<button id="btIniciarChat" onclick="atualizarCoeficiente();" class="btn btn-primary">Atualizar</button> ';
                            }
                            if(isset($nivelp["excluir"]) && $nivelp["excluir"] == 1){
                                echo '<button id="btIniciarChat" onclick="excluirCoeficiente();" class="btn btn-primary">Excluir</button> ';
                            }
                        }
                        echo '<button id="btIniciarChat" onclick="novoCoeficiente();" class="btn btn-primary">Novo</button>';
                        ?>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->