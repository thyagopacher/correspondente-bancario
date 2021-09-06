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
            where nivelpagina.codnivel = '{$_SESSION["codnivel"]}' and nivelpagina.codpagina = 69";
$nivelp = $conexao->comandoArray($sql);
$titulo = "Consulta Beneficio";
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
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    
                                </div><!-- /.box-header -->
                                <!-- form start -->
                                <form role="form" id="fconsulta" name="fconsulta" method="post" onsubmit="return false;">
                                    <input type="hidden" name="codcomunicado" id="codcomunicado" value="<?=$_GET["codcomunicado"]?>"/>
                                    <div class="box-body">
                   
                                        <div class="form-group">
                                            <label for="localnascimento">CPF</label>
                                            <input type='text' class="form-control" name="cpf" id="cpf" placeholder="Digite cpf">
                                        </div>
                                    </div><!-- /.box-body -->
                                    <div class="box-footer">
                                        <button class="btn btn-primary" onclick="ConsultaCpfInss()">Consulta CPF</button>
                                    </div>
                                </form>
                            </div><!-- /.box -->
                        </div><!--/.col (left) -->
                    <!--/.col (right) -->
                    </div>   <!-- /.row -->
                    <div class="row">
                        <div id="listagemBeneficio" class="col-sm-12"></div>
                    </div>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php"?>

            
        </div><!-- ./wrapper -->

        <?php include './scriptPadrao.php';?>
        <script src="../visao/recursos/js/ajax/BeneficioCliente.js" type="text/javascript"></script>
        
    </body>
</html>
