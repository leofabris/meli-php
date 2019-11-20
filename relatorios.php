<?php

session_start();

$conn = new SQLite3("database.db");
$result = $conn->query("SELECT * FROM configs");
$dados = $result->fetchArray();

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <?php include_once('./imports/header.php'); ?>
    <title>Relatórios - Consultas API do ML</title>
    <?php include_once('./imports/imports.php'); ?>
</head>

<body>
    <?php include_once('./imports/navbar.php'); ?>
    <input type="hidden" name="producao_token" id="producao_token" value="<?php echo $_SESSION['producao_token']; ?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#novoRelatorio"><i class="fa fa-plus-circle"></i> Novo Relatório</button>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <table class="table table-striped table-hover table-condensed">
                    <thead>
                        <tr>
                            <th style='text-align: center'>ID</th>
                            <th style='text-align: center'>Data Inicial</th>
                            <th style='text-align: center'>Data Final</th>
                            <th style='text-align: center'>Gerado em</th>
                            <th style='text-align: center'>Forma</th>
                            <th style='text-align: center'></th>
                        </tr>
                    </thead>
                    <tbody id="corpo">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="novoRelatorio" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Requisitar novo relatório</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            Informe a data inicial e final para o novo relatório:
                        </div>
                    </div>
                    <form name="datas" id="datas">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                <label>Data Inicial:</label>
                                <input type="date" name="data_inicial" id="data_inicial" class="form-control input-sm">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                                <label>Data Final:</label>
                                <input type="date" name="data_final" id="data_final" class="form-control input-sm">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="requisitar()"><i class="fa fa-check"></i> Requisitar</button>
                </div>
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
        setInterval("atualizar()", 3000);

        function gerarRequisicao(datas) {
            var datas_separadas = datas.split("|");

            var data11 = datas_separadas[0] + "T00:00:00Z";
            var data22 = datas_separadas[1] + "T23:59:59Z";


            $.ajax({
                url: "https://api.mercadopago.com/v1/account/bank_report?access_token=" + $('#producao_token').val(),
                type: "POST",
                dataType: 'json',
                data: JSON.stringify({
                    "begin_date": data11,
                    "end_date": data22
                }),
                async: true,
                beforeSend: function() {
                    $('#requisitando').modal('toggle');
                    $('#mensagem').empty();
                    $('#mensagem').append('<i class="fa fa-spinner fa-spin"></i><br>Requisitando...');
                },
                success: function(response) {
                    $('#mensagem').empty();
                    $('#mensagem').append('<i class="fa fa-check"></i><br>Feito!<br>Agora, só aguardar!');
                }
            });
        }

        function requisitar() {
            $.ajax({
                url: "./ajax/api_mercado.php?acao=formatarDatas",
                type: "POST",
                dataType: 'html',
                data: $('#datas').serialize(),
                async: true,
                success: function(response) {
                    //console.log(response);
                    gerarRequisicao(response);
                },
            });
        }

        function atualizar() {
            $.ajax({
                url: "https://api.mercadopago.com/v1/account/bank_report/list?access_token=" + $('#producao_token').val(),
                type: "GET",
                dataType: "json",
                async: true,
                success: function(msg) {
                    //console.log("Atualizando...");
                    tratarDados(msg);
                }
            });
        }

        $(document).ready(function() {
            pesquisar();
        });

        var carregando = '<tr><td colspan="5" style="text-align: center"><i class="fa fa-refresh fa-spin"></i><br>Carregando...</td></tr>';

        function tratarDados(msg) {
            //$('#corpo').append(msg);
            $.ajax({
                url: "./ajax/api_mercado.php?acao=relatorios",
                type: "POST",
                dataType: 'html',
                data: "retorno=" + JSON.stringify(msg),
                async: true,
                success: function(response) {
                    $('#corpo').empty();
                    $('#corpo').append(response);
                },
            });
        }

        function pesquisar() {
            $.ajax({
                url: "https://api.mercadopago.com/v1/account/bank_report/list?access_token=" + $('#producao_token').val(),
                type: "GET",
                dataType: "json",
                async: true,
                beforeSend: function() {
                    $('#corpo').empty();
                    $('#corpo').append(carregando);
                },
                success: function(msg) {
                    //console.log(msg);
                    tratarDados(msg);
                }
            });
        }
    </script>
</body>

</html>