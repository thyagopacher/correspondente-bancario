<?php
include "validacaoLogin.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?=$sitep["nome"]?> | Relatorio de Comissão</title>
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
                        Relatório de Comissão

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
                            <?php if ($nivelp["procurar"] == 1) { ?>
                                <li><a href="#tabs-2">RECEBER</a></li>
                                <li><a href="#tabs-3">PAGAR</a></li>
                            <?php } ?>
                        </ul>   
                        <div id="tabs-2"><?php if ($nivelp["procurar"] == 1) {$situacaoComissao="receber"; include 'formProcurarComissao.php';} ?></div>
                        <div id="tabs-3"><?php if ($nivelp["procurar"] == 1) {$situacaoComissao="pagar";   include 'formProcurarComissao.php';} ?></div>
                       
                        <span style="float: right; color: grey;width: 100%;text-align: right;">@ Sites e Sistemas PG</span>                            
                    </div>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php" ?>
            
        </div><!-- ./wrapper -->

        <?php include './scriptPadrao.php';?>
        
        <script src="../visao/recursos/js/ajax/Comissao.js"></script>
        <script src="../visao/recursos/js/ajax/Conta.js"></script>        
  
    </body>
</html>
