<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Consulta API</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Início</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pedidos <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="pedidos.php?tipo=pendentes">Pendentes</a></li>
                        <li><a href="pedidos.php?tipo=recentes">Recentes</a></li>
                        <li><a href="pedidos.php?tipo=arquivadas">Arquivados</a></li>
                    </ul>
                </li>
                <li><a href="etiquetas.php">Etiquetas</a></li>
                <li><a href="pagamentos.php">Pagamentos</a></li>
                <li><a href="relatorios.php">Relatórios</a></li>
                <!--
                <li><a href="configs.php">Configurações</a></li>
                <li class="dropdown">
                    <a href="https://getbootstrap.com/docs/3.4/examples/navbar-static-top/#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="https://getbootstrap.com/docs/3.4/examples/navbar-static-top/#">Action</a></li>
                        <li><a href="https://getbootstrap.com/docs/3.4/examples/navbar-static-top/#">Another action</a></li>
                        <li><a href="https://getbootstrap.com/docs/3.4/examples/navbar-static-top/#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Nav header</li>
                        <li><a href="https://getbootstrap.com/docs/3.4/examples/navbar-static-top/#">Separated link</a></li>
                        <li><a href="https://getbootstrap.com/docs/3.4/examples/navbar-static-top/#">One more separated link</a></li>
                    </ul>
                </li>-->
            </ul>
        </div>
    </div>
</nav>