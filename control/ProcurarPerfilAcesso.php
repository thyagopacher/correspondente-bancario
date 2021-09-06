<?php

session_start();
include '../model/Conexao.php';
$conexao = new Conexao();
$and = "";
$andModulo = '';
if (isset($_POST["codnivel"]) && $_POST["codnivel"] != NULL && $_POST["codnivel"] != "") {
    $and .= ' and codnivel = '.$_POST["codnivel"];
}
if (isset($_POST["naomaster"]) && $_POST["naomaster"] != NULL && $_POST["naomaster"] != "" && $_POST["naomaster"] == "true") {
    $andModulo .= ' and codmodulo <> 8';
    $and       .= ' and codnivel <> 1';
}

$sql = 'select codmodulo, nome from modulo where 1 = 1 '.$andModulo.' order by codmodulo';
$resmodulo = $conexao->comando($sql);
$qtdmodulo = $conexao->qtdResultado($resmodulo);
if ($qtdmodulo > 0) {
    echo '<table class="tabela_formulario" style="width: 850px;">';
    while($modulo = $conexao->resultadoArray($resmodulo)) {
        echo '<tr><td colspan="1" class="tdmd">',$modulo["nome"],'</td>';
        echo '<td colspan="8"><input title="Marcar/desmarcar todos da página" onclick="marcarModulo(', $modulo["codmodulo"], ')" type="checkbox" class="checkPagina" name="marcar_desmarcarModulo', $modulo["codmodulo"], '" id="marcar_desmarcarModulo', $modulo["codmodulo"], '" value="', $modulo["codmodulo"], '"/>Marcar todos do módulo</td>';
        echo '</tr>';
        $sql = 'select codpagina, nome from pagina where codpai = 0 and codmodulo = '.$modulo["codmodulo"].' order by nome';

        $res = $conexao->comando($sql);
        $qtd = $conexao->qtdResultado($res);
        if ($qtd > 0) {            
            while ($pagina = $conexao->resultadoArray($res)) {
                if (isset($_POST["codnivel"]) && $_POST["codnivel"] != NULL && $_POST["codnivel"] != "") {
                    $nivelpagina = $conexao->comandoArray('select mostrar, inserir, atualizar, excluir, procurar, gerapdf, geraexcel from nivelpagina where codpagina = '.$pagina["codpagina"].$and);
                }

                echo '<tr>';
                echo '<td>', $pagina["nome"], '</td>';
                echo '<td><input title="Marcar/desmarcar todos da página" onclick="marcarLinhaPagina(', $pagina["codpagina"], ')" type="checkbox" class="checkPagina" name="marcar_desmarcarpg', $pagina["codpagina"], '" id="marcar_desmarcarpg', $pagina["codpagina"], '" value="', $pagina["codpagina"], '"/>*</td>';
                if ($nivelpagina['mostrar'] == 1) {
                    echo '<td><input type="checkbox" class="checkPagina menu pagina', $pagina["codpagina"], ' modulo',$modulo["codmodulo"],'" checked name="pagina', $pagina["codpagina"], '[]" value="h"/>Menu</td>';
                } else {
                    echo '<td><input type="checkbox" class="checkPagina menu pagina', $pagina["codpagina"], ' modulo',$modulo["codmodulo"],'" name="pagina', $pagina["codpagina"], '[]" value="h"/>Menu</td>';
                }
                if ($nivelpagina['inserir'] == 1) {
                    echo '<td><input type="checkbox" class="checkPagina inserir pagina', $pagina["codpagina"], ' modulo',$modulo["codmodulo"],'" checked name="pagina', $pagina["codpagina"], '[]" value="i"/>Inserir</td>';
                } else {
                    echo '<td><input type="checkbox" class="checkPagina inserir pagina', $pagina["codpagina"], ' modulo',$modulo["codmodulo"],'" name="pagina', $pagina["codpagina"], '[]" value="i"/>Inserir</td>';
                }
                if ($nivelpagina['atualizar'] == 1) {
                    echo '<td><input type="checkbox" class="checkPagina atualizar pagina', $pagina["codpagina"], ' modulo',$modulo["codmodulo"],'" checked name="pagina', $pagina["codpagina"], '[]" value="a"/>Alterar</td>';
                } else {
                    echo '<td><input type="checkbox" class="checkPagina atualizar pagina', $pagina["codpagina"], ' modulo',$modulo["codmodulo"],'" name="pagina', $pagina["codpagina"], '[]" value="a"/>Alterar</td>';
                }
                if ($nivelpagina['excluir'] == 1) {
                    echo '<td><input type="checkbox" class="checkPagina excluir pagina', $pagina["codpagina"], ' modulo',$modulo["codmodulo"],'" checked name="pagina', $pagina["codpagina"], '[]" value="e"/>Excluir</td>';
                } else {
                    echo '<td><input type="checkbox" class="checkPagina excluir pagina', $pagina["codpagina"], ' modulo',$modulo["codmodulo"],'" name="pagina', $pagina["codpagina"], '[]" value="e"/>Excluir</td>';
                }
                if ($nivelpagina['procurar'] == 1) {
                    echo '<td><input type="checkbox" class="checkPagina procurar pagina', $pagina["codpagina"], ' modulo',$modulo["codmodulo"],'" checked name="pagina', $pagina["codpagina"], '[]" value="p"/>Procurar</td>';
                } else {
                    echo '<td><input type="checkbox" class="checkPagina procurar pagina', $pagina["codpagina"], ' modulo',$modulo["codmodulo"],'" name="pagina', $pagina["codpagina"], '[]" value="p"/>Procurar</td>';
                }
                if ($nivelpagina['gerapdf'] == 1) {
                    echo '<td><input type="checkbox" class="checkPagina gerapdf pagina', $pagina["codpagina"], ' modulo',$modulo["codmodulo"],'" checked name="pagina', $pagina["codpagina"], '[]" value="pdf"/>PDF</td>';
                } else {
                    echo '<td><input type="checkbox" class="checkPagina gerapdf pagina', $pagina["codpagina"], ' modulo',$modulo["codmodulo"],'" name="pagina', $pagina["codpagina"], '[]" value="pdf"/>PDF</td>';
                }
                if ($nivelpagina['geraexcel'] == 1) {
                    echo '<td><input type="checkbox" class="checkPagina geraexcel pagina', $pagina["codpagina"], ' modulo',$modulo["codmodulo"],'" checked name="pagina', $pagina["codpagina"], '[]" value="xls"/>XLS</td>';
                } else {
                    echo '<td><input type="checkbox" class="checkPagina geraexcel pagina', $pagina["codpagina"], ' modulo',$modulo["codmodulo"],'" name="pagina', $pagina["codpagina"], '[]" value="xls"/>XLS</td>';
                }
                echo '</tr>';
            }
            
        }
    }
    echo '</table>';
}
