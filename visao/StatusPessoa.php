<?php
include "validacaoLogin.php";
if (isset($_GET["codstatus"]) && $_GET["codstatus"] != NULL && trim($_GET["codstatus"]) != "") {
    $titulo = "Alterar um Especie";
    $action = "../control/AtualizarEspecie.php";
} else {
    $titulo = "Cadastrar um Especie";
    $action = "../control/InserirEspecie.php";
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
                                <form role="form" action="<?=$action?>" id="fstatuspessoa" name="fstatuspessoa" method="post" onsubmit="return false;">
                                    <input type="hidden" name="codstatus" id="codigoStatus" value="<?=$_GET["codstatus"]?>"/>
                                    <div class="box-body">
                   
                                        <div class="form-group">
                                            <label for="localnascimento">Nome</label>
                                            <input type="text" name="nome" id="nomeStatus" size="50" maxlength="250" value="<?php if(isset($status["nome"])){echo $status["nome"];}else { echo "";} ?>"/>
                                        </div>                                     

                                    </div><!-- /.box-body -->
                                    <div class="box-footer">
                                        <?php 
                                        if(!isset($_GET["codstatus"]) || $_GET["codstatus"] == NULL || $_GET["codstatus"] == ""){
                                            $displayBt = "display: none";
                                        }else{
                                            $displayBt = '';
                                        }
                                         ?>
                                        <button onclick="inserirStatus()" id="btinserirStatusPessoa">Cadastrar</button>
                                        <button style="<?=$displayBt?>" id="btatualizarStatusPessoa" onclick="atualizarStatus()">Atualizar</button>
                                        <button style="<?=$displayBt?>" id="btexcluirStatusPessoa" onclick="excluirStatus()">Excluir</button>   
                                        <button onclick="btNovoStatus()">Novo</button>
                                    </div>
                                </form>
                                <div id="listagemStatusPessoa"></div>
                            </div><!-- /.box -->
                        </div><!--/.col (left) -->
                    <!--/.col (right) -->
                    </div>   <!-- /.row -->
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php"?>
        </div><!-- ./wrapper -->

         <?php include './scriptPadrao.php';?>
        <script src="../visao/recursos/js/ajax/StatusPessoa.js"></script>
    </body>
</html>
