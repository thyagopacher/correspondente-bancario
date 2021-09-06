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
                <form action="<?= $action ?>" id="fconta" name="fconta" method="post">
                    <?php if(!isset($_GET["master"])){?>
                    <input type="hidden" name="movimentacao" id="movimentacao"  value="<?php if (isset($conta["movimentacao"])) {echo $conta["movimentacao"];} else { echo strtolower($_GET["movimentacao"]);}?>"/> 
                    <?php }?>                    
                    <input type="hidden" name="codconta" id="codconta" value="<?=$_GET["codconta"]?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type='text' class="form-control" name="nome" id="nome" placeholder="Digite nome" value="<?php if(isset($contap["nome"]) && $contap["nome"] != NULL && $contap["nome"] != ""){echo $contap["nome"];}?>">
                        </div>
                                        
                        <div class="form-group">
                            <label for="sexo">Valor</label>
                            <input type='text' class="form-control real" name="valor" id="valor" value="<?php if(isset($contap["valor"]) && $contap["valor"] != NULL && $contap["valor"] != ""){echo $contap["valor"];}?>">
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Vencimento</label>
                            <input type="date" required name="data" id="data" class="form-control" value="<?php if (isset($contap["data"])) {echo implode("/",array_reverse(explode("-",$contap["data"])));} else {echo date("Y-m-d");}?>"/>                            
                        </div>

                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label>Dt. Pagamento</label>
                            <input type="date" name="dtpagamento" id="dtpagamento" class="form-control" value="<?php if (isset($contap["dtpagamento"]) && $contap["dtpagamento"] != NULL && $contap["dtpagamento2"] != "00/00/0000") {echo implode("/",array_reverse(explode("-",$contap["dtpagamento2"])));}?>"/>                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Arquivo</label>
                            <input class="form-control"  type="file" multiple name="arquivo[]" id="arquivo"/>
                        </div>    
                    </div>
                    <?php if(isset($_GET["codconta"]) && $_GET["codconta"] != NULL && $_GET["codconta"] != ""){?>
                    <div class="col-md-6">
                        <label>Foto Webcam</label>
                        <div class="form-group">
                            <a class="form-control botao" href="javascript: abreTiraFotoConta(<?=$conta["codconta"]?>);">Foto da webcam</a>
                            <?php
                            if(isset($conta["arquivo"]) && $conta["arquivo"] != NULL && $conta["arquivo"] != ""
                                && (strstr($conta["arquivo"], '.png') || strstr($conta["arquivo"], '.jpg') || strstr($conta["arquivo"], '.jpeg'))){
                                echo '<a id="link_imagem" target="_blank" href="../arquivos/',$conta["arquivo"],'"><img width="150" src="../arquivos/',$conta["arquivo"],'" alt="Imagem da conta"/></a>';

                            }
                            ?>                                    
                        </div>                        
                    </div>
                    
                    <?php if(isset($_GET["codconta"])){?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Arquivos anteriores</label>
                                <?php
                                            $sql = "select * from arquivoconta where codconta = '{$_GET["codconta"]}'";
                                            $resarquivo = $conexao->comando($sql);
                                            $qtdarquivo = $conexao->qtdResultado($resarquivo);
                                            if($qtdarquivo > 0){
                                                $linhaArquivo = 1;
                                                echo '<ul>';
                                                while($arquivo = $conexao->resultadoArray($resarquivo)){
                                                    echo '<li style="list-style-type: initial;">';
                                                    echo '<a target="_blank" href="../arquivos/',$arquivo["link"],'" title="Clique aqui para baixar arquivo">Arquivo ',$linhaArquivo,'</a>';
                                                    echo '<a title="Clique aqui para excluir arquivo da conta" href="javascript: excluir2ArquivoConta(',$arquivo["codarquivo"],')"><img width="20" src="/visao/recursos/img/excluir.png" alt="Excluir img da conta"/></a>';
                                                    echo '</li>';
                                                    $linhaArquivo++;
                                                }
                                                echo '</ul>';
                                            }
                                ?>                                     
                        </div>
                    </div>
                    <?php }?>
                    <?php }?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Observação</label>
                            <textarea class="form-control"  name="observacao" id="observacao"><?php if(isset($conta["observacao"])){echo $conta["observacao"];}?></textarea>
                        </div>                       
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                    <?php
                                        if (!isset($conta["codconta"])) {
                                            if ($nivelp["inserir"] == 1) {
                                                echo '<input type="submit" name="submit" value="Cadastrar" class="btn btn-primary"/>';
                                            }
                                        } elseif (isset($conta["codconta"])) {
                                            if ($nivelp["atualizar"] == 1) {
                                                echo '<input type="submit" name="submit" value="Atualizar" class="btn btn-primary"/>';
                                            }
                                            if ($nivelp["excluir"] == 1) {
                                                echo '<button style="margin-left: 10px;" onclick="excluirConta()" class="btn btn-primary">Excluir</button>';
                                            }
                                            echo '<button style="margin-left: 10px;" onclick="btNovoConta()" class="btn btn-primary">Novo</button>';
                                        }
                                    ?>
                            </div>                                        
                        </div>
                    </div>                    
                </form>
            </div>
            <!-- /.row -->

        </div>
    </div>
    <!--/.col (right) -->
</div>