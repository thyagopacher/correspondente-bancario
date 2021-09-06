<?php
$configuracaop = $conexao->comandoArray("select statuspadraoproposta from configuracao where codempresa = {$_SESSION['codempresa']}");
if (isset($_GET["codproposta"])) {
    $sql = 'select proposta.*, tabela.nome as tabela, banco.nome as banco, banco.numbanco,
            beneficio.numbeneficio, bancop.numbanco as numbancop
        from proposta 
        inner join tabela on tabela.codtabela = proposta.codtabela
        inner join banco on banco.codbanco = proposta.codbanco
        left join banco as bancop on bancop.codbanco = proposta.codbanco2
        inner JOIN beneficiocliente AS beneficio on beneficio.codbeneficio = proposta.codbeneficio
        where proposta.codproposta = ' . $_GET["codproposta"];
    $proposta = $conexao->comandoArray($sql);
    $action = '../control/AtualizarProposta.php';
}else{
    $action = '../control/InserirProposta.php';
}
?>
<style>
    .row{
        margin: 0px;
    }
</style>
<div class="row">
    <form action="<?=$action?>" id="fproposta" name="fproposta" method="post">
        <input type="hidden" name="codcliente" id="codcliente" value="<?php if (isset($pessoap["codpessoa"])) {echo $pessoap["codpessoa"];}?>"/>
        <input type="hidden" name="codproposta" id="codproposta"  value="<?php if (isset($proposta["codproposta"])) {echo $proposta["codproposta"];}?>"/>         
        <input type="hidden" name="codtabelap" id="codtabelap"  value=""/>         
        <input type="hidden" name="statuspadraoproposta" id="statuspadraoproposta"  value="<?php if (isset($configuracaop["statuspadraoproposta"])) {echo $configuracaop["statuspadraoproposta"];}?>"/>         
        <div class="row">
            <div class="box box-default">
                <div class="box-body">
                    <div class="row">   
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nome">Vendedor</label>
                                <select class="form-control" name="codvendedor" id="codvendedor">
                                    <?php
                                    $sql = "select pessoa.nome, pessoa.codpessoa 
                                    from pessoa 
                                    inner join nivel on nivel.codnivel = pessoa.codnivel
                                    inner join empresa on empresa.codempresa = pessoa.codempresa
                                    where 1 = 1
                                    and (empresa.codempresa = '{$_SESSION["codempresa"]}' or empresa.codpessoa in(select codpessoa from pessoa where codempresa = {$_SESSION["codempresa"]}))
                                    and pessoa.codcategoria not in(1,6) order by pessoa.nome";
                                    $respessoa = $conexao->comando($sql);
                                    $qtdpessoa = $conexao->qtdResultado($respessoa);
                                    if ($qtdpessoa > 0) {
                                        echo '<option value="">--Selecione--</option>';
                                        while ($pessoa = $conexao->resultadoArray($respessoa)) {
                                            if (isset($proposta["codfuncionario"]) && $proposta["codfuncionario"] == $pessoa["codpessoa"]) {
                                                echo '<option selected value="', $pessoa["codpessoa"], '">', $pessoa["nome"], '</option>';
                                            } else {
                                                echo '<option value="', $pessoa["codpessoa"], '">', $pessoa["nome"], '</option>';
                                            }
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
                                <label>Dt. Venda</label>
                                <input type="date"  class="form-control" name="dtvenda" id="dtvenda" value="<?php
                                if (isset($proposta["dtvenda"])) {
                                    echo $proposta["dtvenda"];
                                }
                                ?>"/>
                            </div>
                        </div>   
  
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status proposta</label>
                                <select name="codstatus" id="codstatusProposta"  class="form-control">
                                    <?php
                                    if(!isset($_GET["codproposta"])){
                                        $and = " and codstatus = {$configuracaop["statuspadraoproposta"]}";
                                    }
                                    $sql = 'select codstatus, nome from statusproposta as status where 1=1 '.$and.' order by nome';
                                    $resstatus = $conexao->comando($sql);
                                    $qtdstatus = $conexao->qtdResultado($resstatus);
                                    if ($qtdstatus > 0) {
                                        while ($status = $conexao->resultadoArray($resstatus)) {
                                            echo '<option value="', $status["codstatus"], '">', $status["nome"], '</option>';
                                        }
                                    } else {
                                        echo '<option value="">--Nada encontrado--</option>';
                                    }
                                    ?>
                                </select>                            
                            </div>
                        </div> 
                        <div class="col-md-3" id="div_dtpago" style="display: none">
                            <div class="form-group">
                                <label>Dt. Pago</label>
                                <input type="date"  class="form-control" name="dtpago" id="dtpago" value="<?php if (isset($proposta["dtpago"])) {echo $proposta["dtpago"];} ?>"/>
                            </div>
                        </div>                         
                    </div>
                </div> 
            </div>
        </div>        
        <div class="row">
            <div class="box box-default">
                <h3 class="box-title" style="font-size: 18px;color: #444;">Dados da Proposta</h3>
                <div class="box-body">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Convênio</label>
                            <select class="form-control" name="codconvenio" id="codconvenio" required>
                                <?php
                                $resconvenio = $conexao->comando("select * from convenio where nome <> '' order by nome");
                                $qtdtconvenio = $conexao->qtdResultado($resconvenio);
                                if ($qtdtconvenio > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($convenio = $conexao->resultadoArray($resconvenio)) {
                                        if (isset($proposta) && $proposta["codconvenio"] != NULL && $proposta["codconvenio"] != "" && $convenio["codconvenio"] == $proposta["codconvenio"]) {
                                            echo '<option selected value="', $convenio["codconvenio"], '">', $convenio["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $convenio["codconvenio"], '">', $convenio["nome"], '</option>';
                                        }
                                    }
                                } else {
                                    echo '<option value="">--Nada encontrado--</option>';
                                }
                                ?>
                            </select>                            
                        </div>
                    </div>
                    <div class="col-md-3" id="div_especie_proposta">
                        <div class="form-group">
                            <label>Espécie</label>
                            <select class="form-control" name="codespecie" id="codespecie">
                                <?php
                                $resespecie = $conexao->comando("select * from especie where nome <> '' order by nome");
                                $qtdtespecie = $conexao->qtdResultado($resespecie);
                                if ($qtdtespecie > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($especie = $conexao->resultadoArray($resespecie)) {
                                        if (isset($proposta) && $proposta["codespecie"] != NULL && $proposta["codespecie"] != "" && $especie["codespecie"] == $proposta["codespecie"]) {
                                            echo '<option selected value="', $especie["codespecie"], '">', $especie["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $especie["codespecie"], '">', $especie["nome"], '</option>';
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
                            <label>Beneficio / Matricula</label>
                            <input type="text" list="beneficios" class="form-control" name="beneficio" id="beneficio" value="<?=$proposta["numbeneficio"]?>">
                            <datalist id="beneficios">
                                <?php
                                $sql = "select *, especie.nome as especie 
                                from beneficiocliente
                                inner join especie on especie.codespecie = beneficiocliente.codespecie
                                where beneficiocliente.codpessoa = '{$_GET["codpessoa"]}' and beneficiocliente.codempresa = '{$_SESSION['codempresa']}'";
                                $resbeneficio = $conexao->comando($sql);
                                $qtdbeneficio = $conexao->qtdResultado($resbeneficio);
                                if ($qtdbeneficio > 0) {
                                    while ($beneficio = $conexao->resultadoArray($resbeneficio)) {
                                        if (isset($proposta["codbeneficio"]) && $proposta["codbeneficio"] != NULL && $proposta["codbeneficio"] == $beneficio["codbeneficio"]) {
                                            echo '<option selected value="', $beneficio["numbeneficio"], '">', $beneficio["numbeneficio"], ' - ', $beneficio["especie"], '</option>';
                                        } else {
                                            echo '<option value="', $beneficio["numbeneficio"], '">', $beneficio["numbeneficio"], ' - ', $beneficio["especie"], '</option>';
                                        }
                                    }
                                }
                                ?>
                            </datalist>
                        </div>
                    </div>                    

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="sexo">Cód. Banco</label>
                            <input class="form-control" type="text" name="codbanco1" id="codbanco1" value="<?=$proposta["numbanco"]?>" title="Digite aqui para buscar o banco"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">                            
                            <label>Banco</label>
                            <select class="form-control" name="codbanco" id="codbanco" required>
                                <?php
                                $resbanco = $conexao->comando("select codbanco, nome, numbanco from banco where nome <> '' order by nome");
                                $qtdbanco = $conexao->qtdResultado($resbanco);
                                if ($qtdbanco > 0) {
                                    echo '<option value="">--Selecione--</option>';
                                    while ($banco = $conexao->resultadoArray($resbanco)) {
                                        if (isset($proposta["codbanco"]) && $proposta["codbanco"] == $banco["codbanco"]) {
                                            echo '<option selected value="', $banco["codbanco"], '">', $banco["numbanco"],' - ' ,$banco["nome"], '</option>';
                                        } else {
                                            echo '<option value="', $banco["codbanco"], '">', $banco["numbanco"],' - ' ,$banco["nome"], '</option>';
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
                            <label>Tabela</label>
                            <select class="form-control" name="codtabela" id="codtabela" required>
                                <option value="<?=$proposta["codtabela"]?>"><?=$proposta["tabela"]?></option>
                            </select>                            
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Valor Parcela</label>
                            <input type="text" class="form-control real" name="vlparcela" id="vlparcela" value="<?php
                            if (isset($proposta["vlparcela"])) {
                                echo $proposta["vlparcela"];
                            }
                            ?>"/>
                        </div>
                    </div>                    

                    <div class="col-md-3">                        
                        <div class="form-group">
                            <label>Valor Proposta</label>
                            <input type="text" class="form-control real" name="vlsolicitado" id="vlsolicitado" required value="<?php
                            if (isset($proposta["vlsolicitado"])) {
                                echo $proposta["vlsolicitado"];
                            }
                            ?>"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Valor liberado</label>
                            <input type="text" class="form-control real" name="vlliberado" id="vlliberado" value="<?php
                            if (isset($proposta["vlliberado"])) {
                                echo $proposta["vlliberado"];
                            }
                            ?>"/>
                        </div>
                    </div>                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Prazo</label>
                            <input type="number" class="form-control inteiro" name="prazo" id="prazo" required value="<?php
                            if (isset($proposta["prazo"])) {
                                echo $proposta["prazo"];
                            }
                            ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--aqui-->

        <div class="row">
            <div class="box box-default">
                <h3 class="box-title" style="font-size: 18px;color: #444;">Dados do Pagamento</h3>
                <div class="box-body">
                    <div class="row">   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Cód. Banco</label>
                                <input class="form-control inteiro" type="text" name="num_codbanco2" id="num_codbanco2" value="<?=$proposta["numbancop"]?>" title="Digite aqui para buscar o banco"/>
                            </div>
                            <div class="form-group">
                                <label>Banco Depósito</label>
                                <select name="codbanco2" id="codbanco2"  class="form-control">
                                    <?php
                                    $resbanco = $conexao->comando("select * from banco where nome <> '' order by nome");
                                    $qtdbanco = $conexao->qtdResultado($resbanco);
                                    if ($qtdbanco > 0) {
                                        echo '<option value="">--Selecione--</option>';
                                        while ($banco = $conexao->resultadoArray($resbanco)) {
                                            if (isset($proposta["codbanco2"]) && $proposta["codbanco2"] != NULL && $proposta["codbanco2"] == $banco["codbanco"]) {
                                                echo '<option selected value="', $banco["codbanco"], '">', $banco["nome"], '</option>';
                                            } else {
                                                echo '<option value="', $banco["codbanco"], '">', $banco["nome"], '</option>';
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
                                <label>Agência Depósito</label>
                                <input type="text"  class="form-control" name="agencia" id="agencia" value="<?php
                                if (isset($proposta["agencia"])) {
                                    echo $proposta["agencia"];
                                }
                                ?>"/>
                            </div>
                            <div class="form-group">
                                <label>Conta Depósito</label>
                                <input type="text"  class="form-control" name="conta" id="conta" value="<?php
                                if (isset($proposta["conta"])) {
                                    echo $proposta["conta"];
                                }
                                ?>"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Operação Depósito</label>
                                <input type="text"  class="form-control" name="operacao" id="operacao" value="<?php
                                if (isset($proposta["operacao"])) {
                                    echo $proposta["operacao"];
                                }
                                ?>"/>
                            </div>
                        </div>
                        <div class="col-md-3">                            
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="poupanca" id="poupanca" <?php
                                    if (isset($proposta["poupanca"]) && $proposta["poupanca"] == "s") {
                                        echo "checked";
                                    }
                                    ?> value="s"/>
                                    Poupança
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">                            
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="corrente" id="corrente" <?php
                                    if (isset($proposta["corrente"]) && $proposta["corrente"] == "s") {
                                        echo "checked";
                                    }
                                    ?> value="s"/>
                                    Conta Corrente
                                </label>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>

        <div class="row">
            <div class="box box-default">
                <div class="box-header with-border">

                </div>
                <div class="box-body">
                    <div class="row">   
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Observação</label>
                                <input type="text"  class="form-control" name="observacao" id="observacaoProposta" value="<?php
                                    if (isset($proposta["observacao"]) && $proposta["observacao"] != NULL && $proposta["observacao"] != "") {
                                        echo $proposta["observacao"];
                                    }
                                    ?>"/>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <input class="btn btn-primary" type="submit" name="submit2" id="submit2" value="Salvar"/>
                </div>                                        
            </div>
        </div>           
    </form>
</div>

<div class="row" id="listagemProposta"></div>


