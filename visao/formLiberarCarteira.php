<div class="row">
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Liberação de carteira</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
            </div> 
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <form id="facesso" name="facesso" method="post" action="../control/Importacao.php">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Carteira</label>
                            <select class="form-control" name="codcarteira" id="codcarteira">
                                <?php
                                $sql = "select * from carteira where codempresa = '{$_SESSION['codempresa']}' and nome <> ''";
                                $resCarteira = $conexao->comando($sql);
                                $qtdCarteira = $conexao->qtdResultado($resCarteira);
                                if($qtdCarteira > 0){
                                    echo '<option value="">--Selecione--</option>';
                                    while($carteira = $conexao->resultadoArray($resCarteira)){
                                        echo '<option value="',$carteira["codcarteira"],'">',$carteira["nome"],'</option>';
                                    }
                                }else{
                                    echo '<option value="">Nada encontrado</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Operador</label>
                            <select class="form-control" name="codoperador" id="codoperador">
                                <?php
                                    $sql = "select pessoa.codpessoa, pessoa.nome 
                                    from pessoa 
                                    inner join nivel on nivel.codnivel = pessoa.codnivel
                                    where pessoa.codempresa = {$_SESSION['codempresa']} 
                                    and   pessoa.status = 'a'    
                                    order by pessoa.nome";
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
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php 
                        echo '<button style="margin-left: 0px" id="btinserirAcessoOperador" class="btn btn-primary" onclick="inserirAcessoOperador()">Cadastrar</button>';
                        echo '<button style="margin-left: 10px; display: none" id="btatualizarAcessoOperador" class="btn btn-primary" onclick="atualizarAcessoOperador()">Atualizar</button>';
                        echo '<button style="margin-left: 10px; display: none" id="btexcluirAcessoOperador" class="btn btn-primary" onclick="excluirAcessoOperador()">Excluir</button>';
                        echo '<button style="margin-left: 10px; display: none" id="btnovoAcessoOperador" class="btn btn-primary" onclick="btNovoAcessoOperador()">Novo</button>';
                        ?>
                    </div>                                        
                </div>
            </div>            
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->