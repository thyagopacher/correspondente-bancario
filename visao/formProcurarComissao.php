<?php

    $arrayTipo = array();
    if(isset($situacaoComissao) && $situacaoComissao != NULL && $situacaoComissao == "receber"){
        $arrayTipo[0] = "Todas";
        $arrayTipo[1] = "Recebida";
        $arrayTipo[2] = "À Receber";
    }else{
        $arrayTipo[0] = "Todas";
        $arrayTipo[1] = "Paga";
        $arrayTipo[2] = "À Pagar";
    }
?>
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
                <form target="_blank" id="fpcomissao" name="fpcomissao" method="post" onsubmit="return false;">
                    <input type="hidden" name="situacaoComissao" id="situacaoComissao" value="<?php if(isset($situacaoComissao) && $situacaoComissao != NULL && $situacaoComissao != ""){echo $situacaoComissao;}?>"/>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Comissão</label>
                            <select class="form-control" name="tipo_comissao" id="tipo_comissao">
                                <?php
                                foreach ($arrayTipo as $key => $value) {
                                    $id = $key + 1;
                                    echo '<option value="',$id,'">',$arrayTipo[$key],'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>                                                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Inicio Contrato</label>
                            <input type="date" class="form-control" name="data_contrato1" id="data_contrato1" value=""/>
                        </div>
                    </div>                                                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Fim Contrato</label>
                            <input type="date" class="form-control" name="data_contrato2" id="data_contrato2" value=""/>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Inicio Recebimento</label>
                            <input type="date" class="form-control" name="data_recebimento1" id="data_recebimento1" value=""/>
                        </div>
                    </div>                                                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Fim Recebimento</label>
                            <input type="date" class="form-control" name="data_recebimento2" id="data_recebimento2" value=""/>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Banco</label>
                            <select class="form-control" name="codbanco" id="codbanco">
                                <?php
                                $resbanco = $conexao->comando('select codbanco, nome from banco where nome <> "" order by nome');
                                $qtdbanco = $conexao->qtdResultado($resbanco);
                                if ($qtdbanco > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($banco = $conexao->resultadoArray($resbanco)) {
                                        echo '<option value="', $banco["codpessoa"], '">', $banco["nome"], '</option>';
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
                            <label for="sexo">Tabela</label>
                            <select class="form-control" name="tabela" id="tabela">
                                <?php
                                $restabela = $conexao->comando('select codtabela, nome 
                                from tabela where codempresa = ' . $_SESSION["codempresa"] . ' 
                                group by nome    
                                order by nome');
                                $qtdtabela = $conexao->qtdResultado($restabela);
                                if ($qtdtabela > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($tabela = $conexao->resultadoArray($restabela)) {
                                        echo '<option value="', $tabela["nome"], '">', $tabela["nome"], '</option>';
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
                            <label for="sexo">Cliente</label>
                            <input type="text" class="form-control" name="cpf" id="cpf" placeholder="CPF do cliente" value=""/>
                        </div>
                    </div>  
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Operador</label>
                            <select class="form-control" name="codfuncionario" id="codfuncionario">
                                <?php
                                $resfuncionario = $conexao->comando('select codpessoa, nome from pessoa where codcategoria not in(1,6) and codempresa = ' . $_SESSION["codempresa"] . ' order by nome');
                                $qtdfuncionario = $conexao->qtdResultado($resfuncionario);
                                if ($qtdfuncionario > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($funcionario = $conexao->resultadoArray($resfuncionario)) {
                                        echo '<option value="', $funcionario["codpessoa"], '">', $funcionario["nome"], '</option>';
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>                                                                
                            </select>
                        </div>
                    </div>
                    <?php
                        $sql = 'select codempresa, razao 
                        from empresa where (codempresa = '.$_SESSION["codempresa"].'
                        or codpessoa in(select codpessoa from pessoa where codempresa = '.$_SESSION["codempresa"].'))';
                        $resempresa = $conexao->comando($sql);
                        $qtdempresa = $conexao->qtdResultado($resempresa);
                        if($qtdempresa > 0){
                            echo '<div class="col-md-3">';
                            echo '<div class="form-group">';
                            echo '<label for="sexo">Filial</label>';
                            echo '<select class="form-control" name="codfilial" id="codfilial">';
                            echo '<option value="">--Selecione--</option>';
                            while($empresa = $conexao->resultadoArray($resempresa)){
                                echo '<option value="',$empresa["codempresa"],'">',$empresa["razao"],'</option>';
                            }
                            echo '</select>';
                            echo '</div>';
                            echo '</div>';
                        }
                    ?>
                </form>
            </div> 
            <!-- /.row --> 
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <button class="btn btn-primary" onclick="procurarComissao(false, '<?=$situacaoComissao?>')">Procurar</button>
                    </div>                                        
                </div>
                <div class="col-md-6">
                    <div id="resultado_salvo" class="form-group">
                    </div>                                        
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12" <?php if(isset($situacaoComissao) && $situacaoComissao != NULL && $situacaoComissao == "pagar"){echo 'id="listagemComissao2"';}else{echo 'id="listagemComissao"';}?>></div>
            </div>            
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->
