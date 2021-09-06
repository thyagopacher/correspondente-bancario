<?php
include "validacaoLogin.php";

$sql = 'select consultade from configuracao where codempresa = ' . $_SESSION["codempresa"];
$configuracaop = $conexao->comandoArray($sql);
$pessoap = $conexao->comandoArray("select primeiroacesso from pessoa where codpessoa = {$_SESSION["codpessoa"]} and codempresa = {$_SESSION["codempresa"]}");
?>
<!DOCTYPE html>
<html manifest="/visao/site.manifest">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?=$sitep["nome"]?> | Dashboard</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="/visao/recursos/css/bootstrap.min.css">

        <link rel="stylesheet" href="/visao/recursos/css/popup.css">

        <link rel="stylesheet" href="/visao/recursos/css/instalador.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="/visao/recursos/css/font-awesome.min.css">

        <!-- Theme style -->
        <link rel="stylesheet" href="/visao/recursos/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. --> 
        <link rel="stylesheet" href="/visao/recursos/css/skins/_all-skins.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="/visao/plugins/iCheck/flat/blue.css">
        <!-- Morris chart -->
        <link rel="stylesheet" href="/visao/plugins/morris/morris.css">
        <!-- jvectormap -->
        <link rel="stylesheet" href="/visao/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
        <!-- Date Picker -->
        <link rel="stylesheet" href="/visao/plugins/datepicker/datepicker3.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="/visao/plugins/daterangepicker/daterangepicker-bs3.css">
        <!-- bootstrap wysihtml5 - text editor -->
        <link rel="stylesheet" href="/visao/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="/visao/recursos/js/html5shiv.min.js"></script>
            <script src="/visao/recursos/js/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript" src="/visao/recursos/js/loader.js"></script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            <?php
            include "header.php";
            include "menu.php";
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Dashboard
                        <small>Painel de controle</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Dashboard</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <?php
                                    if ($_SESSION["codnivel"] != "19") {
                                        $and = " and funcionario.codpessoa = {$_SESSION['codpessoa']}";
                                    }
                                    $sql = "select count(distinct(cliente.codpessoa)) as qtd
                                        from agenda 
                                        inner join pessoa as cliente on cliente.codpessoa = agenda.codpessoa and cliente.codempresa = agenda.codempresa
                                        inner join pessoa as funcionario on funcionario.codpessoa = agenda.codfuncionario and funcionario.codempresa = agenda.codempresa
                                        left join atendimento 
                                            on  atendimento.codpessoa   = cliente.codpessoa 
                                            and atendimento.codcarteira = agenda.codcarteira
                                            and atendimento.codempresa  = agenda.codempresa
                                        where agenda.codempresa = '{$_SESSION['codempresa']}' 
                                        and funcionario.status <> 'i'
                                        $and   
                                        and cliente.codcategoria in(1,6)    
                                        and cliente.codstatus not in (1,2,6,8,13)      
                                        and dtagenda >= '" . date('Y-m-d') . " 00:00:00' and dtagenda <= '" . date('Y-m-d') . " 23:59:01'    
                                        and cliente.codstatus <> '1'  
                                        and agenda.atendido = 'n' 
                                        and atendimento.codatendimento is null
                                        order by agenda.dtagenda asc";
                                    $qtdAgenda = $conexao->comandoArray($sql);
                                    if (isset($qtdAgenda["qtd"]) && $qtdAgenda["qtd"] != NULL && $qtdAgenda["qtd"] != "") {
                                        echo '<h3>', $qtdAgenda["qtd"], '</h3>';
                                    }
                                    echo '<p>Quantidade retornos</p>';
                                    ?>                                         
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="Cliente.php?callcenter=true&agendamentos=true" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <?php
                                    $valorBaixaMesEscrito = $cache->read('valorBaixaMes_' . $_SESSION['codempresa'] . '_' . $_SESSION["codpessoa"]);
                                    if (!isset($valorBaixaMesEscrito) || $valorBaixaMesEscrito == NULL) {
                                        if ($_SESSION["codnivel"] != 19) {
                                            $and = " and codfuncionario = {$_SESSION['codpessoa']}";
                                        }
                                        $sql = "select sum(valor) as valor from baixa 
                                        where codempresa = '{$_SESSION["codempresa"]}' 
                                        and dtcadastro >= '" . date("Y-m-") . "01' and dtcadastro <= '" . date("Y-m-") . "31'    
                                        {$and}";
                                        $valorBaixaMes = $conexao->comandoArray($sql);
                                        if (isset($valorBaixaMes["valor"]) && $valorBaixaMes["valor"] != NULL && $valorBaixaMes["valor"] > 0) {
                                            $valorBaixaMesEscrito .= '<h3><sup style="font-size: 20px">R$ </sup>' . number_format($valorBaixaMes["valor"], 2, ",", ".") . '</h3>';
                                        } else {
                                            $valorBaixaMesEscrito .= '<h3><sup style="font-size: 20px">R$ </sup>0,00</h3>';
                                        }
                                        $cache->save('valorBaixaMes_' . $_SESSION['codempresa'] . '_' . $_SESSION["codpessoa"], $valorBaixaMesEscrito, '2 minutes');
                                    }
                                    echo $valorBaixaMesEscrito;
                                    echo '<p>Produção mês</p>';
                                    ?>                                    
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="Baixa.php?procurar=1" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <?php
                                    $valorMeta = $cache->read('valorMeta_' . $_SESSION['codempresa'] . '_' . $_SESSION["codpessoa"]);
                                    if (!isset($valorMeta) || $valorMeta == NULL) {
                                        $valorMeta = calculaMetaFuncionario();
                                        $cache->save('valorMeta_' . $_SESSION['codempresa'] . '_' . $_SESSION["codpessoa"], $valorMeta, '2 minutes');
                                    }
                                    echo '<h3> R$ ', number_format($valorMeta, 2, ',', '.'), '</h3>';
                                    echo '<p>Meta do dia</p>';
                                    ?>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="Pessoa.php?procurar=1" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>
                                        <?php
                                        $res = medalhaFuncionario();
                                        if ($res != NULL && $res != "") {
                                            echo $res . "º";
                                        } else {
                                            echo "Veja";
                                        }
                                        ?>
                                    </h3>
                                    <p>Ranking Funcionários</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="Ranking.php" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div><!-- ./col -->
                    </div><!-- /.row -->
                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-lg-7 connectedSortable">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="nav-tabs-custom">
                                <!-- Tabs within a box -->
                                <ul class="nav nav-tabs pull-right">
                                    <li><a href="#grafico_mes" data-toggle="tab">Mês X Meta</a></li>
                                    <li class="active"><a href="#sales-chart" data-toggle="tab">Dia X Meta</a></li>
                                    <li class="pull-left header"><i class="fa fa-inbox"></i> Produção X Meta</li>
                                </ul>
                                <div class="tab-content no-padding">
                                    <!-- Morris chart - Sales -->
                                    <div class="chart tab-pane" id="grafico_mes" style="width: 100%;height: 300px;"></div>
                                    <div class="chart tab-pane active" id="sales-chart" style="height: 300px;"></div>
                                </div>
                            </div><!-- /.nav-tabs-custom -->

                            <div class="box box-solid">
                                <div class="box-header">
                                    <i class="ion ion-clipboard"></i>
                                    <h3 class="box-title">Esteira de Propostas</h3>
                                </div><!-- /.box-header -->
                                <div class="box">
                                    <ul id="esteira_inicial" class="todo-list"></ul>
                                </div>

                            </div><!-- /.box -->                            

                            <!-- quick email widget -->
                            <div class="box box-info">
                                <div class="box-header">
                                    <i class="fa fa-envelope"></i>
                                    <h3 class="box-title">Consulta DETALHAMENTO</h3>
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
                                    </div><!-- /. tools -->
                                </div>
                                <div class="box-body">
                                    <form action="#" method="post">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="cpf" id="cpf" placeholder="Digite CPF">
                                        </div>
                                        <div class="form-group">
                                            <input type='text' class="form-control" name="beneficio" id="beneficio" placeholder="Digite Beneficio">
                                        </div>
                                    </form>
                                </div>
                                <div class="box-footer clearfix">
                                    <?php
                                    if (isset($configuracaop["consultade"]) && $configuracaop["consultade"] != NULL && $configuracaop["consultade"] == '1') {
                                        $metodoConsulta = 'consultaCpfBeneficioMulti()';
                                    } elseif (isset($configuracaop["consultade"]) && $configuracaop["consultade"] != NULL && $configuracaop["consultade"] == '2') {
                                        $metodoConsulta = 'consultaCpfBeneficioAnaliseInfo()';
                                    } else {
                                        $metodoConsulta = 'consultaCpfBeneficio()';
                                    }
                                    ?>
                                    <button class="pull-right btn btn-default" id="sendEmail" onclick="javascript: <?= $metodoConsulta ?>">Consultar <i class="fa fa-arrow-circle-right"></i></button>
                                </div>
                            </div>

                        </section><!-- /.Left col -->
                        <section class="col-lg-5 connectedSortable">
                            <div class="box box-solid bg-teal-gradient">
                                <div class="box-header">
                                    <i class="fa fa-th"></i>
                                    <h3 class="box-title">Comunicados</h3>
                                    <?php
                                    $comunicadoEscrito = $cache->read('comunicado_' . $_SESSION['codempresa']);
                                    if (!isset($comunicadoEscrito) || $comunicadoEscrito == NULL) {
                                        $rescomunicado = $conexao->comando('select DATE_FORMAT(dtcadastro, "%d/%m/%Y") as dtcadastro2, nome, codcomunicado, arquivo 
                                            from comunicado where codcomunicado in(
                                            select comunicado.codcomunicado
                                            from comunicado
                                            inner join empresa on empresa.codempresa = comunicado.codempresa
                                                where (empresa.codempresa = ' . $_SESSION["codempresa"] . ' or empresa.codpessoa in(select codpessoa from pessoa where codempresa = ' . $_SESSION["codempresa"] . '))
                                                )');
                                        $qtdcomunicado = $conexao->qtdResultado($rescomunicado);
                                        if ($qtdcomunicado > 0) {
                                            $comunicadoEscrito .= '<ul>';
                                            while ($comunicado = $conexao->resultadoArray($rescomunicado)) {
                                                $comunicadoEscrito .= '<li><a target="_blank" href="/arquivos/' . $comunicado["arquivo"] . '">' . $comunicado["dtcadastro2"] . ' - ' . $comunicado["nome"] . '</a></li>';
                                            }
                                            $comunicadoEscrito .= '</ul>';
                                        }
                                        $cache->save('comunicado_' . $_SESSION['codempresa'], $comunicadoEscrito, '1 minutes');
                                    }
                                    echo $comunicadoEscrito;
                                    ?>
                                    <div class="box-tools pull-right">
                                        <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body border-radius-none">
                                    <div class="chart" id="line-chart" style="height: 250px;"></div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </section><!-- right col -->
                        <section class="col-lg-5 connectedSortable">
                            <div class="box box-solid bg-teal-gradient">
                                <div class="box-header">
                                    <i class="fa fa-th"></i>
                                    <h3 class="box-title">Roteiros</h3>
                                    <?php
                                    $roteiroEscrito = $cache->read('roteiro_' . $_SESSION['codempresa']);
                                    if (!isset($roteiroEscrito) || $roteiroEscrito == NULL) {
                                        $sql = 'select banco.nome, 
                                        banco.codbanco from banco 
                                        where codbanco in(
                                            select manual.codbanco 
                                                from manual
                                                inner join empresa on empresa.codempresa = manual.codempresa
                                                where (empresa.codempresa = ' . $_SESSION["codempresa"] . ' or empresa.codpessoa in(select codpessoa from pessoa where codempresa = ' . $_SESSION["codempresa"] . '))
                                            )';
                                        $reslink = $conexao->comando($sql)or die("<pre>$sql</pre>");
                                        $qtdlink = $conexao->qtdResultado($reslink);
                                        if ($qtdlink > 0) {
                                            $roteiroEscrito .= '<ul>';
                                            while ($link = $conexao->resultadoArray($reslink)) {
                                                $roteiroEscrito .= '<li>';
                                                $roteiroEscrito .= $link["nome"];
                                                $reslink2 = $conexao->comando('select DATE_FORMAT(dtcadastro, "%d/%m/%Y") as dtcadastro2, nome, arquivo from manual 
                                                    where codbanco = ' . $link["codbanco"] . ' and codempresa = ' . $_SESSION["codempresa"]);
                                                $qtdlink2 = $conexao->qtdResultado($reslink2);
                                                if ($qtdlink2 > 0) {
                                                    $roteiroEscrito .= '<ul>';
                                                    while ($link2 = $conexao->resultadoArray($reslink2)) {
                                                        $roteiroEscrito .= '<li><a target="_blank" href="/arquivos/' . $link2["arquivo"] . '">' . $link2["dtcadastro2"] . ' - ' . $link2["nome"] . '</a></li>';
                                                    }
                                                    $roteiroEscrito .= '</ul>';
                                                }
                                                $roteiroEscrito .= '</li>';
                                            }
                                            $roteiroEscrito .= '</ul>';
                                        }
                                        $cache->save('roteiro_' . $_SESSION['codempresa'], $roteiroEscrito, '1 minutes');
                                    }
                                    echo $roteiroEscrito;
                                    ?>                                    
                                    <div class="box-tools pull-right">
                                        <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body border-radius-none">
                                    <div class="chart" id="line-chart" style="height: 250px;"></div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </section><!-- right col -->
                        <section class="col-lg-5 connectedSortable">
                            <div class="box box-solid bg-teal-gradient">
                                <div class="box-header">
                                    <i class="fa fa-th"></i>
                                    <h3 class="box-title">Links</h3>
                                    <?php
                                    $sql = 'select DATE_FORMAT(dtcadastro, "%d/%m/%Y") as dtcadastro2, nome, link
                                    from link where codlink in(
                                        select link.codlink
                                        from link
                                      inner join empresa on empresa.codempresa = link.codempresa
                                            where (empresa.codempresa = ' . $_SESSION["codempresa"] . ' or empresa.codpessoa in(select codpessoa from pessoa where codempresa = ' . $_SESSION["codempresa"] . '))
                                         )
                                     order by nome';
                                    $reslink = $conexao->comando($sql)or die("<pre>$sql</pre>");
                                    $qtdlink = $conexao->qtdResultado($reslink);
                                    if ($qtdlink > 0) {
                                        echo '<ul>';
                                        while ($link = $conexao->resultadoArray($reslink)) {
                                            echo '<li><a target="_blank" href="', $link["link"], '">', $link["dtcadastro2"], ' - ', $link["nome"], '</a></li>';
                                        }
                                        echo '</ul>';
                                    }
                                    ?>                                     
                                    <div class="box-tools pull-right">
                                        <button class="btn bg-teal btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button class="btn bg-teal btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body border-radius-none">
                                    <div class="chart" id="line-chart" style="height: 250px;"></div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </section><!-- right col -->
                    </div><!-- /.row (main row) -->

                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php"; ?>

            <div class="control-sidebar-bg"></div>
        </div><!-- ./wrapper -->

        <!-- jQuery 2.1.4 -->
        <script type="text/javascript" src="/visao/recursos/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="/visao/recursos/js/jquery.form.js"></script>
        <script src="/visao/recursos/js/instalador.js"></script>

        <!-- jQuery UI 1.11.4 -->
        <script src="/visao/recursos/js/jquery-ui.min.js"></script> 

        <script src="/visao/recursos/js/tinybox.min.js"></script>
        <?php
        if ($pessoap["primeiroacesso"] === "s") {
            ?>
            <script>
                                            function abreInstalador() {
                                                TINY.box.show({url: '../visao/instalador/instalador1.php', width: 800, height: 400, opacity: 20, topsplit: 3});
                                            }
                                            abreInstalador();
            </script>

            <?php
        }
        ?>

        <script src="/visao/recursos/js/ajax/BeneficioCliente.js?123456" charset="utf-8"></script>
        <script src="/visao/recursos/js/ajax/Agenda.js" charset="utf-8"></script>
        <script src="/visao/recursos/js/ajax/Comunicado.js" charset="utf-8"></script>
        <script src="/visao/recursos/js/ajax/Proposta.js" charset="utf-8"></script> 
        <script src="/visao/recursos/js/chat.js" charset="utf-8"></script>

        <script src="/visao/recursos/js/Geral.js" charset="utf-8"></script>

        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
                                        $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.5 -->
        <script src="/visao/recursos/js/bootstrap.min.js"></script>
        <!-- Morris.js charts -->
        <script src="/visao/recursos/js/raphael-min.js"></script>
        <script src="/visao/plugins/morris/morris.min.js"></script>
        <!-- Sparkline -->
        <script src="/visao/plugins/sparkline/jquery.sparkline.min.js"></script>
        <!-- jvectormap -->
        <script src="/visao/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="/visao/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <!-- jQuery Knob Chart -->
        <script src="/visao/plugins/knob/jquery.knob.js"></script>
        <!-- daterangepicker -->
        <script src="/visao/recursos/js/moment.min.js"></script>
        <script src="/visao/plugins/daterangepicker/daterangepicker.js"></script>
        <!-- datepicker -->
        <script src="/visao/plugins/datepicker/bootstrap-datepicker.js"></script>
        <script src="/visao/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="/visao/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <!-- Slimscroll -->
        <script src="/visao/plugins/slimScroll/jquery.slimscroll.min.js"></script>

        <!-- AdminLTE for demo purposes -->
        <script src="/visao/recursos/js/demo.js"></script>

    </body>
</html>
<?php

function medalhaFuncionario() {
    global $conexao;
    $jaRecebeu = FALSE;
    $mes = date("m");
    $and = " and b2.dtcadastro >= '" . date("Y") . '-' . $mes . "-01'";
    $and .= " and b2.dtcadastro <= '" . date("Y") . '-' . $mes . "-30'";
    $sql = "select distinct pessoa.codpessoa, pessoa.codempresa, pessoa.nome, pessoa.imagem,
        (select sum(b1.valor) as valor from baixa as b1 where b1.codempresa = b2.codempresa and b1.codfuncionario = b2.codfuncionario and b1.dtcadastro = b2.dtcadastro) as total_produzido    
        from baixa as b2
        inner join pessoa on pessoa.codpessoa = b2.codfuncionario
        inner join empresa on empresa.codempresa = b2.codempresa
        where 1 = 1 and b2.codempresa = {$_SESSION["codempresa"]}
        {$and} order by b2.valor desc";
    $res = $conexao->comando($sql)or die("<pre>$sql</pre>");
    $qtd = $conexao->qtdResultado($res);
    $ranking = array();
    if ($qtd > 0) {
        $linhaIteracao = 0;
        while ($baixa = $conexao->resultadoArray($res)) {
            $sql = "select sum(b2.valor) as valor from baixa as b2 where b2.codfuncionario = '{$baixa["codpessoa"]}' {$and}";
            $total = $conexao->comandoArray($sql);
            $ranking[$linhaIteracao]["codempresa"] = $baixa["codempresa"];
            $ranking[$linhaIteracao]["nome"] = $baixa["nome"];
            $ranking[$linhaIteracao]["codfuncionario"] = $baixa["codpessoa"];
            $ranking[$linhaIteracao]["valor"] = $total["valor"];
            $ranking[$linhaIteracao]["imagem"] = $baixa["imagem"];
            $linhaIteracao++;
        }
    } else {
        echo '';
    }

    usort($ranking, "cmpValor");
    if ($ranking[0]["codfuncionario"] == $_SESSION['codpessoa']) {
        return 1;
    } elseif ($ranking[1]["codfuncionario"] == $_SESSION['codpessoa']) {
        return 2;
    } elseif ($ranking[2]["codfuncionario"] == $_SESSION['codpessoa']) {
        return 3;
    } elseif ($ranking[3]["codfuncionario"] == $_SESSION['codpessoa']) {
        return 4;
    } elseif ($ranking[4]["codfuncionario"] == $_SESSION['codpessoa']) {
        return 5;
    }
}

function cmpValor($a, $b) {
    if ($a["valor"] == $b["valor"]) {
        return 0;
    }
    return ($a["valor"] > $b["valor"]) ? -1 : 1;
}

function calculaMetaFuncionario() {
    global $conexao;
    /*     * pegando o valor total da meta do funcionário */
    if ($_SESSION["codnivel"] != 19) {
        $and = " and codfuncionario = {$_SESSION['codpessoa']}";
    }
    $sql = "select sum(valor) as valor from metafuncionario where codempresa = '{$_SESSION['codempresa']}' 
    and dtcadastro >= '" . date("Y-m-") . "01 00:00:00'    
    and dtcadastro <= '" . date("Y-m-") . "30 23:59:59'  {$and}          
    order by codmeta desc";

    $metaFuncionario = $conexao->comandoArray($sql);
    $diasUteis = 0;

    if (isset($metaFuncionario) && $metaFuncionario["valor"] != NULL && $metaFuncionario["valor"] != "") {

        /*         * somatório valor total vendido */
        $ontem = date('Y-m-d', strtotime("-1 days"));
        $baixaTotal = $conexao->comandoArray("select sum(valor) as valor from baixa 
        where codempresa = '{$_SESSION['codempresa']}' 
        and dtcadastro >= '" . date("Y-m-") . "01'    
        and dtcadastro <= '" . $ontem . " 23:59:59'    
        {$and}");

        $ultimo_dia = date("t", mktime(0, 0, 0, date("m"), '01', date("Y")));
        $dia_mes = date("Y-m-");
        $semana = array(
            'Sun' => 'domingo',
            'Mon' => 'segunda',
            'Tue' => 'terca',
            'Wed' => 'quarta',
            'Thu' => 'quinta',
            'Fri' => 'sexta',
            'Sat' => 'sabado'
        );
        for ($i = date("d"); $i <= $ultimo_dia; $i++) {
            if ($i < 10) {
                $dia_mes2 = "0" . $i;
            } else {
                $dia_mes2 = $i;
            }
            $data_selec = $dia_mes . $dia_mes2;
            $sql = "select * from dia where data = '{$data_selec}' and codempresa = '{$_SESSION['codempresa']}'";
            $dia_feriado = $conexao->comandoArray($sql);
            if (isset($dia_feriado) && $dia_feriado["data"] != NULL && $dia_feriado["data"] != "") {
                continue; //tira os feriados
            }

            $dia_semana = date("D", strtotime($data_selec));
            if ($semana[$dia_semana] == "segunda" || $semana[$dia_semana] == "terca" || $semana[$dia_semana] == "quarta" || $semana[$dia_semana] == "quinta" || $semana[$dia_semana] == "sexta") {
                $diasUteis++;
            }
        }

        if ($diasUteis == 0) {
            $resultadoFinal = 0;
        } elseif (isset($baixaTotal["valor"]) && $baixaTotal["valor"] != NULL && $baixaTotal["valor"] > 0) {
            $resultadoFinal = ($metaFuncionario["valor"] - $baixaTotal["valor"]) / $diasUteis;
        } else {
            $resultadoFinal = $metaFuncionario["valor"] / $diasUteis;
        }
    }

    return $resultadoFinal;
}

if (isset($pessoap["primeiroacesso"]) && $pessoap["primeiroacesso"] != NULL && $pessoap["primeiroacesso"] == "s") {
    $sql = 'update pessoa set primeiroacesso = "n" where codpessoa = ' . $_SESSION["codpessoa"] . ' and codempresa = ' . $_SESSION["codempresa"];
    $conexao->comando($sql);
}
?> 