<?php
include "validacaoLogin.php";
$configuracaop = $conexao->comandoArray('select tempoocioso from configuracao where codempresa = '. $_SESSION["codempresa"]);

if (isset($_GET["codpessoa"]) && $_GET["codpessoa"] != NULL && trim($_GET["codpessoa"]) != "") {
    if (isset($empresap["codcategoria"]) && $empresap["codcategoria"] != NULL && $empresap["codcategoria"] != "2") {
        $andPessoa = " and (codempresa = {$_SESSION["codempresa"]} or codempresa in(select codempresa from empresa where codpessoa in(select codpessoa from pessoa where codempresa = {$_SESSION["codempresa"]})))";
    } elseif ($_SESSION["codnivel"] != 18 && $_SESSION["codnivel"] != 1) {
        $andPessoa = " and codempresa = '{$_SESSION['codempresa']}'";
    }
    /*quando tiver o pessoa por código*/
    $sql = "select *,
                YEAR(CURRENT_DATE) - YEAR(pessoa.dtnascimento) - (DATE_FORMAT(CURRENT_DATE, '%m%d') < DATE_FORMAT(pessoa.dtnascimento, '%m%d')) as idade  
        from pessoa where codpessoa = '{$_GET["codpessoa"]}' $andPessoa";
    $pessoap = $conexao->comandoArray($sql);
}

