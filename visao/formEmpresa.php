<div class="row">

    <!-- general form elements -->
    <div class="box box-primary">
        <div class="box-header with-border">

        </div><!-- /.box-header -->
        <!-- form start -->
        <form role="form" id="fempresa" action="<?= $action ?>" autocomplete="off" method="post" enctype="multipart/form-data">
            <input type="hidden" name="codramo" id="codramo" value="<?= $_GET["codramo"] ?>"/>
            <input type="hidden" name="codempresa" id="codempresa" value="<?= $_GET["codempresa"] ?>"/>
            <input type="hidden" name="tipo" id="tipo" value="<?= $_GET["tipo"] ?>"/>
            <div class="box-body">
                <?php
                if (isset($_GET["tipo"]) && isset($_GET["codramo"])) {//correspondente
                    ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Plano</label>
                            <select class="form-control" name="codplano" id="codplano" title="Escolha aqui o plano">
                                <?php
                                $resplano = $conexao->comando("select * from plano order by nome");
                                $qtdplano = $conexao->qtdResultado($resplano);
                                if ($qtdplano > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($plano = $conexao->resultadoArray($resplano)) {
                                        if (isset($empresaf["codplano"]) && $empresaf["codplano"] == $plano["codplano"]) {
                                            echo '<option selected value="', $plano["codplano"], '">', $plano["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $plano["codplano"], '">', $plano["nome"], '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>
                        </div>  
                    </div>                    
                    <?php
                }
                ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nome">Razão</label>
                        <input type='text' class="form-control" name="razao" id="razao" placeholder="Digite razão social"  value='<?php if (isset($empresaf["razao"])) {
                    echo $empresaf["razao"];
                } ?>'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nome">Fantasia</label>
                        <input type='text' class="form-control" name="fantasia" id="fantasia" placeholder="Digite nome fantasia"  value='<?php if (isset($empresaf["fantasia"])) {
                    echo $empresaf["fantasia"];
                } ?>'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="statusPessoa">Status</label>
                        <select class="form-control" name="codstatus" id="codstatus" title="Escolha aqui se a filial está ativa ou não">
                            <?php
                            $resstatus = $conexao->comando("select * from statusempresa order by nome");
                            $qtdstatus = $conexao->qtdResultado($resstatus);
                            if ($qtdstatus > 0) {
                                echo '<option value="">--Selecione--</option>';
                                while ($status = $conexao->resultadoArray($resstatus)) {
                                    if (isset($empresaf["codstatus"]) && $empresaf["codstatus"] == $status["codstatus"]) {
                                        echo '<option selected value="', $status["codstatus"], '">', $status["nome"], '</option>';
                                    } else {
                                        echo '<option value="', $status["codstatus"], '">', $status["nome"], '</option>';
                                    }
                                }
                            } else {
                                echo '<option value="">--Nada encontrado--</option>';
                            }
                            ?>
                        </select>
                    </div>  
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="localnascimento">CEP</label>
                        <input type='text' class="form-control" name="cep" id="cep" maxlength="8" placeholder="Digite cep ele busca endereço" value='<?php if (isset($empresaf["cep"])) {
                                echo $empresaf["cep"];
                            } ?>'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="localnascimento">Tip. Logradouro</label>
                        <input type='text' class="form-control" name="tipologradouro" id="tipologradouro" maxlength="8" placeholder="Digite tipo logradouro" value='<?php if (isset($empresaf["tipologradouro"])) {
                                echo $empresaf["tipologradouro"];
                            } ?>'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="localnascimento">Logradouro</label>
                        <input type='text' class="form-control" name="logradouro" id="logradouro" placeholder="Digite logradouro" value='<?php if (isset($empresaf["logradouro"])) {
                                echo $empresaf["logradouro"];
                            } ?>'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="numero">Número</label>
                        <input type='text' class="form-control" name="numero" id="numero" placeholder="Digite numero" value='<?php if (isset($empresaf["numero"])) {
                                echo $empresaf["numero"];
                            } ?>'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="bairro">Bairro</label>
                        <input type='text' class="form-control" name="bairro" id="bairro" placeholder="Digite bairro" value='<?php if (isset($empresaf["bairro"])) {
                                echo $empresaf["bairro"];
                            } ?>'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type='text' class="form-control" name="cidade" id="cidade" placeholder="Digite cidade" value='<?php if (isset($empresaf["cidade"])) {
                                echo $empresaf["cidade"];
                            } ?>'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="estado">Estado</label>
                        <input type='text' class="form-control" name="estado" id="estado" placeholder="Digite estado" value='<?php if (isset($empresaf["estado"])) {
                                echo $empresaf["estado"];
                            } ?>'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="estadocivil">Tel. Fixo</label>
                        <input type='text' maxlength="15" class="form-control telefone" name="telefone" id="telefone" placeholder="Digite telefone fixo" value='<?php if (isset($empresaf["telefone"])) {
                                echo $empresaf["telefone"];
                            } ?>'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="estadocivil">Celular</label>
                        <input type='text' maxlength="15" class="form-control telefone" name="celular" id="celular" placeholder="Digite celular" value='<?php if (isset($empresaf["celular"])) {
                                echo $empresaf["celular"];
                            } ?>'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" class="form-control" name='email' id="email" placeholder="Digite e-mail" value='<?php if (isset($empresaf["email"])) {
                                echo $empresaf["email"];
                            } ?>'>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="exampleInputFile">Imagem</label>
                        <input type="file" id="exampleInputFile">
                        <p class="help-block">Escolha uma imagem aqui para a filial</p>
                    </div>
                </div>
<?php if (!isset($_GET["tipo"]) && isset($_GET["codramo"])) {//filial ?>
                    <input type="hidden" name="codcategoria"  id="codcategoria" value="2"/>
                    
<?php
} elseif (isset($_GET["tipo"]) && isset($_GET["codramo"])) {//correspondente
    echo '<input type="hidden" name="codcategoria"  id="codcategoria" value="4"/>';
} else {
    echo '<input type="hidden" name="codcategoria"  id="codcategoria" value="1"/>';
}
?>
            </div><!-- /.box-body -->
            <div class="box-footer">
                <button type="submit"  class="btn btn-primary">Salvar</button>
            </div>
        </form>
    </div><!-- /.box -->
    <!--/.col (right) -->
</div>   <!-- /.row -->