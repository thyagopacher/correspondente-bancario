<?php
include "validacaoLogin.php";
$titulo = "Acompanhar Carteira";
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?=$sitep["nome"]?> | <?= $titulo ?></title>
        <?php include 'head.php';?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <?php include "header.php"; ?>
            <?php include "menu.php"; ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <?= $titulo ?>
                        
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li><a href="#"><?= $nivelp["modulo"] ?></a></li>
                        <li class="active"><?= $nivelp["pagina"] ?></li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">Relatório</a></li>
                        </ul>   
                        <div id="tabs-1">
                            <form name="fprocurarAcompanhamentoCarteira" id="fprocurarAcompanhamentoCarteira" method="post">
                                <table class="tabela_formulario">
                                    <tr>
                                        <td>Filial:</td>
                                        <td>
                                            <select name="filial" id="filial" class="form-control">
                                                <?php
                                                $resfilial = $conexao->comando("select codempresa, razao from empresa order by razao");
                                                $qtdfilial = $conexao->qtdResultado($resfilial);
                                                if($qtdfilial > 0){
                                                    echo '<option value="">--Selecione--</option>';
                                                    while($filial = $conexao->resultadoArray($resfilial)){
                                                        echo '<option value="',$filial["codempresa"],'">',  strtoupper($filial["razao"]),'</option>';
                                                    }
                                                }else{
                                                    echo '<option value="">--Nada encontrado--</option>';
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <div id="resultado_acompanhamento_carteira"></div>
                        </div>                    
                        <span style="float: right; color: grey;width: 100%;text-align: right;">@ Sites e Sistemas PG</span>                            
                    </div>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php" ?>
        </div><!-- ./wrapper -->


        <?php include './scriptPadrao.php';?>
        
        <script src="../visao/recursos/js/jquery.form.min.js"></script>
        <script src="../visao/recursos/js/ajax/AcompanharCarteira.js"></script>
        
    </body>
</html>