if (isset($_SESSION["codnivel"]) && $_SESSION["codnivel"] != NULL && $_SESSION["codnivel"] != "" && isset($_GET["callcenter"])) {
    if (!isset($_GET["novo"])) {
        if (!isset($_GET["codpessoa"]) || $_GET["codpessoa"] == NULL || $_GET["codpessoa"] == "") {
            /*primeiro procura na agenda*/
            $and .= ' and pessoa.dtcadastro >= "2016-03-01" and pessoa.dtcadastro <= "2099-03-01" ';
            $and .= ' and pessoa.status = "a" and pessoa.codstatus not in (1,2,6,8,13)  ';
            if (isset($_GET["callcenter"])) {
                $and .= ' and pessoa.codcategoria in(6)';
            } else {
                $and .= ' and pessoa.codcategoria in(1)';
            }
            $sql = "select pessoa.*, agenda.codcarteira 
                from agenda
                inner join pessoa on pessoa.codpessoa = agenda.codpessoa and pessoa.codempresa = agenda.codempresa
                left join atendimento 
                    on  atendimento.codpessoa   = agenda.codpessoa 
                    and atendimento.codcarteira = agenda.codcarteira
                    and atendimento.codempresa  = agenda.codempresa                
            where agenda.codempresa = {$_SESSION["codempresa"]} 
            and agenda.codfuncionario = {$_SESSION["codpessoa"]}
            and agenda.atendido = 'n'  
            and agenda.dtagenda >= '" . date("Y-m-d") . " 00:00:00'
            and agenda.dtagenda <= '" . date("Y-m-d") . " 23:59:59'
            and atendimento.codatendimento is null        
            order by agenda.dtagenda limit 1";
            $pessoap = $conexao->comandoArray($sql);
            if (!isset($pessoap["codpessoa"]) || $pessoap["codpessoa"] == NULL || $pessoap["codpessoa"] == "") {
                /**procura agendamentos de funcionários inativados para espalhar*/
                $andFila = '';
                $sql = 'select pessoa.*, agenda.codcarteira
                        from agenda 
                        inner join pessoa on pessoa.codpessoa = agenda.codpessoa and pessoa.codempresa = agenda.codempresa      
                        left join atendimento 
                            on  atendimento.codpessoa   = agenda.codpessoa 
                            and atendimento.codcarteira = agenda.codcarteira
                            and atendimento.codempresa  = agenda.codempresa                         
                        where   agenda.codempresa  = ' . $_SESSION['codempresa'] . ' 
                        and     pessoa.codstatus not in (1,2,6,8,13)    
                        and     agenda.dtagenda >= "' . date("Y-m-d") . ' 00:00:00" 
                        and     agenda.dtagenda <= "' . date("Y-m-d") . ' 23:59:59"     
                        and     agenda.codcarteira > 0    
                        and     atendimento.codatendimento is null
                        and     agenda.codfuncionario in(select codpessoa from pessoa where status = "i" and codempresa = ' . $_SESSION['codempresa'] . ')';
                $pessoap = $conexao->comandoArray($sql);
                if (!isset($pessoap["codpessoa"]) || $pessoap["codpessoa"] == NULL || $pessoap["codpessoa"] == "") {
                    /*depois procura cliente por critérios ** fila aleatória para carteira de clientes por operador*/
                    if ($_SESSION["codnivel"] != "19" && $_SESSION["codnivel"] != "1") {
                        $andFila .= " and acesso.codoperador = {$_SESSION["codpessoa"]}";
                    }
                    $sql = "select  pessoa.*, cc.codcarteira 
                    from acessooperador as acesso
                    inner join carteiracliente as cc on cc.codcarteira = acesso.codcarteira and cc.codempresa = acesso.codempresa
                    inner join pessoa on pessoa.codpessoa = cc.codcliente and pessoa.codempresa = acesso.codempresa
                    left join agenda 
                        on  agenda.codpessoa   = cc.codcliente 
                        and agenda.codempresa  = cc.codempresa
                        and agenda.dtagenda   >= '" . date("Y-m-d") . " 00:00:00'
                        and agenda.codfuncionario not in(
                            select codpessoa from pessoa where status = 'i' and codempresa = '{$_SESSION['codempresa']}'
                                )
                    left join atendimento 
                        on  atendimento.codpessoa   = pessoa.codpessoa 
                        and atendimento.codcarteira = cc.codcarteira
                        and atendimento.codempresa  = acesso.codempresa                       
                    where acesso.codempresa  = {$_SESSION["codempresa"]} 
                    and pessoa.codstatus not in (1,2,6,8,13)
                    and pessoa.status = 'a' 
                    and atendimento.codatendimento is null
                    and agenda.codagenda is null
                    order by    
                        rand()
                    limit 1";
                    $pessoap = $conexao->comandoArray($sql);
                }
            }
            $_GET["codpessoa"] = $pessoap["codpessoa"];
        }
        if (isset($_GET["callcenter"]) && !isset($pessoap["codpessoa"])) {
            echo '<script>alert("Fila de atendimento acabou!!!");</script>';
        }
    } elseif (!isset($nivel_operador) && isset($_GET["codpessoa"])) {
        $pessoap = $conexao->comandoArray("select * from pessoa where codempresa = '{$_SESSION['codempresa']}' and codpessoa = '{$_GET["codpessoa"]}'");
    } elseif (isset($_GET["proximo"]) && !isset($_GET["tipo"])) {
        if (isset($_GET["codpessoa"]) && $_GET["codpessoa"] != NULL && $_GET["codpessoa"] != "") {
            $andPessoa2 = " and pessoa.codpessoa > '{$_GET["codpessoa"]}'"; //para rodar fila de clientes normais
        }
        $pessoap = $conexao->comandoArray("select * from pessoa where codcategoria = 1 {$andPessoa2} and codempresa = '{$_SESSION['codempresa']}' order by codcategoria");
    }
}
if (isset($_GET["callcenter"]) && $_GET["callcenter"] != NULL && $_GET["callcenter"] == "true") {
    $andNivelPagina = " and nivelpagina.codpagina = '58'";
    $requireForm = "";
    $titulo = "<b style='font-weight: bolder;'>Ficha de Cadastro<br><span style='font-size: 14px;font-style: italic;'>Call Center</span></b>";
} else {
    $andNivelPagina = " and nivelpagina.codpagina = '55'";
    $requireForm = "";
    $titulo = "<b style='font-weight: bolder;'>Ficha de Cadastro<br><span style='font-size: 14px;font-style: italic;'>Cliente</span></b>";
}

if (isset($pessoap) && isset($pessoap["codpessoa"])) {
    if ($_SESSION["codnivel"] != '19' && $_SESSION["codnivel"] != '1') {
        $atendimento = new Atendimento($conexao);
        $atendimento->codpessoa = $pessoap["codpessoa"];
        $atendimento->codcarteira = $pessoap["codcarteira"];
        $atendimento->inserir();
    }
    $action = "../control/AtualizarPessoa.php";
} else {
    $action = "../control/InserirCliente.php";
}

