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
                <form action="<?= $action ?>" id="fPbaixa" name="fPbaixa" method="post">
                    <input type="hidden" name="codpessoa" id="codpessoa" value="<?=$_GET["codpessoa"]?>"/>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">CPF</label>
                            <input type='text' class="form-control" name="cpf" id="cpf" placeholder="Digite cpf" value="<?php if(isset($pessoap["cpf"]) && $pessoap["cpf"] != NULL && $pessoap["cpf"] != ""){echo $pessoap["cpf"];}?>">
                        </div>
                                        
                        <div class="form-group">
                            <label for="sexo">Colaborador</label>
                            <select name="codfuncionario" id="codfuncionario" class="form-control" >
                                <?php
                                $resFuncionario = $conexao->comando("select codpessoa, nome from pessoa where codcategoria not in(1,6)");
                                $qtdFuncionario = $conexao->qtdResultado($resFuncionario);
                                if($qtdFuncionario > 0){
                                    echo '<option value="">--Selecione--</option>';
                                    while($funcionario = $conexao->resultadoArray()){
                                        echo '<option value="',$funcionario["codpessoa"],'">',$funcionario["nome"],'</option>';
                                    }
                                }else{
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="statusPessoa">Data Inicio</label>
                            <input type="date" class="form-control" name="data1" id="data1" value="<?=date("Y-m-01")?>">
                        </div>
                        <div class="form-group">
                            <label for="statusPessoa">Data Fim</label>
                            <input type="date" class="form-control" name="data2" id="data2" value="<?=date("Y-m-d")?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="statusPessoa">Agrupar Colaborador</label>
                            <select name="agrupar_colaborador" class="form-control" id="agrupar_colaborador">
                                <option value="n">Não</option>
                                <option value="s">Sim</option>
                            </select>
                        </div>
                    </div>
                    <!-- /.col -->
                    
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <button class="btn btn-primary" onclick="procurarBaixa2(false)">Procurar</button>
                        <button class="btn btn-primary" onclick="abreRelatorioBaixa()">Gera PDF</button>
                        <button class="btn btn-primary" onclick="abreRelatorio2Baixa()">Gera Excel</button>
                    </div>                                        
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" id="listagemBaixa"></div>
            </div>            
        </div>
    </div>
    <!--/.col (right) -->
</div>