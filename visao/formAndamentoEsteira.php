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

            <form onsubmit="return false;" id="fprocurarAndamentoEsteira" name="fprocurarAndamentoEsteira" method="post">
                <div class="row">
                    <input type="hidden" name="codnivel" id="codnivel" value="<?= $_GET["codnivel"] ?>"/>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">CPF</label>
                            <input type="text" class="form-control" name="cpf" id="cpf" value=""/>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Operador</label>
                            <select class="form-control" name="codfuncionario" id="codfuncionario">
                                <?php
                                $resfuncionario = $conexao->comando("select codpessoa, nome from pessoa where senha <> '' and codcategoria not in(1,6) and codempresa = '{$_SESSION['codempresa']}'");
                                $qtdfuncionario = $conexao->qtdResultado($resfuncionario);
                                if ($qtdfuncionario > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($funcionario = $conexao->resultadoArray($resfuncionario)) {
                                        echo '<option value="', $funcionario["codpessoa"], '">', strtoupper($funcionario["nome"]), '</option>';
                                    }
                                } else {
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
                            <label for="sexo">Loja</label>
                            <select class="form-control" name="codloja" id="codloja">
                                <option value="">--Selecione--</option>
                                <option value="<?= $_SESSION["codempresa"] ?>">Emp. logada</option>
                                <?php
                                $resempresa3 = $conexao->comando("select razao, codempresa 
                                from empresa where razao <> ''
                                and codpessoa in(select codpessoa from pessoa where codempresa = {$_SESSION["codempresa"]})
                                order by razao");
                                $qtdempresa3 = $conexao->qtdResultado($resempresa3);
                                if ($qtdempresa3 > 0) {

                                    while ($empresa3 = $conexao->resultadoArray($resempresa3)) {
                                        echo '<option value="', $empresa3["codempresa"], '">', $empresa3["razao"], '</option>';
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">                        
                        <div class="form-group">
                            <label for="sexo">Status</label>
                            <select class="form-control" name="codstatus" id="codstatus">
                                <?php
                                $resstatus2 = $conexao->comando("select nome, codstatus from statusproposta order by nome");
                                $qtdstatus2 = $conexao->qtdResultado($resstatus2);
                                if ($qtdstatus2 > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($status2 = $conexao->resultadoArray($resstatus2)) {
                                        echo '<option value="', $status2["codstatus"], '">', $status2["nome"], '</option>';
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3"> 
                        <div class="form-group">
                            <label for="tipopesquisa">Tipo de Pesquisa</label>
                            <select class="form-control" name="tipopesquisa" id="tipopesquisa">
                                <option value="1">Propostas em Andamento</option>
                                <option value="2">Propostas Pagas</option>
                                <option value="3">Todos Status</option>
                                <option value="4">Canceladas e Recusadas</option>
                            </select>
                        </div>
                    </div>                    
                </div>

            </form>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php if (isset($nivelp["procurar"]) && $nivelp["procurar"] == "1") { ?>
                        <button onclick="AndamentoEsteira()" class="btn btn-primary">Procurar</button>
                    <?php } ?>
                </div>                                        
            </div>
        </div>
        <div id="resultado_andamento_esteira" class="col-md-12"></div>
    </div>
</div>
<!--/.col (right) -->
</div>   <!-- /.row -->