$requireForm = "";
?>  
<!DOCTYPE html>
<html lang="pt-br">
    <head>  
        <title><?=$sitep["nome"]?> | <?= strip_tags($titulo) ?></title>
        <link rel="stylesheet" type="text/css" href="/visao/recursos/css/detalhamento.css">  
        <?php include 'head.php'; ?>

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
                            <?php if ($nivelp["procurar"] == 1 || $_SESSION["codnivel"] == 1) { ?>
                                <li><a href="#tabs-2">Procurar</a></li>
                            <?php } ?>
                            <?php if (isset($_GET["callcenter"])) { ?>    
                                <li id="li_agenda"><a href="#tabs-3">Agendamentos</a></li>
                            <?php } ?>
                            <?php if (isset($_GET["codpessoa"]) && $_GET["codpessoa"] != NULL && $_GET["codpessoa"] != "") { ?>
                                <li><a href="#tabs-4">Proposta</a></li>
                            <?php } ?>
                        </ul>   
                        <div id="tabs-1">
                            <?php include("formCliente.php"); ?>
                        </div>
                        <?php if ($nivelp["procurar"] == 1 || $_SESSION["codnivel"] == 1) { ?>
                            <div id="tabs-2">
                                <?php include("formProcurarCliente.php"); ?>
                            </div>
                        <?php } ?>
                        <?php if (isset($_GET["callcenter"])) { ?>    
                            <div id="tabs-3"><?php include("formProcurarAgenda.php"); ?></div>
                        <?php } ?>
                        <?php if (isset($_GET["codpessoa"]) && $_GET["codpessoa"] != NULL && $_GET["codpessoa"] != "") { ?>
                            <div id="tabs-4"><?php include("formProposta.php"); ?></div>
                        <?php } ?>
                        <span style="float: right; color: grey;width: 100%;text-align: right;">@ Sites e Sistemas PG</span>                            
                    </div>

                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->
            <?php include "footer.php" ?>
        </div><!-- ./wrapper -->

        <?php include './scriptPadrao.php'; ?>
        <script type="text/javascript" src="/visao/recursos/js/ajax/Pessoa.js?123456789"></script>
        <script type="text/javascript" src="/visao/recursos/js/ajax/Agenda.js"></script> 
    </body>
</html>
<?php
if (isset($_GET["codproposta"])) {
    ?>
    <script>
        $("#tabs").tabs({
            active: 2
        });
    </script>  
    <?php
}

/* * mascara para inputs html */

function mask($val, $mask = "(##)####-####") {
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i++) {
        if ($mask[$i] == '#') {
            if (isset($val[$k])) {
                $maskared .= $val[$k++];
            }
        } else {
            if (isset($mask[$i])) {
                $maskared .= $mask[$i];
            }
        }
    }
    return $maskared;
}

/* * sintaxe para corrigir valor de mascara de acordo com o tamanho */

function reestruturandoTelefone($telefonepessoa2) {
    $telefone = str_replace("-", "", str_replace("(", "", str_replace(")", "", str_replace('.', '', $telefonepessoa2))));
    $telefonepessoa = trim($telefone);
    if (strlen($telefonepessoa) == 10) {
        $mascaraTelefone = "(##)####-####";
    } else {
        $mascaraTelefone = "(##)#####-####";
    }
    if (strlen($telefonepessoa) > 8 && $telefonepessoa{0} == "0") {
        $ddd = substr($telefonepessoa, 0, 3);
        if ($ddd !== "045") {
            $telefone = mask($telefonepessoa, $mascaraTelefone);
        } else {
            $telefone = mask($telefonepessoa, $mascaraTelefone);
        }
    } elseif (strlen($telefonepessoa) > 8 && $telefonepessoa{0} != "0") {
        $ddd = substr($telefonepessoa, 0, 2);
        if ($ddd !== "45") {
            $telefone = mask($telefonepessoa, $mascaraTelefone);
        } else {
            $telefone = mask($telefonepessoa, $mascaraTelefone);
        }
    } elseif (strlen($telefonepessoa) == 8) {
        $telefone = mask("45" . $telefonepessoa, $mascaraTelefone);
    }
    return $telefone;
}

//$html = ob_get_clean();
//echo preg_replace('/\s+/', ' ', $html);
