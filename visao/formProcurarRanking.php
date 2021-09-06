<form id="fpranking" action="../control/ProcurarRankingRelatorio.php" target="_blank" role="form" class="form-horizontal form-groups-bordered" method="POST" onsubmit="return false;">                       
    <table class="tabela_formulario">
        <input type="hidden" name="html" id="html" value=""/>
        <input type="hidden" name="tipo" id="tipo" value="pdf"/>
        <input type="hidden" name="procurar" id="procurar" value="<?=$nivelp["procurar"]?>"/>
        <tr>
            <td style="font-size: 12px; color: #666;">Mês</td>
            <td>
                <select class="form-control" name="mes" id="mes">
                    <?php
                    for($i = 1; $i <= 12; $i++){
                        if(date("m") == $i){
                            echo '<option selected value="',$i,'">',$i,' / ',date("Y"),'</option>';
                        }else{
                            echo '<option value="',$i,'">',$i,' / ',date("Y"),'</option>';
                        }
                    }
                    ?>
                    
                </select>
            </td>  
            <?php if($_SESSION["codnivel"] == 1 || $_SESSION["codnivel"] == 19){?>
            <td><label for="sexo">Unidade</label></td>
            <td>
                <select class="form-control" name="codfilial" id="codfilial">
                <?php
                    $sql = 'select codempresa, razao 
                    from empresa where (codempresa = '.$_SESSION["codempresa"].'
                    or codpessoa in(select codpessoa from pessoa where codempresa = '.$_SESSION["codempresa"].'))';
                    $resempresa = $conexao->comando($sql);
                    $qtdempresa = $conexao->qtdResultado($resempresa);
                    if($qtdempresa > 0){
                        echo '<option value="">--Selecione--</option>';
                        while($empresa = $conexao->resultadoArray($resempresa)){
                            if($empresa['codempresa'] == $_SESSION['codempresa']){
                                echo '<option selected value="',$empresa["codempresa"],'">',$empresa["razao"],'</option>';
                            }else{
                                echo '<option value="',$empresa["codempresa"],'">',$empresa["razao"],'</option>';
                            }
                        }
                    }
                ?>
                </select>
            </td>
            <?php }?>
            <td></td>
        </tr>
    </table>
</form>

<div id="listagemRanking"></div>
