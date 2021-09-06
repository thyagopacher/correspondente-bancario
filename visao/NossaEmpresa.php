<?php
include "validacaoLogin.php";


$empresaf = $conexao->comandoArray("select * from empresa where codempresa = '{$_SESSION["codempresa"]}'");
$action = "../control/AtualizarEmpresa.php";
$titulo = "Nossa Empresa";
$_GET["codempresa"] = $_SESSION["codempresa"];
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
                            <li><a href="#tabs-2">Configurações</a></li>
                        </ul>   
                        <div id="tabs-1">
                            <?php include "formEmpresa.php";?>
                        </div>
                        <div id="tabs-2">
                            <?php include("formConfiguracao.php"); ?>
                        </div>                           
                         
                        <span style="float: right; color: grey;width: 100%;text-align: right;">@ Sites e Sistemas PG</span>                            
                    </div>                    
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php"?>
        </div><!-- ./wrapper -->

         <?php include './scriptPadrao.php';?>
        
        <script src="../visao/recursos/js/jquery.form.min.js"></script>
        <script src="../visao/recursos/js/ajax/Empresa.js"></script>
         <script src="../visao/recursos/js/ajax/Configuracao.js"></script>
         
        <?php if(isset($_GET["codempresa"]) && $_GET["codempresa"] != NULL && $_GET["codempresa"] != ""){?>
        <script type="text/javascript" src="../visao/recursos/js/ajax/Pessoa.js"></script>
        <?php }?>
    </body>
</html>
