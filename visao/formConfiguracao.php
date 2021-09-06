<?php
    $configuracao = $conexao->comandoArray('select * from configuracao where codempresa = '. $_SESSION["codempresa"]);
    $pessoap      = $conexao->comandoArray('select visualiza_multibr from pessoa where codempresa = '. $_SESSION["codempresa"]. ' and codpessoa = '. $_SESSION["codpessoa"]);
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
                <form id="fconfiguracao" name="fconfiguracao" method="post">
                    <input type="hidden" name="codconfiguracao" id="codconfiguracao" value="<?= $configuracao["codconfiguracao"] ?>"/>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nome">Login SMS</label>
                            <input type="text" class="form-control" name="loginSMS" id="loginSMS" value="<?php if (isset($configuracao["loginSMS"])) {echo $configuracao["loginSMS"];} ?>"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Senha SMS</label>
                            <input type="text" class="form-control" name="senhaSMS" id="senhaSMS" value="<?php if (isset($configuracao["senhaSMS"])) {echo $configuracao["senhaSMS"];} ?>"/>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Msg. Inicio</label>
                            <input type="text" class="form-control" name="msg_inicio" id="msg_inicio" value="<?php if (isset($configuracao["msg_inicio"])) {echo $configuracao["msg_inicio"];} ?>"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Login Viper(WS)</label>
                            <input type="text" class="form-control" name="loginViper" id="loginViper" value="<?php if (isset($configuracao["loginViper"])) {echo $configuracao["loginViper"];} ?>"/>
                        </div>
                    </div>
                    <?php if(isset($pessoap["visualiza_multibr"]) && $pessoap["visualiza_multibr"] != NULL && $pessoap["visualiza_multibr"] == "s"){?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Usuário MultiBR(WS)</label>
                            <input type="text" class="form-control" name="usuarioMultiBR" id="usuarioMultiBR" value="<?php if (isset($configuracao["usuarioMultiBR"])) {echo $configuracao["usuarioMultiBR"];} ?>"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Senha MultiBR(WS)</label>
                            <input type="text" class="form-control" name="senhaMultiBR" id="senhaMultiBR" value="<?php if (isset($configuracao["senhaMultiBR"])) {echo $configuracao["senhaMultiBR"];} ?>"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Key MultiBR(WS)</label>
                            <input type="text" class="form-control" name="keyMultiBR" id="keyMultiBR" value="<?php if (isset($configuracao["keyMultiBR"])) {echo $configuracao["keyMultiBR"];} ?>"/>
                        </div>
                    </div>
                    <?php }?>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tempoocioso">Tempo Ocioso(min)</label>
                            <input type="text" class="form-control" name="tempoocioso" id="tempoocioso" value="<?php if (isset($configuracao["tempoocioso"])) {echo $configuracao["tempoocioso"];} ?>"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="keyanaliseinfo">Key analise info</label>
                            <input type="text" class="form-control" name="keyanaliseinfo" id="keyanaliseinfo" value="<?php if (isset($configuracao["keyanaliseinfo"])) {echo $configuracao["keyanaliseinfo"];} ?>"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Token InfoPesq(WS)</label>
                            <input type="text" class="form-control" name="tokenInfoPesq" id="tokenInfoPesq" value="<?php if (isset($configuracao["tokenInfoPesq"])) {echo $configuracao["tokenInfoPesq"];} ?>"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Consulta de</label>
                            <select class="form-control" name="consultade" id="consultade">
                                <option value="0" <?php if (isset($configuracao["consultade"]) && $configuracao["consultade"] == '0') {echo 'selected';} ?>>VIPER</option>
                                <?php if(isset($pessoap["visualiza_multibr"]) && $pessoap["visualiza_multibr"] != NULL && $pessoap["visualiza_multibr"] == "s"){?>
                                <option value="1" <?php if (isset($configuracao["consultade"]) && $configuracao["consultade"] == '1') {echo 'selected';} ?>>MultiBR</option>
                                <?php }?>
                                <option value="2" <?php if (isset($configuracao["consultade"]) && $configuracao["consultade"] == '2') {echo 'selected';} ?>>Analise Info</option>
                            </select>
                        </div>
                    </div>
 
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <?php
                        if (!isset($configuracao["codconfiguracao"])) {
                            echo '<input type="button" name="submit" value="Salvar" onclick="inserirConfiguracao()" class="btn btn-primary"/>';
                        } elseif (isset($configuracao["codconfiguracao"])) {
                            echo '<input style="margin-left: 5px;" type="button" name="submit" value="Salvar" onclick="atualizarConfiguracao();" class="btn btn-primary"/>';
                        } 
                        ?>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>   <!-- /.row -->