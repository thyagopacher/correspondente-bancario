<?php
include "validacaoLogin.php";
    $codbaixa = filter_input(INPUT_GET, 'codbaixa', FILTER_VALIDATE_INT);
    if (isset($codbaixa)) {
        $baixa = $conexao->comandoArray("select * from baixa where codbaixa = '{$codbaixa}'");
        $titulo = "Alterar baixas";
        $action = "../control/AtualizarBaixa.php";
    } else {
        $titulo = "Cadastrar baixas";
        $action = "../control/InserirBaixa.php";
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?=$sitep["nome"]?> | Baixas</title>
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
                            <li><a href="#tabs-1">Cadastrar</a></li>
                            <li><a href="#tabs-2">Procurar</a></li>
                        </ul>   
                        <div id="tabs-1">
                            <?php include("formBaixa.php"); ?>
                        </div>
                        <div id="tabs-2">
                            <?php include("formProcurarBaixa.php"); ?>
                        </div>
                        <span style="float: right; color: grey;width: 100%;text-align: right;">@ Sites e Sistemas PG</span>                            
                    </div>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php"?>
            
        </div><!-- ./wrapper -->

        <?php include './scriptPadrao.php';?>
        <script type="text/javascript" src="/visao/recursos/js/ajax/Baixa.js"></script>

        <?php if(isset($_GET['procurar']) && $_GET['procurar'] != NULL && $_GET['procurar'] == "1"){?>
        <script>
            $("#tabs").tabs({
                active: 1
            });
            procurarBaixa2(true);
        </script>        
        <?php } ?>       
    </body>
</html>
