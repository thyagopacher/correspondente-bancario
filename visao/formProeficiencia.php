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
                <form id="fproeficiencia" name="fproeficiencia" method="post">
                    <input type="hidden" name="codproeficiencia" id="codproeficiencia" value="<?= $_GET["codproeficiencia"] ?>"/>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="nome">Perfil de Pagamento</label>
                            <input type="text" class="form-control" name="perfil" id="perfil" value="<?php if (isset($proeficiencia["perfil"])) {echo $proeficiencia["perfil"];} ?>"/>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div id="tabela_proeficiencia" style="margin-bottom: 10px;">
                            <?php
                            $resproeficiencia = $conexao->comando("select * from proeficiencia where perfil = '{$proeficiencia["perfil"]}'");
                            $qtdproeficiencia = $conexao->qtdResultado($resproeficiencia);
                            if($qtdproeficiencia == 0){
                            ?>
                            <table class="tabela_formulario">
                                <tr>
                                    <td>Meta Base<br><input type="text" name="valor[]" id="valor0" class="real" placeholder="Digite valor" value="<?php if (isset($proeficiencia["valor"])) {echo number_format($proeficiencia["valor"], 2, ",", "");} ?>"></td>             
                                    <td>Indice Referência<br><input type="text" name="margem[]" id="margem0" class="inteiro" placeholder="Digite referência" value="<?php if (isset($proeficiencia["margem"])) {echo number_format($proeficiencia["margem"], 2, ",", "");} ?>"></td>  
                                    <td>Bonificação<br><input type="text" name="remuneracao[]" id="remuneracao0" class="real" placeholder="Digite remuneração" value="<?php if (isset($proeficiencia["remuneracao"])) {echo number_format($proeficiencia["remuneracao"], 2, ",", "");} ?>"></td>  
                                    <td>Dt. Vigência Ini<br><input type="date" name="dtvigenciaIni[]" id="dtvigenciaIni0" value="<?php if (isset($proeficiencia["dtvigenciaIni"])) {echo $proeficiencia["dtvigenciaIni"];} ?>"/></td>            
                                    <td>Dt. Vigência<br><input type="date" name="dtvigencia[]" id="dtvigencia0" value="<?php if (isset($proeficiencia["dtvigencia"])) {echo $proeficiencia["dtvigencia2"];} ?>"/></td>              
                                    <td><a href="javascript: adicionarLinhaProeficiencia(0)" class="botao">+</a></td> 
                                    <td><a href="javascript: removerLinhaProeficiencia(0)" class="botao">-</a></td> 
                                </tr>
                            </table>
                            <?php 
                            }elseif($qtdproeficiencia > 0){
                                $linha = 0;
                                while($proeficiencia = $conexao->resultadoArray($resproeficiencia)){
                    ?>
                            <table class="tabela_formulario">
                                <tr>
                                    <td>Meta Base<br><input type="text" name="valor[]" id="valor<?=$linha?>" class="real" placeholder="Digite valor" value="<?php if (isset($proeficiencia["valor"])) {echo number_format($proeficiencia["valor"], 2, ",", "");} ?>"></td>             
                                    <td>Indice Referência<br><input type="text" name="margem[]" id="margem<?=$linha?>" class="inteiro" placeholder="Digite referência" value="<?php if (isset($proeficiencia["margem"])) {echo number_format($proeficiencia["margem"], 2, ",", "");} ?>"></td>  
                                    <td>Bonificação<br><input type="text" name="remuneracao[]" id="remuneracao<?=$linha?>" class="real" placeholder="Digite remuneração" value="<?php if (isset($proeficiencia["remuneracao"])) {echo number_format($proeficiencia["remuneracao"], 2, ",", "");} ?>"></td>  
                                    <td>Dt. Vigência Ini<br><input type="date" name="dtvigenciaIni[]" id="dtvigenciaIni<?=$linha?>" value="<?php if (isset($proeficiencia["dtvigenciaIni"])) {echo $proeficiencia["dtvigenciaIni"];} ?>"/></td>            
                                    <td>Dt. Vigência<br><input type="date" name="dtvigencia[]" id="dtvigencia<?=$linha?>" value="<?php if (isset($proeficiencia["dtvigencia"])) {echo $proeficiencia["dtvigencia"];} ?>"/></td>              
                                    <td><a href="javascript: adicionarLinhaProeficiencia(<?=$linha?>)" class="botao">+</a></td> 
                                    <td><a href="javascript: removerLinhaProeficiencia(<?=$linha?>)" class="botao">-</a></td> 
                                </tr>
                            </table>        
                    <?php        
                                $linha++;
                                }
                            }
                            ?>
                        </div>                        
                    </div>
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php
                        if (!isset($_GET["perfil"])) {
                            if ($nivelp["inserir"] == 1) {
                                echo '<input class="btn btn-primary" type="button" name="submit" value="Cadastrar" id="btinserirProeficiencia" onclick="inserirProeficiencia();"/>';
                            }
                        } else {
                            if ($nivelp["atualizar"] == 1) {
                                echo '<input class="btn btn-primary" style="margin-left: 5px;" type="button" name="submit" id="btatualizarProeficiencia" value="Atualizar" onclick="atualizarProeficiencia();"/>';
                            }
                            if ($nivelp["excluir"] == 1) {
                                echo '<button class="btn btn-primary" style="margin-left: 5px;" onclick="excluirProeficiencia()" id="btexcluirProeficiencia">Excluir</button>';
                            }
                            echo '<button class="btn btn-primary" style="margin-left: 5px; display: none" onclick="btNovoProeficiencia()" id="btnovoProeficiencia">Novo</button>';
                        }
                        ?>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->