<?php
include "validacaoLogin.php";

$southconsultap = $conexao->comandoArray('select * from southconsulta where codconsulta = '. $_GET["codconsulta"]);
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?=$sitep["nome"]?> | Consultas South</title>
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
                           <li><a href="#tabs-1">Contratar Consulta</a></li>
                           <li><a href="#tabs-2">Procurar Contratos</a></li>
                           <li><a href="#tabs-3">Procurar Consultas</a></li>
                       </ul>
                       <div id="tabs-1">
                           <?php include("formSouthConsulta.php");?>
                       </div>
                       <div id="tabs-2">
                           <?php include("formProcurarSouthConsulta.php");?>
                       </div>
                       <div id="tabs-3">
                           <?php include("formProcurarConsultaSouth.php");?>
                       </div>
                   </div> 
                </section>
            </div>
            <?php include "footer.php" ?>
        </div><!-- ./wrapper -->

        <?php include './scriptPadrao.php';?>
        <script src="../visao/recursos/js/ajax/ConsultasSouth.js"></script>
        <script src="../visao/recursos/js/ajax/SouthConsulta.js"></script>
    </body>
</html>
