<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php
                echo '<img src="', $arquivoImg, '" class="img-circle" alt="Imagem usuário">';
                ?>

            </div>
            <div class="pull-left info">
                <p><?= $_SESSION["nome"] ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type='text' name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MENU DE NAVEGAÇÃO</li>
            <li>
                <a href="./home"><i></i> <span>Inicial</span> <i class="fa fa-angle-left pull-right"></i></a>
            </li>
            <?php
            $resmodulo = $conexao->comando("select codmodulo, nome, icone from modulo order by codmodulo");
            $qtdmodulo = $conexao->qtdResultado($resmodulo);
            if ($qtdmodulo > 0) {
                if (isset($empresap["codcategoria"]) && $empresap["codcategoria"] != NULL && $empresap["codcategoria"] == 2) {
                    $andPagina = ' and pagina.codpagina <> 60';
                }
                while ($modulo = $conexao->resultadoArray($resmodulo)) {
                    $sql = "select distinct pagina.codpagina, pagina.nome, pagina.link, pagina.titulo, pagina.abreaolado 
                    from pagina
                    inner join nivelpagina on nivelpagina.codpagina = pagina.codpagina
                    inner join nivel on nivel.codnivel = nivelpagina.codnivel
                    where pagina.codmodulo = '{$modulo["codmodulo"]}' {$andPagina}
                    and ((nivel.padrao = 's' and nivel.codnivel = '{$_SESSION["codnivel"]}') or (nivel.padrao <> 's' and nivel.codnivel = '{$_SESSION["codnivel"]}'))
                    order by pagina.nome";
                    $respagina = $conexao->comando($sql)or die("<pre>$sql</pre>");
                    $qtdpagina = $conexao->qtdResultado($respagina);
                    if ($qtdpagina > 0) {
                        if (isset($_SESSION["codpagina"]) && $_SESSION["codpagina"] != NULL && $_SESSION["codpagina"] != "") {
                            $sql = 'select codpagina from pagina where codpagina = ' . $_SESSION["codpagina"] . ' and codmodulo = ' . $modulo["codmodulo"];
                            $paginap = $conexao->comandoArray($sql);
                        }
                        if (isset($paginap["codpagina"]) && $paginap["codpagina"] != NULL && $paginap["codpagina"] != "") {
                            $classeLi = 'active treeview';
                        } else {
                            $classeLi = 'treeview';
                        }
                        //active treeview janela aberta
                        echo '<li class="', $classeLi, '">';
                        echo '<a href="#"><i class="fa ', $modulo["icone"], '"></i> <span>', $modulo["nome"], '</span> <i class="fa fa-angle-left pull-right"></i></a>';

                        echo '<ul class="treeview-menu">';
                        while ($pagina = $conexao->resultadoArray($respagina)) {
                            $pagina["link"] = str_replace('.php', '', $pagina["link"]);
                            if (strpos($pagina["link"], '?')) {
                                $complemento = '&data=' . date("dmYHis");
                            } else {
                                $complemento = '?data=' . date("dmYHis");
                            }
                            if ($pagina["abreaolado"] == "n") {
                                echo '<li class="active"><a href="', $pagina["link"], $complemento, '"><i class="fa ', $pagina["icone"], '"></i>', $pagina["nome"], '</a></li>';
                            } else {
                                echo '<li class="active"><a target="_blank" href="', $pagina["link"], $complemento, '"><i class="fa ', $pagina["icone"], '"></i>', $pagina["nome"], '</a></li>';
                            }
                        }
                        echo '</ul>';
                        echo '</li>';
                    }
                }
            }
            ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>