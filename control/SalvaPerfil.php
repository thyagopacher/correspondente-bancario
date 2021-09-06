<?php
    session_start();
        //validação caso a sessão caia
    if(!isset($_SESSION)){
        die("<script>alert('Sua sessão caiu, por favor logue novamente!!!');window.close();</script>");
    }  
include "../model/Conexao.php";
include "../model/NivelPagina.php";

$msg_retorno = "";
$sit_retorno = true;

$conexao = new Conexao();
$nivelp = new NivelPagina($conexao);
$nivelp->codnivel = $_POST["codnivel"];
$res = 1;

$sql = "delete from nivelpagina where codnivel = '{$_POST["codnivel"]}'";
$resdel = $conexao->comando($sql);

if ($resdel == FALSE) {
    die(json_encode(array('mensagem' => "Erro resetar perfil para remontar!", 'situacao' => false)));
}


$sql = "select * from pagina order by codpagina";
$respagina = $conexao->comando($sql);
$qtdpagina = $conexao->qtdResultado($respagina);
if ($qtdpagina > 0) {
    while ($pagina = $conexao->resultadoArray($respagina)) {
        $post_pagina = "pagina{$pagina["codpagina"]}";
        if (isset($_POST[$post_pagina])) {
            foreach ($_POST[$post_pagina] as $key => $value) {
                $nivel_pagina = $conexao->comandoArray("select * from nivelpagina where codnivel = '{$_POST["codnivel"]}' and codpagina = '{$pagina["codpagina"]}'");
                switch ($value) {
                    case "h":
                        if (isset($nivel_pagina) && isset($nivel_pagina["codnivel"])) {
                            $sql = "update nivelpagina set mostrar = '1' where codpagina = '{$pagina["codpagina"]}' and codnivel = '{$_POST["codnivel"]}'";
                        } else {
                            $sql = "insert into nivelpagina(mostrar, codpagina, codnivel) values('1', '{$pagina["codpagina"]}', '{$_POST["codnivel"]}')";
                        }
                        $res = $conexao->comando($sql);
                        break;
                    case "i":
                        if (isset($nivel_pagina) && isset($nivel_pagina["codnivel"])) {
                            $sql = "update nivelpagina set inserir = '1' where codpagina = '{$pagina["codpagina"]}' and codnivel = '{$_POST["codnivel"]}'";
                        } else {
                            $sql = "insert into nivelpagina(inserir, codpagina, codnivel) values('1', '{$pagina["codpagina"]}', '{$_POST["codnivel"]}')";
                        }
                        $res = $conexao->comando($sql);
                        break;
                    case "a":
                        if (isset($nivel_pagina) && isset($nivel_pagina["codnivel"])) {
                            $sql = "update nivelpagina set atualizar = '1' where codpagina = '{$pagina["codpagina"]}' and codnivel = '{$_POST["codnivel"]}'";
                        } else {
                            $sql = "insert into nivelpagina(atualizar, codpagina, codnivel) values('1', '{$pagina["codpagina"]}', '{$_POST["codnivel"]}')";
                        }
                        $res = $conexao->comando($sql);
                        break;
                    case "e":
                        if (isset($nivel_pagina) && isset($nivel_pagina["codnivel"])) {
                            $sql = "update nivelpagina set excluir = '1' where codpagina = '{$pagina["codpagina"]}' and codnivel = '{$_POST["codnivel"]}'";
                        } else {
                            $sql = "insert into nivelpagina(excluir, codpagina, codnivel) values('1', '{$pagina["codpagina"]}', '{$_POST["codnivel"]}')";
                        }
                        $res = $conexao->comando($sql);
                        break;
                    case "p":
                        if (isset($nivel_pagina) && isset($nivel_pagina["codnivel"])) {
                            $sql = "update nivelpagina set procurar = '1' where codpagina = '{$pagina["codpagina"]}' and codnivel = '{$_POST["codnivel"]}'";
                        } else {
                            $sql = "insert into nivelpagina(procurar, codpagina, codnivel) values('1', '{$pagina["codpagina"]}', '{$_POST["codnivel"]}')";
                        }
                        $res = $conexao->comando($sql);
                        break;
                    case "pdf":
                        if (isset($nivel_pagina) && isset($nivel_pagina["codnivel"])) {
                            $sql = "update nivelpagina set gerapdf = '1' where codpagina = '{$pagina["codpagina"]}' and codnivel = '{$_POST["codnivel"]}'";
                        } else {
                            $sql = "insert into nivelpagina(gerapdf, codpagina, codnivel) values('1', '{$pagina["codpagina"]}', '{$_POST["codnivel"]}')";
                        }
                        $res = $conexao->comando($sql);                       
                        break;
                    case "xls":
                        if (isset($nivel_pagina) && isset($nivel_pagina["codnivel"])) {
                            $sql = "update nivelpagina set geraexcel = '1' where codpagina = '{$pagina["codpagina"]}' and codnivel = '{$_POST["codnivel"]}'";
                        } else {
                            $sql = "insert into nivelpagina(geraexcel, codpagina, codnivel) values('1', '{$pagina["codpagina"]}', '{$_POST["codnivel"]}')";
                        }
                        $res = $conexao->comando($sql);
                        break;                        
                }
            }
        }
    }
}

if ($res === FALSE) {
    $msg_retorno = "Erro ao salvar causado por:" . mysqli_error($conexao->conexao);
    $sit_retorno = false;
} else {
    $msg_retorno = "Perfil salvo!";
}

echo json_encode(array('mensagem' => $msg_retorno, 'situacao' => $sit_retorno));
