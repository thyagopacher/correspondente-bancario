<?php
include "validacaoLogin.php";
$titulo = "Importação de Clientes";
$action = "../control/Importacao.php";
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
                                <form role="form" action="<?=$action?>" id="fimportacao" name="fimportacao" method="post">
                                    <input type="hidden" name="layout" id="layout" value="1"/>
                                    <div class="box-body">
                                        
                   
                                        <div class="form-group">
                                            <label for="localnascimento">Nome</label>
                                            <input type='text' class="form-control" name="nome" id="nome" placeholder="Digite nome da importação">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="exampleInputFile">Arquivo</label>
                                            <input type="file" id="arquivo" name="arquivo">
                                            <p class="help-block">Escolha arquivo da importação</p>
                                        </div>                                        

                                    </div><!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                    </div>
                                </form>
                            </div><!-- /.box -->
                        </div><!--/.col (left) -->
                    <!--/.col (right) -->
                    </div>   <!-- /.row -->
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php"?>

        </div><!-- ./wrapper -->

        <?php include './scriptPadrao.php';?>
        <script src="../visao/recursos/js/jquery.form.min.js"></script>
        <script src="../visao/recursos/js/ajax/Importacao.js"></script>
        
    </body>
</html>
