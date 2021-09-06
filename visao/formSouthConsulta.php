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
                <form id="fSouthConsulta" name="fSouthConsulta" method="post" onsubmit="return false;">
                    <input type="hidden" name="codconsulta" id="codconsulta" value="<?=$_GET["codconsulta"]?>"/>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">Empresa</label>
                            <select name="codempresa" id="codempresa" class="form-control">
                                <?php 
                                    $resempresa = $conexao->comando('select codempresa, razao from empresa where 1 = 1');
                                    $qtdempresa = $conexao->qtdResultado($resempresa);
                                    if($qtdempresa > 0){
                                        echo '<option value="">--Selecione--</option>';
                                        while($empresa = $conexao->resultadoArray($resempresa)){
                                            if(isset($southconsultap["codempresa"]) && $southconsultap["codempresa"] != NULL && $southconsultap["codempresa"] == $empresa["codempresa"]){
                                                echo '<option selected value="',$empresa["codempresa"],'">',$empresa["razao"],'</option>';
                                            }else{
                                                echo '<option value="',$empresa["codempresa"],'">',$empresa["razao"],'</option>';
                                            }
                                        }
                                    }else{
                                        echo '<option value="">--Nada encontrado--</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Qtd. Consulta</label>
                            <input type="number" min="0" name="qtdconsulta" id="qtdconsulta" class="form-control" value="<?php if(isset($southconsultap["qtdconsulta"]) && $southconsultap["qtdconsulta"] != NULL && $southconsultap["qtdconsulta"] != ""){echo $southconsultap["qtdconsulta"];}?>"/>                            
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Validade(dias)</label>
                            <input type="integer" name="validade" id="validade" class="form-control" value="<?php if(isset($southconsultap["validade"]) && $southconsultap["validade"] != NULL && $southconsultap["validade"] != ""){echo $southconsultap["validade"];}?>"/>                            
                        </div>
                    </div>
                    
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button class="btn btn-primary" onclick="salvarSouthConsulta()">Salvar</button>
                        <button class="btn btn-primary" onclick="novoSouthConsulta()">Novo</button>
                        <?php if(isset($_GET["codconsulta"]) && $_GET["codconsulta"] != NULL && $_GET["codconsulta"] != ""){?>
                        <button class="btn btn-primary" onclick="excluirSouthConsulta(false)">Excluir</button>
                        <?php }?>
                    </div>                                        
                </div>
            </div>          
        </div>
    </div>
    <!--/.col (right) -->
</div>