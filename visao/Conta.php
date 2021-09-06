<?php
include "validacaoLogin.php";   
    if (isset($_GET["codconta"])) {
        $sql    = "select conta.*, DATE_FORMAT(conta.dtpagamento, '%d/%m/%Y') as dtpagamento2 from conta where codconta = '{$_GET["codconta"]}'";
        $contap  = $conexao->comandoArray($sql);
        $titulo = "Alterar uma conta";
        $action = "../control/AtualizarConta.php";
    } else { 
        $titulo = "Cadastrar uma conta";
        $action = "../control/InserirConta.php";
    }
if(!isset($_GET["movimentacao"]) && isset($conta) && $conta["movimentacao"] != NULL && $conta["movimentacao"] != ""){
    $_GET["movimentacao"] = $conta["movimentacao"];
}
if(isset($_GET["movimentacao"])){
    if($_GET["movimentacao"] == "R"){
        $titulo .= " a receber";
        $andNivelPagina = " and nivelpagina.codpagina = 6";
    }elseif($_GET["movimentacao"] == "D"){
        $titulo .= " a pagar";
        $andNivelPagina = " and nivelpagina.codpagina = 7";
    }
}elseif(isset($_GET["master"]) && $_GET["master"] == "true"){
    $titulo .= " de filiais";
    $andNivelPagina = " and nivelpagina.codpagina = 62";
}      
$nivelp = $conexao->comandoArray("select * from nivelpagina where codnivel = '{$_SESSION["codnivel"]}' {$and}");
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
                           <?php if($nivelp["procurar"] == 1){?>
                           <li><a href="#tabs-2">Procurar</a></li>
                           <?php }?>
                           <li><a href="#tabs-3">Tipo Conta</a></li>
                       </ul>
                       <div id="tabs-1">
                           <?php include("formConta.php");?>
                       </div>
                       <div id="tabs-2">
                           <?php 
                           if($nivelp["procurar"] == 1){
                               include("formProcurarConta.php");
                           }
                           ?>
                       </div>
                       <div id="tabs-3">
                           <?php include("formTipoConta.php");?>
                       </div>
                   </div>   <!-- /.row -->
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php"?>
            
        </div><!-- ./wrapper -->

        <?php include './scriptPadrao.php';?>
        <script type="text/javascript" src="../visao/recursos/js/jquery.form.min.js"></script>
        <script type="text/javascript" src="../visao/recursos/js/ajax/Conta.js"></script>
        
        <script type="text/javascript" src="../visao/recursos/js/ajax/TipoConta.js"></script>        
  
    </body>
</html>
