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
if (isset($_GET["codespecie"]) && $_GET["codespecie"] != NULL && trim($_GET["codespecie"]) != "") {
    $titulo = "Alterar um Envio SMS";
    $action = "../control/AtualizarEspecie.php";
} else {
    $titulo = "Cadastrar um Envio SMS";
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
                                <form role="form" autocomplete="off" id="fenvioSMS" name="fenvioSMS" method="post" onsubmit="return false;">
                                    <input type="hidden" name="codachado" id="codsmspadrao"  value="<?php if(isset($sms["codsmspadrao"])){echo $sms["codsmspadrao"];}else{ echo "";}?>"/>      
                                    <div class="box-body">
                   
                                        <div class="form-group">
                                            <label for="localnascimento">Modelo</label>
                                            <select name="modelo" id="modelo" class="form-control">
                                                <?php
                                                $ressmspadrao = $conexao->comando("select * from smspadrao where codempresa = '{$_SESSION['codempresa']}' order by texto");
                                                $qtdsmspadrao = $conexao->qtdResultado($ressmspadrao);
                                                if($qtdsmspadrao > 0){
                                                    while($smspadrao = $conexao->resultadoArray($ressmspadrao)){
                                                        echo '<option>',$smspadrao["texto"],'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="exampleInputFile">Número</label>
                                            <input class="form-control" type="text" name="numbers" id="numbers" class="telefone" value=""/>
                                        </div>                                        
                                        
                                        <div class="form-group">
                                            <label for="exampleInputFile">Texto</label>
                                            <textarea class="form-control" name="msg" id="msg"></textarea>
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
        <script src="../visao/recursos/js/ajax/EnvioSMS.js"></script>
        
    </body>
</html>
