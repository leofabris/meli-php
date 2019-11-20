<?php

session_start();

if (isset($_GET['tipo'])) {
    switch ($_GET['tipo']) {
        case "pendentes":
            $pagina = "Pedidos Pendentes";
            $url = "https://api.mercadolibre.com/orders/search/pending";
            $descricao = "Esta pesquisa recuperará todos as ordens em status 'pendente' e omitirá as canceladas automaticamente.";
            break;
        case "recentes":
            $pagina = "Pedidos Recentes";
            $url = "https://api.mercadolibre.com/orders/search/recent";
            $descricao = "As ordens recentes são as geradas mais recentemente para um usuário. A resposta incluirá ordens nas quais a data atual for anterior à expiration_date e ainda não tenham sido qualificadas por nenhuma das partes.";
            break;
        case "arquivadas":
            $pagina = "Pedidos Arquivados";
            $url = "https://api.mercadolibre.com/orders/search/archived";
            $descricao = "Pedidos com expiration_date anterior à data atual ou que foi qualificada por ambas as partes";
            break;
    }
} else {
    header("location:index.php");
}
?>
    <!DOCTYPE html>
    <html lang="pt">

    <head>
        <?php include_once('./imports/header.php'); ?>
        <title>Pedidos - Consultas API do ML</title>
        <?php include_once('./imports/imports.php'); ?>
    </head>

    <body>
        <?php include_once('./imports/navbar.php'); ?>
        <input type="hidden" name="producao_token" id="producao_token" value="<?php echo $_SESSION['producao_token']; ?>">
        <input type="hidden" name="seller_id" id="seller_id" value="<?php echo $_SESSION['seller_id']; ?>">
        <input type="hidden" name="url" id="url" value="<?php echo $url; ?>">
        <div class="container">
            <h1 class="page-header" style="margin-top: 10px;"><?php echo $pagina; ?></h1>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <?php echo $descricao; ?>
                            <span class="pull-right">
                                <a href="#" onclick="pesquisar()"><i class="fa fa-refresh"></i></a>
                            </span>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-hover table-condensed">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Criado em</th>
                                        <th>Fechado em</th>
                                        <th>Expira em</th>
                                        <th>Ultima Atualização</th>
                                        <th style="text-align: center">Total</th>
                                        <th style="text-align: center">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="corpo">

                                </tbody>
                            </table>
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-lg-4 col-md-4" style="padding-top: 9px" id="total_registros">

                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" id="paginacao_inferior">
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination" style="margin-top: 0px; margin-bottom: 0px">
                                                    <li>
                                                        <a href="#" onclick="mudarPagina(-50)" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>
                                                    <!--
                                                    <li><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li><a href="#">3</a></li>
                                                    <li><a href="#">4</a></li>
                                                    <li><a href="#">5</a></li>-->
                                                    <li>
                                                        <a href="#" onclick="mudarPagina(+50)" aria-label="Next">
                                                            <span aria-hidden="true">&raquo;</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="debug">

                        </div>
                    </div>

                </div>

                <div id="requisitando" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center font-large font-large" id="mensagem">
                                        <i class="fa fa-spinner fa-spin"></i><br>Requisitando...
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include_once('./imports/scripts.php'); ?>
                <script>
                    //setInterval("pesquisar()", 15000);

                    var carregando = '<tr><td colspan="7" style="text-align: center"><i class="fa fa-refresh fa-spin"></i><br>Carregando...</td></tr>';
                    var url = $('#url').val();
                    var offset = "0";

                    $(document).ready(function() {
                        pesquisar();
                    });

                    function atualizar() {
                        $.ajax({
                            url: url,
                            type: "GET",
                            dataType: "json",
                            async: true,
                            data: {
                                "seller": $('#seller_id').val(),
                                "access_token": $('#producao_token').val(),
                                "offset": offset,
                                "sort": "date_desc",
                            },
                            success: function(msg) {
                                $('#corpo').empty();
                                console.log(msg);

                                $.each(msg.results, function(obj, i) {
                                    tratarDados(i);
                                });

                                $('#total_registros').empty();
                                $('#total_registros').append(msg.paging.total + " registros encontrados");
                            }
                        });
                    }

                    function mudarPagina(qtd) {
                        offset = parseInt(offset) + qtd;
                        if (offset < 0) {
                            offset = 0;
                            alert("Não é possível alterar. Já está no começo!!!");
                        } else {
                            pesquisar();
                        }
                        //console.log("Offset: " + offset);
                    }

                    function tratarDados(msg) {

                        var dados = "id=" + msg.id + "&criacao=" + msg.date_created + "&fechamento=" + msg.date_closed + "&expira=" + msg.expiration_date + "&atualizacao=" + msg.date_last_updated + "&total=" + msg.total_amount + "&status=" + msg.status;

                        $.ajax({
                            url: "./ajax/api_mercado.php?acao=pedidos",
                            type: "POST",
                            dataType: 'html',
                            data: dados,
                            async: true,
                            success: function(response) {
                                //console.log(response);
                                $('#corpo').append(response);
                            },
                        });
                    }

                    function pesquisar() {
                        $.ajax({
                            url: url,
                            type: "GET",
                            dataType: "json",
                            async: true,
                            data: {
                                "seller": $('#seller_id').val(),
                                "access_token": $('#producao_token').val(),
                                "offset": offset,
                                "sort": "date_desc",
                            },
                            beforeSend: function() {
                                $('#corpo').empty();
                                $('#corpo').append(carregando);
                            },
                            success: function(msg) {
                                $('#corpo').empty();
                                console.log(msg);

                                $.each(msg.results, function(obj, i) {
                                    tratarDados(i);
                                });

                                $('#total_registros').empty();
                                $('#total_registros').append(msg.paging.total + " registros encontrados");
                            }
                        });
                    }
                </script>
    </body>

    </html>