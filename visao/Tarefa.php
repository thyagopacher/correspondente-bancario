<?php
include "validacaoLogin.php";
if (isset($_GET["codtarefa"]) && $_GET["codtarefa"] != NULL && trim($_GET["codtarefa"]) != "") {
    $titulo = "Alterar um Suporte";
    $action = "../control/AtualizarEmpresa.php";
} else {
    $titulo = "Cadastrar um Suporte";
    $action = "../control/InserirEmpresa.php";
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
                                <form role="form">
                                    <input type="hidden" name="codtarefa" id="codtarefa" value="<?=$_GET["codtarefa"]?>"/>
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="nome">Localização</label>
                                            <input type='text' class="form-control" name="localizacao" id="localizacao" placeholder="Digite localização da tarefa">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="statusPessoa">Prioridade</label>
                                            <select class="form-control" name="prioridade" id="prioridade" title="Escolha aqui prioridade da tarefa">
                                                <option value="">--Selecione--</option>
                                                <option title="ta tudo parado mas o povo aguenta esperar" value="g">Grande</option>
                                                <option value="m">Média</option>
                                                <option value="p">Pequena</option>
                                                <option title="Senão for feito hoje ta tudo acabado" value="u">Urgente</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="exampleInputFile">Arquivo</label>
                                            <input type="file" id="exampleInputFile">
                                            <p class="help-block">Escolha image, ou arquivo que possa esclarecer o atendimento</p>
                                        </div>                                        
                                        
                                        <div class="form-group">
                                            <label for="localnascimento">Descrição</label>
                                            <textarea class="form-control" name="descricao" id="descricao" placeholder="Digite aqui a descrição"></textarea>
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
        <script src="../visao/recursos/js/ajax/Empresa.js"></script>
    </body>
</html>
