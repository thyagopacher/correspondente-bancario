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
                <form action="../control/ProcurarTabelaRelatorio.php" target="_blank" id="fptabela" name="fptabela" method="post" onsubmit="return false;">
                    <input type="hidden" name="codnivel" id="codnivel" value="<?= $_GET["codnivel"] ?>"/>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="sexo">Nome</label>
                            <input type="text" class="form-control" name="nome" id="nome" value=""/>
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Cód. Banco</label>
                            <input type="text" class="form-control" name="codbanco1" id="codbanco2" value=""/>
                        </div>
                    </div>
                    <div class="col-md-3"> 
                        <div class="form-group">
                            <label for="sexo">Banco</label>
                            <select name="codbanco" class="form-control" id="codbanco3">
                                <?php
                                $resbanco = $conexao->comando("select * from banco where nome <> '' order by nome");
                                $qtdbanco = $conexao->qtdResultado($resbanco);
                                if ($qtdbanco > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($banco = $conexao->resultadoArray($resbanco)) {
                                        echo '<option numbanco="',$banco["numbanco"],'" value="', $banco["codbanco"], '">', $banco["nome"], '</option>';
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Convênio</label>
                            <select class="form-control" name="codconvenio" id="codconvenio" required>
                                <?php
                                $resBanco = $conexao->comando("select * from convenio where nome <> '' order by nome");
                                $qtdBanco = $conexao->qtdResultado($resBanco);
                                if ($qtdBanco > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($convenio = $conexao->resultadoArray($resBanco)) {
                                        echo '<option value="', $convenio["codconvenio"], '">', $convenio["nome"], '</option>';
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>                            
                        </div>
                    </div>                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Nivel</label>
                            <select name="codnivel" class="form-control" id="codnivel" <?php if ($_SESSION["codnivel"] == 1) {
                                    echo "title='Escolha aqui um nivel para verificar quais tabelas ele tem selecionadas'";
                                } ?>>
                                <?php
                                $resnivel = $conexao->comando("select * from nivel where codnivel <> 1 and (codempresa = '{$_SESSION['codempresa']}' or padrao = 's')");
                                $qtdnivel = $conexao->qtdResultado($resnivel);
                                if ($qtdnivel > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($nivel = $conexao->resultadoArray($resnivel)) {
                                        echo '<option value="', $nivel["codnivel"], '">', $nivel["nome"], '</option>';
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>                    
                            </select>
                        </div>
                    </div>                    
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <button class="btn btn-primary" onclick="procurarTabela(false)">Procurar</button>
                        <button class="btn btn-primary" onclick="abreRelatorioTabela()">Gera PDF</button>
                        <button class="btn btn-primary" onclick="abreRelatorio2Tabela()">Gera Excel</button>
                    </div>                                        
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" id="listagemTabela"></div>
            </div>            
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->