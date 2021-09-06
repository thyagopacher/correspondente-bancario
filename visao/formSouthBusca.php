<div class="row">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Pessoa Fisica</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <form id="fconsultaFisica" name="fconsultaFisica" method="post">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="check_consulta[]" id="check_cpf" value="1">
                                CPF
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="check_consulta[]" id="check_nome" value="2">
                                Nome
                            </label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="check_consulta[]" id="check_email" value="6">
                                Num Beneficio
                            </label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-inline">
                            <?php
                                if($limiteConsulta <= 0){
                                    $onclick="onclick='acabouCreditos()'";
                                }else{
                                    $onclick="onclick='procurarFisica(false);'";
                                }
                            ?>
                            <input type="text" size="100" maxlength="100" placeholder="digite a busca aqui" class="form-control" name="valor_procurar" id="valor_procurar" value=""/>
                            <input type="button" name="submit" value="Consultar" class="btn btn-primary" <?=$onclick?>/>
                        </div>
                    </div>                    
                </form>
                
            </div>
        </div>
    </div>
</div>  
<div class="row">
    <div class="col-md-12" id="listagemFisica"></div>
</div>