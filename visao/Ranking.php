<?php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('America/Sao_Paulo');
session_start();
include "../model/Conexao.php";
$conexao = new Conexao(); 
$sql = "select nivelpagina.*, pagina.nome as pagina, modulo.nome as modulo, pagina.link as pagina_link 
            from nivelpagina 
            inner join pagina on pagina.codpagina = nivelpagina.codpagina    
            inner join modulo on modulo.codmodulo = pagina.codmodulo
            where nivelpagina.codnivel = '{$_SESSION["codnivel"]}' {$andNivelPagina}";
$nivelp = $conexao->comandoArray($sql);
if (isset($_GET["codpessoa"]) && $_GET["codpessoa"] != NULL && trim($_GET["codpessoa"]) != "") {
    $pessoap = $conexao->comandoArray("select * from pessoa where codpessoa = '{$_GET["codpessoa"]}' and codempresa = '{$_SESSION['codempresa']}'");
    $action = "../control/AtualizarPessoa.php";
} else {
    $action = "../control/InserirCliente.php";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?=$sitep["nome"]?> | Ranking de Vendas</title>
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
                    <h1>Ranking de Vendas</h1>
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
                            <li><a href="#tabs-1">Procurar</a></li>
                        </ul>   
                        <div id="tabs-1">
                            <?php include("formProcurarRanking.php"); ?>
                        </div>
                        <span style="float: right; color: grey;width: 100%;text-align: right;">@ Sites e Sistemas PG</span>                            
                    </div>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php"?>
            
        </div><!-- ./wrapper -->

         <?php include './scriptPadrao.php';?>
        <script type="text/javascript" src="../visao/recursos/js/ajax/Ranking.js"></script>
        
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
