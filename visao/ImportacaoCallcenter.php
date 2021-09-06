<?php
include "validacaoLogin.php";
if (isset($_GET["codimportacao"])) {
    $importacao = $conexao->comandoArray("select * from importacao where codimportacao = '{$_GET["codimportacao"]}'");
}
$titulo = "Carteiras de Clientes";
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
                            <li><a href="#tabs-1">Importação</a></li>
                            <li><a href="#tabs-2">Procurar</a></li>
                            <li><a href="#tabs-3">Liberar carteira</a></li>
                            <li><a href="#tabs-4" title="Procurar acesso de operadores">Procurar acesso</a></li>
                        </ul>
                        <div id="tabs-1"><?php include("formImportarCallcenter.php"); ?></div>
                        <div id="tabs-2"><?php include("formProcurarImportacao.php"); ?></div>
                        <div id="tabs-3"><?php include("formLiberarCarteira.php"); ?></div>
                        <div id="tabs-4"><?php include("./formProcurarAcessoOperador.php"); ?></div>                    
                        <span style="float: right; color: grey;width: 100%;text-align: right;">@ Sites e Sistemas PG</span>                            
                    </div>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php" ?>
            
        </div><!-- ./wrapper -->

        <?php include './scriptPadrao.php';?>
        <script type="text/javascript" src="../visao/recursos/js/jquery.form.min.js"></script>
        <script type="text/javascript" src="../visao/recursos/js/ajax/Importacao.js"></script>
        <script type="text/javascript" src="../visao/recursos/js/ajax/AcessoOperador.js"></script>
  
    </body>
</html>
