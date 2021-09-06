<?php
include "validacaoLogin.php";
if (isset($_GET["codespecie"]) && $_GET["codespecie"] != NULL && trim($_GET["codespecie"]) != "") {
    $titulo = "Alterar Especie INSS";
    $action = "../control/AtualizarEspecie.php";
    $especie = $conexao->comandoArray('select * from especie where codespecie = '. $_GET["codespecie"]);
} else {
    $titulo = "Cadastrar Especie INSS";
    $action = "../control/InserirEspecie.php";
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
                                <?php if ($nivelp["procurar"] == 1) { ?>
                                    <li><a href="#tabs-2">Procurar</a></li>
                                <?php } ?>
                            </ul>   
                            <div id="tabs-1">
                                <?php include("./formEspecie.php"); ?>
                            </div>
                            <?php if ($nivelp["procurar"] == 1) { ?>
                                <div id="tabs-2">
                                    <?php include("formProcurarEspecie.php"); ?>
                                </div>
                            <?php } ?>                     
                            <span style="float: right; color: grey;width: 100%;text-align: right;">@ Sites e Sistemas PG</span>                            
                        </div>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php"?>
        </div><!-- ./wrapper -->

        <?php include './scriptPadrao.php';?>
        <script type="text/javascript" src="../visao/recursos/js/ajax/Especie.js"></script>
          
    </body>
</html>
