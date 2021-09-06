<?php
    date_default_timezone_set('America/Sao_Paulo');
    session_start();
    include "../model/Conexao.php";
    $conexao   = new Conexao();
    $resAcesso = $conexao->comando("update acesso set dtsaida = '".date("Y-m-d H:i:s")."' where data = '".date('Y-m-d')."' and codempresa = '{$_SESSION['codempresa']}' and codpessoa = '{$_SESSION['codpessoa']}'");
    session_destroy();
    echo '<script>location.href="/";</script>'; 
?>