<?php
include "validacaoLogin.php";
$southconsultap = $conexao->comandoArray('select * from southconsulta as sc where sc.codempresa = '. $_SESSION["codempresa"]. ' order by codconsulta desc limit 1');

if(isset($southconsultap["validade"]) && $southconsultap["validade"] != NULL && $southconsultap["validade"] != ""){
    $diaMais        = date('Y-m-d', strtotime('+'.$southconsultap["validade"].' days', strtotime($southconsultap["dtcadastro"])));
    $time_inicial   = strtotime(date("Y-m-d"));
    $time_final     = strtotime($diaMais);
    $diferenca      = $time_final - $time_inicial; // 19522800 segundos
    $diasExpira     = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
    
    $diasMaisBrasil = date("d/m/Y", strtotime($diaMais));
}

//consultas usadas
$consultassouthp = $conexao->comandoArray('select count(1) as qtd from consultassouth as cs where cs.codempresa = '. $_SESSION["codempresa"]);
$limiteConsulta  = $southconsultap["qtdconsulta"] - $consultassouthp["qtd"];
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?=$sitep["nome"]?> | South Busca</title>
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
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3></h3>
                                    <p style="text-align: center;width: 50%;">
                                        Consultas disponiveis: <?=$southconsultap["qtdconsulta"]?>
                                    </p>   
                                    <p style="text-align: center;width: 50%;">
                                        Limite de Consultas: <?=$limiteConsulta?>
                                    </p>   
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="#" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>  
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3><?=$diasMaisBrasil?></h3>
                                    <p>Expira em <?=$diasExpira?> dias</p>                                
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="#" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>                        
                    </div>
                    <?php include("formSouthBusca.php"); ?>
                    <?php include("formSouthBusca2.php"); ?>
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php" ?>
        </div><!-- ./wrapper -->

         <?php include './scriptPadrao.php';?>
        <script type="text/javascript" src="../visao/recursos/js/ajax/ConsultasSouth.js"></script>
    </body>
</html>
