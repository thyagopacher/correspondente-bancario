<div class="row">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Procurar acesso</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div> 
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <form id="fpacesso" name="fpacesso" method="post" onsubmit="return false;">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Carteira</label>
                            <input class="form-control" type="text" name="nome" id="nome" list="nomes" maxlength="50" value=""/>
                            <datalist id="nomes"><!--para listar nomes dinamicamente no input de nome-->
                                <?php
                                $resnomes = $conexao->comando("select distinct nome from carteira where codempresa = '{$_SESSION['codempresa']}' order by nome");
                                $qtdnomes = $conexao->qtdResultado($resnomes);
                                if ($qtdnomes > 0) {
                                    while ($nome = $conexao->resultadoArray($resnomes)) {
                                        echo '<option>', $nome["nome"], '</option>';
                                    }
                                }
                                ?>                                    
                            </datalist>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Operador</label>
                            <select class="form-control" name="codoperador" id="codoperador">
                                <?php
                                    $sql = "select pessoa.codpessoa, pessoa.nome 
                                    from pessoa 
                                    inner join nivel on nivel.codnivel = pessoa.codnivel
                                    where pessoa.codempresa = '{$_SESSION['codempresa']}' 
                                    and pessoa.codcategoria not in(1,6,0)   
                                    order by nome";
                                    $resOperador = $conexao->comando($sql);
                                    $qtdOperador = $conexao->qtdResultado($resOperador); 
                                    if ($qtdOperador > 0) {
                                        echo '<option value="">--Selecione--</option>';
                                        while ($operador = $conexao->resultadoArray($resOperador)) {
                                            echo '<option value="', $operador["codpessoa"], '">', $operador["nome"], '</option>';
                                        }
                                    } else {
                                        echo '<option value="">Nada encontrado</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Dt Inicio</label>
                            <input type="date" class="form-control" name="data1" id="data1" value=""/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Dt. Fim</label>
                            <input type="date" class="form-control" name="data2" id="data2" value=""/>
                        </div>
                    </div>                    
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <button class="btn btn-primary" id="btprocurarAcessoOperador" onclick="procurarAcessoOperador(false)">Procurar</button>
                    </div>                                        
                </div>
            </div>
            <div id="listagemAcessoOperador" class="col-md-12"></div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->