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
            where nivelpagina.codnivel = '{$_SESSION["codnivel"]}' and pagina.link = 'Tabela.php'";
$nivelp = $conexao->comandoArray($sql);
if (isset($_GET["codtabela"]) && $_GET["codtabela"] != NULL && trim($_GET["codtabela"]) != "") {
    $tabela = $conexao->comandoArray("select * from tabela where codtabela = '{$_GET["codtabela"]}' and codempresa = {$_SESSION["codempresa"]}");
    $titulo = "Alterar Tabela";
} else {
    $titulo = "Cadastrar Tabela";
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
                             <!--<li><a href="#tabs-3">Importar</a></li>-->       
                             <li><a href="#tabs-4">Comissão Nivel</a></li>       
                        </ul>   
                        <div id="tabs-1">
                            <?php include("formTabela.php"); ?>
                        </div>
                        <?php if ($nivelp["procurar"] == 1) { ?>
                            <div id="tabs-2">
                                <?php include("formProcurarTabela.php"); ?>
                            </div>
                        <?php } ?>   
<!--                        <div id="tabs-3">
                            <?php //include("formImportarTabela.php"); ?>
                        </div>  -->
                        <div id="tabs-4">
                                <?php include './formPctNivel.php';?>
                        </div>                         
                        <span style="float: right; color: grey;width: 100%;text-align: right;">@ Sites e Sistemas PG</span>                            
                    </div>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php" ?>
        </div><!-- ./wrapper -->

         <?php include './scriptPadrao.php';?> 
        <script type="text/javascript" src="../visao/recursos/js/ajax/Tabela.js"></script>
        <script type="text/javascript" src="../visao/recursos/js/ajax/TabelaPrazo.js"></script>        
        <script src="../visao/recursos/js/ajax/PctNivel.js"></script>
        <script src="../visao/recursos/js/jquery.form.min.js"></script>
  
    </body>
</html>
