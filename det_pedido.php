<?php

session_start();

$url = "https://api.mercadolibre.com/orders/" . $_GET['id'] . "?access_token=" . $_SESSION['producao_token'];
$url_pagamento = "https://api.mercadopago.com/v1/payments/search";

if (!isset($_GET['id'])) {
    header("location:index.php");
}

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <?php include_once('./imports/header.php'); ?>
    <title>Detalhe do Pedido - Consultas API do ML</title>
    <?php include_once('./imports/imports.php'); ?>
</head>

<body style="min-height: 600px;">
    <?php include_once('./imports/navbar.php'); ?>

    <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>">
    <input type="hidden" name="url" id="url" value="<?php echo $url; ?>">
    <input type="hidden" name="token" id="token" value="<?php echo $_SESSION['producao_token']; ?>">
    <input type="hidden" name="url_pagamento" id="url_pagamento" value="<?php echo $url_pagamento; ?>">


    <div class="container">

        <h1 class="page-header" style="margin-top: 5px">
            Pedido: <?php echo $_GET['id']; ?>
            <span class="pull-right" id="tags">

            </span>
        </h1>

        <div class="row">
            <div class="col-lg-2 col-md-2">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        Dados do Pedido
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <label>Data:</label>
                                <input type="text" name="data_pedido" id="data_pedido" class="form-control input-sm text-center" style="padding-left: 5px; padding-right: 5px" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-10 col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        Dados do comprador
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-2">
                                <label>ID:</label>
                                <input type="text" name="id_usuario" id="id_usuario" class="form-control input-sm text-center" readonly>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <label>Nickname:</label>
                                <input type="text" name="nickname" id="nickname" class="form-control input-sm text-center" readonly>
                            </div>
                            <div class="col-lg-5 col-md-5">
                                <label>Nome:</label>
                                <input type="text" name="nome" id="nome" class="form-control input-sm text-uppercase" readonly>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <label id="tipo_documento">CPF/CNPJ:</label>
                                <input type="text" name="documento" id="documento" class="form-control input-sm" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body" style="height: 300px; min-height: 300px; max-height: 300px; overflow-y: auto;">
                        <table class="table table-condensed table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Descrição</th>
                                    <th>Qtd.</th>
                                    <th>Preço</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="itens">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10 col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        Dados Financeiros
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <table class="table table-condensed table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Criado em</th>
                                            <th>Aprovado em</th>
                                            <th>Parcelas</th>
                                            <th>Valor Parcela</th>
                                            <th>Total</th>
                                            <th>Frete</th>
                                            <th>Taxas</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pagamentos">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <p class="text-center" id="etiqueta"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="pagamento" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 1024px;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="titulo_pagamento"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-2 col-md-2" id="mensagem">
                            <label>ID:</label>
                            <input type="text" name="id_pagamento" id="id_pagamento" class="form-control input-sm" readonly>
                        </div>
                        <div class="col-lg-2 col-md-2" id="mensagem">
                            <label>Forma Pagto.:</label>
                            <input type="text" name="forma_pagamento" id="forma_pagamento" class="form-control input-sm" readonly>
                        </div>
                        <div class="col-lg-2 col-md-2" id="mensagem">
                            <label>Criado em:</label>
                            <input type="text" name="criado_pagamento" id="criado_pagamento" class="form-control input-sm" readonly>
                        </div>
                        <div class="col-lg-2 col-md-2" id="mensagem">
                            <label>Vence em:</label>
                            <input type="text" name="vencimento_pagamento" id="vencimento_pagamento" class="form-control input-sm" readonly>
                        </div>
                        <div class="col-lg-2 col-md-2" id="mensagem">
                            <label>Última atual.:</label>
                            <input type="text" name="atualizacao_pagamento" id="atualizacao_pagamento" class="form-control input-sm" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-2" id="mensagem">
                            <label>ID:</label>
                            <input type="text" name="id_pagamento" id="id_pagamento" class="form-control input-sm" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('./imports/scripts.php'); ?>

    <script>
        $(document).ready(function() {
            pesquisar();
        });

        var url = $('#url').val();
        var urlPagamento = $('#url_pagamento').val();

        function pesquisar() {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                async: true,
                success: function(msg) {
                    console.log(msg);
                    $('#id_usuario').val(msg.buyer.id);
                    $('#nickname').val(msg.buyer.nickname);
                    if (msg.buyer.first_name != null && msg.buyer.last_name != null) {
                        $('#nome').val(msg.buyer.first_name + " " + msg.buyer.last_name);
                    }


                    if (msg.buyer.billing_info != null) {
                        $('#documento').val(msg.buyer.billing_info.doc_number);
                    }
                    $('#data_pedido').val(msg.date_created.substring(8, 10) + "/" + msg.date_created.substring(5, 7) + "/" + msg.date_created.substring(0, 4) + " " + msg.date_created.substring(11, 19));

                    processarItens(msg.order_items);
                    processarPagamentos(msg.payments);
                    if (msg.status == "cancelled") {
                        $('#tags').append('<span class="label label-danger">Cancelado</span> ');
                    } else {
                        processarTags(msg.tags);
                    }
                    verificarEtiqueta(msg.shipping);
                }
            });
        }

        function processarTags(tags) {

            $.each(tags, function(obj, val) {
                switch (val) {
                    case "not_delivered":
                        $('#tags').append('<span class="label label-warning">Não entregue</span> ');
                        break;
                    case "not_paid":
                        $('#tags').append('<span class="label label-info">Pagto pendente</span> ');
                        break;
                    case "paid":
                        $('#tags').append('<span class="label label-success">Pago</span> ');
                        break;
                }

            });

        }

        function processarPagamentos(dados) {

            $.each(dados, function(obj, val) {
                val.activation_uri = "";
            });

            $.ajax({
                url: "./ajax/api_mercado.php?acao=pagamentos",
                type: "POST",
                dataType: "html",
                async: true,
                data: "pagamentos=" + JSON.stringify(dados),
                success: function(retorno) {
                    //console.log(retorno);
                    $('#pagamentos').empty();
                    $('#pagamentos').append(retorno);
                }
            });
        }

        function detalhaPagamento(idPagamento) {
            $.ajax({
                url: urlPagamento,
                type: "GET",
                dataType: "json",
                async: true,
                data: {
                    "access_token": $('#token').val(),
                    "id": idPagamento,
                },
                success: function(retorno) {
                    //console.log(retorno);

                    processarPagamento(retorno.results);
                }
            });
        }

        function processarPagamento(dados) {

            $.each(dados, function(obj, val) {
                $('#titulo_pagamento').append("Pagamento ID: " + val.id);
                $('#id_pagamento').val(val.id);
                if (val.payment_method_id == "bolbradesco") {
                    $('#forma_pagamento').val("Boleto");
                }

                console.log(val);
            });
        }

        function processarItens(dados) {
            $.ajax({
                url: "./ajax/api_mercado.php?acao=itens",
                type: "POST",
                dataType: "html",
                async: true,
                data: "itens=" + JSON.stringify(dados),
                success: function(retorno) {
                    //console.log(retorno);
                    $('#itens').empty();
                    $('#itens').append(retorno);
                }
            });
        }

        function verificarEtiqueta(dados) {
            if(dados.status == "ready_to_ship" && dados.substatus == "ready_for_pickup") {
                var url_etiqueta = "https://api.mercadolibre.com/shipment_labels?shipment_ids=" + dados.id + "&response_type=zpl2&access_token=" + $('#token').val();
                $('#etiqueta').append("<a href='" + url_etiqueta + "'><i class='fa fa-download'></i><br>Baixar Etiqueta</a>");
            } else {
                $('#etiqueta').append("Indisponível");
            }
        }
    </script>



</body>

</html>