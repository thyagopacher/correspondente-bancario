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
                <form action="<?= $action ?>" id="fpessoa" name="fpessoa" method="post">
                    <input type="hidden" name="codcategoria" id="codcategoria" value="5"/>
                    <input type="hidden" name="codpessoa" id="codpessoa" value="<?= $_GET["codpessoa"] ?>"/>
                    <input type="hidden" name="codempresa" id="codempresa" value="<?= $_GET["codempresa"] ?>"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nivel</label>
                            <select class="form-control" name="codnivel" id="codnivel" <?php if(isset($pessoap["codpessoa"]) && $pessoap["codpessoa"] == $_SESSION["codpessoa"] && $_SESSION["codnivel"] != '1'){echo "disabled";}?> required>
                                <?php
                                if($correspondente == "s"){
                                    $and .= " and nivel.codnivel in(19)";
                                } elseif($filial == "s"){
                                    $and .= " and nivel.codnivel = 2";
                                }elseif($_SESSION["codnivel"] != '1'){
                                    $and .= " and nivel.codnivel in(16, 17, 19, 24)";
                                }else{
                                    $and .= " and nivel.codnivel in(16, 17, 19, 24, 1)";
                                }                               
                                $sql = "select * 
                                from nivel where (nivel.padrao = 's' or nivel.codempresa = '{$_SESSION['codempresa']}') {$and}   
                                order by nivel.nome";
                                $resnivel = $conexao->comando($sql);
                                $qtdnivel = $conexao->qtdResultado($resnivel);
                                if ($qtdnivel > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($nivel = $conexao->resultadoArray($resnivel)) {
                                        if (isset($pessoap["codnivel"]) && $pessoap["codnivel"] == $nivel["codnivel"]) {
                                            echo '<option selected value="', $nivel["codnivel"], '">', $nivel["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $nivel["codnivel"], '">', $nivel["nome"], '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="">Nada encontrado</option>';
                                }
                                ?>
                            </select>                            
                        </div>
                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="email" class="form-control" name='email' id="email" placeholder="Digite e-mail" value="<?php
                            if (isset($pessoap["email"]) && $pessoap["email"] != NULL && $pessoap["email"] != "") {
                                echo $pessoap["email"];
                            }
                            ?>">                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type='text' class="form-control" name="nome" id="nome" placeholder="Digite nome" value="<?php
                            if (isset($pessoap["nome"]) && $pessoap["nome"] != NULL && $pessoap["nome"] != "") {
                                echo $pessoap["nome"];
                            }
                            ?>">
                        </div>
      
                        <div class="form-group">
                            <label for="sexo">Sexo</label>
                            <select class="form-control" name="sexo" id="sexo">
                                <option value="m" <?php
                                if (isset($pessoap["sexo"]) && $pessoap["sexo"] == "m") {
                                    echo "selected";
                                }
                                ?>>Masculino</option>
                                <option value="f" <?php
                                if (isset($pessoap["sexo"]) && $pessoap["sexo"] == "f") {
                                    echo "selected";
                                }
                                ?>>Feminino</option>
                            </select>                            

                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="statusPessoa">Status</label>
                            <select class="form-control" name="status" id="statusPessoa" title="Escolha aqui se a pessoa está ativa ou não">
                                <option>--Selecione--</option>
                                <option value="a" <?php
                                if (isset($pessoap["status"]) && $pessoap["status"] == "a") {
                                    echo "selected";
                                }
                                ?>>ativo</option>
                                <option value="i" <?php
                                if (isset($pessoap["status"]) && $pessoap["status"] == "i") {
                                    echo "selected";
                                }
                                ?>>inativo</option>
                            </select>
                        </div>

                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">CPF</label>
                            <input type='text' class="form-control" name="cpf" id="cpf" placeholder="Digite cpf" value="<?php
                            if (isset($pessoap["cpf"]) && $pessoap["cpf"] != NULL && $pessoap["cpf"] != "") {
                                echo $pessoap["cpf"];
                            }
                            ?>">

                        </div>  
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">RG</label>
                            <input type='text' class="form-control" name="rg" id="rg" placeholder="Digite rg" value="<?php
                            if (isset($pessoap["rg"]) && $pessoap["rg"] != NULL && $pessoap["rg"] != "") {
                                echo $pessoap["rg"];
                            }
                            ?>">
                        </div>                                               
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Org. Emissor</label>
                            <input type='text' class="form-control" name="orgaoemissor" id="orgaoemissor" placeholder="Digite orgao emissor" value="<?php
                            if (isset($pessoap["orgaoemissor"]) && $pessoap["orgaoemissor"] != NULL && $pessoap["orgaoemissor"] != "") {
                                echo $pessoap["orgaoemissor"];
                            }
                            ?>">
                        </div>   
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">UF</label>
                            <select class="form-control" name="ufrg" id="ufrg" title="Escolha o estado onde foi emitido o RG">
                                <?php
                                $resestado = $conexao->comando("select * from estado order by nome");
                                $qtdestado = $conexao->qtdResultado($resestado);
                                if ($qtdestado > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($estado = $conexao->resultadoArray($resestado)) {
                                        if (isset($pessoa["ufrg"]) && $pessoa["ufrg"] == $estado["codestado"]) {
                                            echo '<option selected value="', $estado["codestado"], '">', $estado["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $estado["codestado"], '">', $estado["nome"], '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>                                                
                            </select>
                        </div>                                        
                    </div><!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nome">Dt. Emissão</label>
                            <input type="date" class="form-control" name="dtemissaorg" id="dtemissaorg" title="Digite data de emissão do RG" value="<?php if(isset($pessoap["dtemissaorg"])){echo $pessoap["dtemissaorg"];}?>">
                        </div>  
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="sexo">Dt. Nascimento</label>
                            <input type="date" class="form-control" name="dtnascimento" id="dtnascimento" placeholder="Digite dt. nascimento"  value="<?php if(isset($pessoap["dtnascimento"])){echo $pessoap["dtnascimento"];}?>">
                        </div>                                        
                    </div><!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="localnascimento">Local Nasc.</label>
                            <input type='text' class="form-control" name="localnascimento" id="localnascimento" placeholder="Digite local nascimento" value="<?php if(isset($pessoap["localnascimento"])){echo $pessoap["localnascimento"];}?>">
                        </div>        
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="localnascimento">CEP</label>
                            <input type='text' class="form-control" name="cep" id="cep" maxlength="8" placeholder="Digite cep" value="<?php if(isset($pessoap["cep"])){echo $pessoap["cep"];}?>">
                        </div>                                        
                    </div><!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="localnascimento">Tip. Logradouro</label>
                            <input type='text' class="form-control" name="tipologradouro" id="tipologradouro" maxlength="8" placeholder="Digite tipo logradouro" value="<?php if(isset($pessoap["tipologradouro"])){echo $pessoap["tipologradouro"];}?>">
                        </div>      
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="numero">Número</label>
                            <input type='text' class="form-control" name="numero" id="numero" placeholder="Digite numero" value="<?php if(isset($pessoap["numero"])){echo $pessoap["numero"];}?>">
                        </div>                                        
                    </div><!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="localnascimento">Logradouro</label>
                            <input type='text' class="form-control" name="logradouro" id="logradouro" placeholder="Digite logradouro" value="<?php if(isset($pessoap["logradouro"])){echo $pessoap["logradouro"];}?>">
                        </div>                                         
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bairro">Bairro</label>
                            <input type='text' class="form-control" name="bairro" id="bairro" placeholder="Digite bairro" value="<?php if(isset($pessoap["bairro"])){echo $pessoap["bairro"];}?>">
                        </div>                                        
                    </div><!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cidade">Cidade</label>
                            <input type='text' class="form-control" name="cidade" id="cidade" placeholder="Digite cidade" value="<?php if(isset($pessoap["cidade"])){echo $pessoap["cidade"];}?>">
                        </div>      
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estadocivil">Pai</label>
                            <input type='text' class="form-control" name="pai" id="pai" placeholder="Digite pai" value="<?php if(isset($pessoap["pai"])){echo $pessoap["pai"];}?>">
                        </div>                                         

                    </div><!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select class="form-control" name="estado" id="estado" title="Escolha estado onde ele mora">
                                <?php
                                $resestado = $conexao->comando("select * from estado order by nome");
                                $qtdestado = $conexao->qtdResultado($resestado);
                                if ($qtdestado > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($estado = $conexao->resultadoArray($resestado)) {
                                        if (isset($pessoa["ufrg"]) && $pessoa["ufrg"] == $estado["codestado"]) {
                                            echo '<option selected value="', $estado["codestado"], '">', $estado["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $estado["codestado"], '">', $estado["nome"], '</option>';
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
                            <label for="estadocivil">Mãe</label>
                            <input type='text' class="form-control" name="mae" id="mae" placeholder="Digite mãe" value="<?php if(isset($pessoap["mae"])){echo $pessoap["mae"];}?>">
                        </div>                                        

                    </div><!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="estadocivil">Est. Civil</label>
                            <select class="form-control" name="estadocivil" id="estadocivil" title="Digite estado civil">
                                <?php
                                $resestado = $conexao->comando("select * from estadocivil order by nome");
                                $qtdestado = $conexao->qtdResultado($resestado);
                                if ($qtdestado > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($estado = $conexao->resultadoArray($resestado)) {
                                        if (isset($pessoa["estadocivil"]) && $pessoa["estadocivil"] == $estado["codestado"]) {
                                            echo '<option selected value="', $estado["codestado"], '">', $estado["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $estado["codestado"], '">', $estado["nome"], '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>
                        </div>  

                    </div><!-- /.col -->
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputFile">Login</label>
                            <input type="text" class="form-control" autocomplete="off" required name="login" id="login" size="50" maxlength="250" value="<?php
                            if (isset($pessoap["login"])) {
                                echo $pessoap["login"];
                            }
                            ?>">
                        </div>                                        
                    </div><!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputFile">Senha</label>
                            <input class="form-control" type="password" autocomplete="off" required name='senha' id="senha" value="<?php
                            if (isset($pessoap["senha"])) {
                                echo base64_decode($pessoap["senha"]);
                            }
                            ?>" placeholder="Senha">
                        </div>                                        
                    </div><!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputFile">Imagem</label>
                            <input class="form-control" type="file" id="imagem" name="imagem">
                            <?php
                                if(isset($pessoap["imagem"]) && $pessoap["imagem"] != NULL && $pessoap["imagem"] != ""){
                                    echo '<a target="_blank" href="../arquivos/',$pessoap["imagem"],'">Img pessoa</a>';
                                }else{
                                    echo '<p class="help-block">Escolha uma imagem aqui para a pessoa</p>';
                                }
                            ?>
                        </div>                                        
                    </div><!-- /.col -->                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="exampleInputFile">Webcam</label>
                            <a class="form-control" href="javascript: abreTiraFoto(<?=$pessoap["codpessoa"]?>);">Foto da webcam</a>
                        </div>                                        
                    </div><!-- /.col -->                    
                </form>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" name="submit" id="submit" value="Salvar"/>
                    </div>                                        
                </div>
            </div>
        </div>
    </div>
    <!--/.col (right) -->
</div>