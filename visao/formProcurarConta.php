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
                <form action="<?= $action ?>" id="fpconta" name="fpconta" method="post">
                    <input type="hidden" name="codconta" id="codconta" value="<?=$_GET["codconta"]?>"/>
                    <input type="hidden" name="tipo" id="tipo" value="pdf"/>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type='text' class="form-control" name="nome" id="nome" placeholder="Digite nome" value="<?php if(isset($contapp["nome"]) && $contapp["nome"] != NULL && $contapp["nome"] != ""){echo $contapp["nome"];}?>">
                        </div>
                                        
                        <div class="form-group">
                            <label for="sexo">Valor</label>
                            <input type='text' class="form-control real" name="valor" id="valor" value="<?php if(isset($contapp["valor"]) && $contapp["valor"] != NULL && $contapp["valor"] != ""){echo $contapp["valor"];}?>">
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">Tipo</label>
                            <select class='form-control' name="codtipo" id="codtipo" required title="Escolha aqui o tipo de sua conta">
                                <?php
                                $sql = "select * from tipoconta where codempresa = '{$_SESSION['codempresa']}' order by nome";
                                $restipo = $conexao->comando($sql);
                                $qtdtipo = $conexao->qtdResultado($restipo);
                                if ($qtdtipo > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($tipo = $conexao->resultadoArray($restipo)) {
                                        if (isset($contap["codtipo"]) && $contap["codtipo"] == $tipo["codtipo"]) {
                                            echo '<option selected value="', $tipo["codtipo"], '">', $tipo["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $tipo["codtipo"], '">', $tipo["nome"], '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="">Nada encontrado</option>';
                                }
                                ?>
                            </select>                            
                        </div>
                        <div class="form-group">
                            <label for="nome">Status</label>
                            <select class='form-control' name="codstatus" id="codstatus" required title="Escolha aqui o status de sua conta">
                                <?php
                                $sql = "select * from statusconta order by nome";
                                $resstatus = $conexao->comando($sql);
                                $qtdstatus = $conexao->qtdResultado($resstatus);
                                if ($qtdstatus > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($status = $conexao->resultadoArray($resstatus)) {
                                        if (isset($contap["codstatus"]) && $contap["codstatus"] == $status["codstatus"]) {
                                            echo '<option selected value="', $status["codstatus"], '">', $status["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $status["codstatus"], '">', $status["nome"], '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="">Nada encontrado</option>';
                                }
                                ?>
                            </select>                            
                        </div>

                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Vencimento</label>
                            <input type="date" required name="data" id="data" class="form-control" value="<?php if (isset($contap["data"])) {echo implode("/",array_reverse(explode("-",$contap["data"])));} else {echo date("Y-m-d");}?>"/>                            
                        </div>

                    </div>
                    <div class="col-md-3">

                        <div class="form-group">
                            <label>Dt. Pagamento</label>
                            <input type="date" name="dtpagamento" id="dtpagamento" class="form-control" value="<?php if (isset($contap["dtpagamento"]) && $contap["dtpagamento"] != NULL && $contap["dtpagamento2"] != "00/00/0000") {echo implode("/",array_reverse(explode("-",$contap["dtpagamento2"])));}?>"/>                            
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Observação</label>
                            <textarea class="form-control"  name="observacao" id="observacao"><?php if(isset($conta["observacao"])){echo $conta["observacao"];}?></textarea>
                        </div>                       
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
    <button class="btn btn-primary" onclick="procurarConta(false)">Procurar</button>
    <?php
    if(isset($nivelp["gerapdf"]) && $nivelp["gerapdf"] == 1){
        echo '<button class="btn btn-primary" onclick="abreRelatorioConta()">Gera PDF</button> ';
    }
    if(isset($nivelp["geraexcel"]) && $nivelp["geraexcel"] == 1){
        echo '<button class="btn btn-primary" onclick="abreRelatorio2Conta()">Gera Excel</button>';
    }
    ?>
                    </div>                                        
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" id="listagem"></div>
            </div>            
        </div>
    </div>
    <!--/.col (right) -->
</div>