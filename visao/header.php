<header class="main-header">
    <!-- Logo -->
    <a href="/visao/index.php?data=<?= date("dmYHis") ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img style="width: 80px;" src="../visao/recursos/img/correspondente2.png"/></span>
        <!-- logo for regular state and mobile devices --> 
        <span class="logo-lg"><img style="width: 115px;" src="../visao/recursos/img/correspondente2.png"/></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span> 
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu"> 
            <ul class="nav navbar-nav">              
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php
                        echo '<img src="' . $arquivoImg . '" class="user-image" alt="User Image">';
                        echo '<span class="hidden-xs">', $_SESSION["nome"] ,'</span>';
                        ?>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <?php
                            echo '<img src="' . $arquivoImg . '" class="img-circle" alt="User Image">';
                            echo '<p>';
                            echo $_SESSION["nome"];
                            $pessoa = $conexao->comandoArray("select DATE_FORMAT(dtcadastro, '%d/%m/%Y') as dtcadastro2 from pessoa where codpessoa = '{$_SESSION['codpessoa']}'");
                            echo '<small>Membro desde ', $pessoa["dtcadastro2"], '</small>';
                            echo '</p>';
                            ?>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="Pessoa?codpessoa=<?=$_SESSION["codpessoa"]?>" class="btn btn-default btn-flat">Perfil</a>
                            </div>
                            <div class="pull-right">
                                <a href="Logout" class="btn btn-default btn-flat">Sair</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>