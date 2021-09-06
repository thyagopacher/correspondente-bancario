<?php
include "validacaoLogin.php";
if (isset($_GET["codpessoa"]) && $_GET["codpessoa"] != NULL && trim($_GET["codpessoa"]) != "") {
    $titulo = "Alterar um Frase";
} else {
    $titulo = "Cadastrar um Frase";
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
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6">
                            <!-- general form elements -->
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    
                                </div><!-- /.box-header -->
                                <!-- form start -->
                                <form role="form" method="post" name="ffrase" id="ffrase">
                                    <input type="hidden" name="codfrase" id="codfrase" value="<?php if(isset($frase["codfrase"])){echo $frase["codfrase"];}?>"/>
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="nome">Chave</label>
                                            <select class="form-control" name="chave" id="chave">
                                                <option value="">--Selecione--</option>
                                                <option value="[data]">Data</option>
                                                <option value="[nome_colaborador]">Colaborador</option>
                                                <option value="[meta_colaborador]">Meta</option>                 
                                                <option value="[meta_falta]" title="O que falta para fechar a meta do mês">Meta Falta</option>                 
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="statusPessoa">Texto</label>
                                            <textarea class="form-control" placeholder="Digite nome" name="texto" id="textoFrase" cols="70" rows="10"></textarea>
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
        <script type="text/javascript" src="../visao/recursos/js/Editor2.js"></script>
        <script type="text/javascript" src="../visao/recursos/js/ajax/Frase.js"></script>
    </body>
</html>
