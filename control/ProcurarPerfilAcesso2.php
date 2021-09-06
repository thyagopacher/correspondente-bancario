 <?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }     
    include("../model/Conexao.php");
    $conexao = new Conexao();
    $and     = "";
    if(isset($_POST["codnivel"]) && $_POST["codnivel"] != NULL && $_POST["codnivel"] != ""){
        $and .= " and codnivel = '{$_POST["codnivel"]}'";
    }
    if(isset($_POST["naomaster"]) && $_POST["naomaster"] != NULL && $_POST["naomaster"] != "" && $_POST["naomaster"] == "true"){
        $and .= " and codnivel <> '1'";
    }

    $res = $conexao->comando("select * from pagina where codmodulo <> '8' order by nome");
    $qtd = $conexao->qtdResultado($res);
    if($qtd > 0){
        echo '<table class="tabela_formulario">';
        while($pagina = $conexao->resultadoArray($res)){
            if($and != ""){
                $nivelpagina = $conexao->comandoArray("select * from nivelpagina where codpagina = '{$pagina["codpagina"]}' {$and}");
            }
            echo '<tr>';
            echo '<td>', $pagina["nome"], '</td>';
            if(isset($nivelpagina) && $nivelpagina["mostrar"] == 1){
                echo '<td><input type="checkbox" class="checkPagina" checked name="paginaMenu[]" value="',$pagina["codpagina"],'"/>Habilitar menu</td>';
            }else{
                echo '<td><input type="checkbox" class="checkPagina" name="paginaMenu[]" value="',$pagina["codpagina"],'"/>Habilitar menu</td>';
            }
            if(isset($nivelpagina) && $nivelpagina["inserir"] == 1){
                echo '<td><input type="checkbox" class="checkPagina" checked name="paginaInserir[]" value="',$pagina["codpagina"],'"/>Inserir</td>';
            }else{
                echo '<td><input type="checkbox" class="checkPagina" name="paginaInserir[]" value="',$pagina["codpagina"],'"/>Inserir</td>';
            }
            if(isset($nivelpagina) && $nivelpagina["atualizar"] == 1){
                echo '<td><input type="checkbox" class="checkPagina" checked name="paginaAlterar[]" value="',$pagina["codpagina"],'"/>Alterar</td>';
            }else{
                echo '<td><input type="checkbox" class="checkPagina" name="paginaAlterar[]" value="',$pagina["codpagina"],'"/>Alterar</td>';
            }
            if(isset($nivelpagina) && $nivelpagina["excluir"] == 1){
                echo '<td><input type="checkbox" class="checkPagina" checked name="paginaExcluir[]" value="',$pagina["codpagina"],'"/>Excluir</td>';
            }else{
                echo '<td><input type="checkbox" class="checkPagina" name="paginaExcluir[]" value="',$pagina["codpagina"],'"/>Excluir</td>';
            }
            if(isset($nivelpagina) && $nivelpagina["procurar"] == 1){
                echo '<td><input type="checkbox" class="checkPagina" checked name="paginaProcurar[]" value="',$pagina["codpagina"],'"/>Procurar</td>';
            }else{
                echo '<td><input type="checkbox" class="checkPagina" name="paginaProcurar[]" value="',$pagina["codpagina"],'"/>Procurar</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }else{
        echo "Cadastre antes alguma funcionalidade!";
    }