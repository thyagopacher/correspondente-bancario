<?php
include "validacaoLogin.php";
if (isset($_GET["codpessoa"]) && $_GET["codpessoa"] != NULL && trim($_GET["codpessoa"]) != "") {
    $titulo = "Alterar um Funcionário";
    $pessoap = $conexao->comandoArray("select * 
    from pessoa where codpessoa = '{$_GET["codpessoa"]}' 
    and codempresa = '{$_SESSION['codempresa']}'");
    $action = "../control/AtualizarPessoa.php";
} else {
    $titulo = "Cadastrar um Funcionario";
    $action = "../control/InserirPessoa.php";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?=$sitep["nome"]?> | Cadastro de usuários</title>
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
                            <?php include("formPessoa.php"); ?>
                        </div>
                        <?php if ($nivelp["procurar"] == 1) { ?>
                            <div id="tabs-2">
                                <?php include("formProcurarPessoa.php"); ?>
                            </div>
                        <?php } ?>
                        
                        <span style="float: right; color: grey;width: 100%;text-align: right;">@ Sites e Sistemas PG</span>                            
                    </div>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php"?>
            
        </div><!-- ./wrapper -->

         <?php include './scriptPadrao.php';?>
        <script type="text/javascript" src="/visao/recursos/js/ajax/Pessoa.js"></script>

        <?php if(isset($_GET['procurar']) && $_GET['procurar'] != NULL && $_GET['procurar'] == "1"){?>
        <script>
            $("#tabs").tabs({
                active: 1
            });
            procurarPessoa2(true);
        </script>        
        <?php } ?>       
    </body>
</html>
