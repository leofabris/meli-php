<?php

$conn = new SQLite3("database.db");

session_start();

$url = "https://api.mercadolibre.com/orders/search/recent";

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <?php include_once('./imports/header.php'); ?>
    <title>Impressão de Etiquetas - Consultas API do ML</title>
    <?php include_once('./imports/imports.php'); ?>
</head>

<body style="min-height: 600px;">
    <?php include_once('./imports/navbar.php'); ?>

    <input type="hidden" name="producao_token" id="producao_token" value="<?php echo $_SESSION['producao_token']; ?>">
    <input type="hidden" name="seller_id" id="seller_id" value="<?php echo $_SESSION['seller_id']; ?>">
    <input type="hidden" name="url" id="url" value="<?php echo $url; ?>">

    <div class="container">
        <h2 class="page-header" style="margin-top: 10px;">Etiquetas</h2>

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-10 col-md-10" style="padding-top: 5px;">
                                Selecione as etiquetas que deseja imprimir:
                            </div>
                            <div class="col-lg-2 col-md-2 text-right">
                                <select name="tipo" class="form-control input-sm">
                                    <option value="novas">Novas</option>
                                    <option value="impressas">Já Impressa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-12 col-md-12">
                            <table class="table table-condensed table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Pedido</th>
                                        <th>Num. NF</th>
                                        <th>Valor</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="pedidos">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-lg-4 col-md-4">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12" id="debug">
            </div>
        </div>

    </div>

    <?php include_once('./imports/scripts.php'); ?>

    <script>
        var carregando = '<tr><td colspan="7" style="text-align: center"><i class="fa fa-refresh fa-spin"></i><br>Carregando...</td></tr>';
        var url = $('#url').val();
        var offset = "0";
        var total = "0";

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
                    tratarDados(i);
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
        }

        function tratarDados(msg) {

            $.ajax({
                url: "./ajax/api_mercado.php?acao=etiquetas",
                type: "POST",
                dataType: 'html',
                data: "dados=" + JSON.stringify(msg),
                async: true,
                success: function(response) {
                    console.log(response);
                    $('#debug').append("<pre>" + response + "</pre>");
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

                    total = msg.paging.total;

                    $.each(msg.results, function(obj, i) {
                        $.each(i.payments, function(obj2, val2) {
                            val2.activation_uri = "";
                        });
                    });

                    tratarDados(msg);

                    /*
                    var cont=1;
                    do {
                        sleep(500);
                        offset = parseInt(offset) + 50;
                        atualizar();
                        //console.log(cont++ + ": Offset: " + offset + " -> Total: " + total);
                    } while (parseInt(offset) < parseInt(total));
*/
                }
            });
        }

        function sleep(miliseconds) {
            var currentTime = new Date().getTime();
            while (currentTime + miliseconds >= new Date().getTime()) {}
        }
    </script>

</body>

</html>