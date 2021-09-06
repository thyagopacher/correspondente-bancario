<?php
include "validacaoLogin.php";
    $codmeta = filter_input(INPUT_GET, 'codmeta', FILTER_VALIDATE_INT);
    if (isset($codmeta)) {
        $meta = $conexao->comandoArray("select * from metafuncionario where codmeta = '{$codmeta}'");
        $titulo = "Alterar meta colaborador";
        $action = "../control/AtualizarMetaFuncionario.php";
    } else {
        $titulo = "Cadastrar meta colaborador";
        $action = "../control/InserirMetaFuncionario.php";
    }
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
                            <li><a href="#tabs-1">Cadastro</a></li>
                            <li><a href="#tabs-2">Procurar</a></li>
                        </ul>   
                        <div id="tabs-1"><?php include("formMetaFuncionario.php"); ?></div>                    
                        <div id="tabs-2"><?php include("formProcurarMetaFuncionario.php"); ?></div>                    
                        <span style="float: right; color: grey;width: 100%;text-align: right;">@ Sites e Sistemas PG</span>                            
                    </div>   <!-- /.row -->
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php"?>
        </div><!-- ./wrapper -->

        <?php include './scriptPadrao.php';?>
        <script type="text/javascript" src="../visao/recursos/js/ajax/MetaFuncionario.js"></script>
    </body>
</html>
