<?php

ob_start("ob_gzhandler");

/* * deixa o documento como utf-8 */
header('Content-Type: text/html; charset=utf-8');

session_start();
if (!isset($_SESSION["codpessoa"])) {
    echo '<script>alert("Sua sessão caiu por favor faça login novamente!!!"); location.href="http://' . $_SERVER['SERVER_NAME'] . '/login"</script>';
}

function __autoload($class_name) {
    if (file_exists('../model/' . $class_name . '.php')) {
        include '../model/' . $class_name . '.php';
    } elseif (file_exists('../visao/' . $class_name . '.php')) {
        include '../visao/' . $class_name . '.php';
    } elseif (file_exists('./' . $class_name . '.php')) {
        include './' . $class_name . '.php';
    }
}

$conexao = new Conexao();
$cache = new Cache();

$pagina = explode('?', str_replace('/', '', $_SERVER["REQUEST_URI"]));
if ($pagina[1] != "callcenter=true") {
    $pagina = $pagina[0];
} else {
    $pagina = 'Cliente.php?callcenter=true';
}
$nivelp = $cache->read('nivelp' . $_SESSION['codnivel'] . 'link' . $pagina);
if (!isset($nivelp) || $nivelp == NULL) {
    
    $sql = "select nivelpagina.*, pagina.nome as pagina, modulo.nome as modulo, pagina.link as pagina_link 
                from nivelpagina 
                inner join pagina on pagina.codpagina = nivelpagina.codpagina    
                inner join modulo on modulo.codmodulo = pagina.codmodulo
                where nivelpagina.codnivel = '{$_SESSION["codnivel"]}' and pagina.link = '{$pagina}.php'";           
    $nivelp = $conexao->comandoArray($sql);
    $cache->save('nivelp' . $_SESSION['codnivel'] . 'link' . $pagina, $nivelp, '10 minutes');
}

/* * vendo informações padrão do sistema que mostram a empresa que esta vendendo com logo */
$sitep = $cache->read('sitep' . $_SESSION['codempresa']);
if (!isset($sitep) || $sitep == NULL) {
    $sql = 'select nome, logo, email from site';
    $sitep = $conexao->comandoArray($sql);
    $cache->save('sitep' . $_SESSION['codempresa'], $empresap, '60 minutes');
}

/* * vendo informações padrão da empresa que o cara logou */
$empresap = $cache->read('empresap' . $_SESSION['codempresa']);
if (!isset($empresap) || $empresap == NULL) {
    $sql = 'select razao, codcategoria, logo from empresa where codempresa = ' . $_SESSION["codempresa"];
    $empresap = $conexao->comandoArray($sql);
    $cache->save('empresap' . $_SESSION['codempresa'], $empresap, '90 minutes');
}

$_SESSION["codpagina"] = $nivelp["codpagina"];

/* * verificando a imagem da pessoa e ja salvando a devida url para evitar uso de file_exists */
$arquivoImg = $cache->read('arquivoImgEmp' . $_SESSION['codempresa'] . 'Codpessoa' . $_SESSION["codpessoa"]);
if (!isset($arquivoImg) || $arquivoImg == NULL) {
    $arquivoImg = '../arquivos/' . $_SESSION["imagem"];
    if ($_SESSION["imagem"] == NULL || $_SESSION["imagem"] == "" || !file_exists($arquivoImg)) {
        $arquivoImg = '../visao/recursos/img/sem_imagem.png';
    }
    $cache->save('arquivoImgEmp' . $_SESSION['codempresa'] . 'Codpessoa' . $_SESSION["codpessoa"], $arquivoImg, '90 minutes');
}

/* * criptografia quando passa para editar por url código de chave primária */
$Cripto = new Cripto();
if (isset($_GET["p"])) {
    $param = isset($_GET["p"]) ? $_GET["p"] : '';
    $parametros = $Cripto->separar_parametros($Cripto->decodificar($param));
    $valor = $Cripto->separar_valor($parametros[0]);
    $codigo = $valor[1];
    $_GET[$valor[0]] = $valor[1];
}