<?php
    if(!isset($linha_tabela_prazo)){
        $linha_tabela_prazo = "INDICE_LINHA_TABELA";
    }
?>
<div class="row" <?php if(!isset($mostrar_tabela_prazo) || $mostrar_tabela_prazo == NULL || $mostrar_tabela_prazo == ""){echo "id='linha_exemplo' style='display: none'";}else{echo "id='tabela_linha$linha_tabela_prazo'";}?>>   
    <div class="col-md-2">
        <div class="form-group">
            <label>Inicio</label>
            <input class="form-control" type="date" name="dtinicio[]" id="dtinicio<?=$linha_tabela_prazo?>" value="<?php if(isset($tabelaprazo["dtinicio"])){echo $tabelaprazo["dtinicio"];}?>"/>                    
        </div>
    </div>                           
    <div class="col-md-2">
        <div class="form-group">
            <label>Fim</label>
            <input class="form-control" type="date" name="dtfim[]" id="dtfim<?=$linha_tabela_prazo?>" value="<?php if(isset($tabelaprazo["dtfim"])){echo $tabelaprazo["dtfim"];}?>"/>                    
        </div>
    </div>                           
    <div class="col-md-1">
        <div class="form-group">
            <label>Prazo de</label>
            <input class="form-control inteiro" type="number" min='0' max='999' name="prazode[]" id="prazode<?=$linha_tabela_prazo?>" value="<?php if(isset($tabelaprazo["prazode"])){echo $tabelaprazo["prazode"];}?>"/>                    
        </div>
    </div>                           
    <div class="col-md-1">
        <div class="form-group">
            <label>Até</label>
            <input class="form-control inteiro" type="number" min='0' max='999' name="prazoate[]" id="prazoate<?=$linha_tabela_prazo?>" value="<?php if(isset($tabelaprazo["prazoate"])){echo $tabelaprazo["prazoate"];}?>"/>                    
        </div>
    </div>                           
    <div class="col-md-1">
        <div class="form-group">
            <label>Comissão</label>
            <input class="form-control real comissao" type="text" name="comissao[]" id="comissao<?=$linha_tabela_prazo?>" onKeyUp="maskIt(this,event,'#########,##',true)" onchange="calculaNiveisComissao(this);" value="<?php if(isset($tabelaprazo["comissao"])){echo $tabelaprazo["comissao"];}?>"/>                    
        </div>
    </div>                           
    <div class="col-md-1">
        <div class="form-group">
            <label>Bonus</label>
            <input class="form-control real" type="text" name="bonus[]" id="bonus<?=$linha_tabela_prazo?>" onKeyUp="maskIt(this,event,'#########,##',true)" value="<?php if(isset($tabelaprazo["bonus"])){echo $tabelaprazo["bonus"];}?>"/>                    
        </div>
    </div> 
    <div class="col-md-2">
        <div class="form-group">
            <label>RCO</label>
            <input class="form-control real" type="text" name="rco[]" id="rco<?=$linha_tabela_prazo?>" onKeyUp="maskIt(this,event,'#########,##',true)" value="<?php if(isset($tabelaprazo["rco"])){echo $tabelaprazo["rco"];}?>"/>                    
        </div>
    </div> 
    <div class="col-md-2">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="pgto_liquido[]" id="pgto_liquido<?=$linha_tabela_prazo?>" value="s" <?php if(isset($tabelaprazo["pgto_liquido"]) && $tabelaprazo["pgto_liquido"] == "s"){echo 'checked';}?>/>     
                Pgto sob liquido
            </label>
        </div>
    </div>    
    <div class="col-md-12">
        <?php
        $array_niveis = array();
        $resnivel = $conexao->comando('select codnivel, nome from nivel where ((padrao = "s" and codnivel = 16) or (codempresa = ' . $_SESSION["codempresa"] . ' and padrao = "n")) and codnivel <> 1 and codnivel <> 19 order by nivel.codnivel');
        $qtdnivel = $conexao->qtdResultado($resnivel);
        if ($qtdnivel > 0) {
            while ($nivel = $conexao->resultadoArray($resnivel)) {
                $array_niveis[] = $nivel["codnivel"];
                
                $sql = 'select porcentagem from pctnivel where codempresa = '. $_SESSION["codempresa"]. ' and codnivel = '. $nivel["codnivel"];
                $pctnivel = $conexao->comandoArray($sql);
                
                $sql = 'select porcentagem from pctniveltabelap where codempresa = '. $_SESSION["codempresa"]. ' and codtabelap = '. $tabelaprazo["codtabelap"]. ' and codnivel = '. $nivel["codnivel"];
                $pctniveltabelap = $conexao->comandoArray($sql);
                ?>
                <div class="col-md-1">
                    <div class="form-group">
                        <label><?= $nivel["nome"] ?></label>
                        <input class="form-control real" type="text" comissao_nivel="<?=$pctnivel["porcentagem"]?>" name="pctnivel[]" id="pctnivel_<?=$linha_tabela_prazo?>_<?= $nivel["codnivel"] ?>" value="<?php if(isset($pctniveltabelap["porcentagem"])){echo number_format($pctniveltabelap["porcentagem"], 2, ',', '');}?>"/>                    
                    </div>
                </div>
                <?php
            }
            echo '<input type="hidden" name="niveis" id="niveis" value="',implode(",", $array_niveis),'"/>';
        }
        
        ?>   
    </div>    
    <div class="col-md-4"><label>Adicionar mais Prazos à Tabela?</label><a class="btn btn-primary" href="javascript: adicionarLinhaTabelaPrazo('<?=$linha_tabela_prazo?>')" title="adicionar linha de tabela prazo">+</a><a class="btn btn-primary" href="javascript: removerLinhaTabelaPrazo('<?=$linha_tabela_prazo?>')" title="retirar linha de tabela prazo">-</a></div>
</div>