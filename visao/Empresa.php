<?php
include "validacaoLogin.php";
if(isset($_GET["codempresa"]) && $_GET["codempresa"] != NULL && $_GET["codempresa"] != ""){
    $empresaf = $conexao->comandoArray("select * from empresa where codempresa = '{$_GET["codempresa"]}'");
    $action = "../control/AtualizarEmpresa.php";
}else{
    $action = "../control/InserirEmpresa.php";
}
if (!isset($_GET["tipo"]) && isset($_GET["codramo"])) {
    $filial = "s";
    $titulo = "Cadastro de filial";
}elseif(isset($_GET["tipo"]) && isset($_GET["codramo"])) {
    $correspondente = "s";
    $titulo = "Cadastro de correspondente";
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
                            <?php if(isset($_GET["codempresa"]) && $_GET["codempresa"] != NULL && $_GET["codempresa"] != ""){?>
                            <li><a href="#tabs-3">Cad. Administrador</a></li>
                            <?php } ?>
                        </ul>   
                        <div id="tabs-1">
                            <?php include "formEmpresa.php";?>
                        </div>
                        <?php if ($nivelp["procurar"] == 1) { ?>
                            <div id="tabs-2">
                                <?php include("formProcurarEmpresa.php"); ?>
                            </div>
                        <?php } ?>    
                        <?php if(isset($_GET["codempresa"]) && $_GET["codempresa"] != NULL && $_GET["codempresa"] != ""){?>
                        <div id="tabs-3">
                            <?php 

                            $action = "../control/InserirPessoa.php";
                            include("formPessoa.php");
                            ?>
                        </div>
                        <?php }?>                        
                        <span style="float: right; color: grey;width: 100%;text-align: right;">@ Sites e Sistemas PG</span>                            
                    </div>                    
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php"?>

        </div><!-- ./wrapper -->

        <?php include './scriptPadrao.php';?>
        <script src="../visao/recursos/js/jquery.form.min.js"></script>
        <script src="../visao/recursos/js/ajax/Empresa.js"></script>
        <?php if(isset($_GET["cadAdmin"]) && $_GET["cadAdmin"] != NULL && $_GET["cadAdmin"] == "s"){?>
        <script>
            $("#tabs").tabs({ 
                active: 2
            });
        </script>          
        <?php }?>
        <script src="../visao/recursos/js/ajax/Configuracao.js"></script>
        <?php if(isset($_GET["codempresa"]) && $_GET["codempresa"] != NULL && $_GET["codempresa"] != ""){?>
        <script type="text/javascript" src="../visao/recursos/js/ajax/Pessoa.js"></script>
        <?php }?>
    </body>
</html>  
