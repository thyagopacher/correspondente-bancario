<?php
include "validacaoLogin.php";  
    if (isset($_GET["codnivel"])) {
        $sql = "select * from nivel where codnivel = '{$_GET["codnivel"]}'";
        $nivel = $conexao->comandoArray($sql);
        $titulo   = "Alterar um nivel";
    }else{
        $titulo = "Cadastrar um nivel";
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
                            <li><a href="#tabs-3">Página</a></li>
                            <li><a href="#tabs-4">Proc. Página</a></li>
                            <li><a href="#tabs-5">Módulo</a></li>
                            <li><a href="#tabs-6">Perfil Acesso</a></li>
                        </ul>   
                        <div id="tabs-1">
                            <?php include("formNivel.php"); ?>
                        </div>
                        <?php if ($nivelp["procurar"] == 1) { ?>
                            <div id="tabs-2">
                                <?php include("formProcurarNivel.php"); ?>
                            </div>
                        <?php } ?>
                        <div id="tabs-3">
                            <?php include("formPagina.php");?>
                        </div>
                        <div id="tabs-4">
                            <?php include("formProcurarPagina.php");?>
                        </div>
                        <div id="tabs-5">
                            <?php include("formModulo.php");?>
                        </div>
                        <div id="tabs-6">
                            <?php include("formPerfilAcesso.php");?>
                        </div>                        
                        <span style="float: right; color: grey;width: 100%;text-align: right;">@ Sites e Sistemas PG</span>                            
                    </div>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php"?>
        </div><!-- ./wrapper -->

        <?php include './scriptPadrao.php';?>
        <script src="/visao/recursos/js/jquery.form.js"></script>        
   
        <script src="../visao/recursos/js/ajax/Nivel.js"></script>
        <script src="../visao/recursos/js/ajax/Modulo.js"></script>
        <script src="../visao/recursos/js/ajax/Pagina.js"></script>

    </body>
</html>
