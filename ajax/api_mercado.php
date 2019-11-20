<?php

session_start();

error_reporting(E_ALL);



if ($_GET['acao'] == "relatorios") {

    $retorno = json_decode(utf8ize($_POST['retorno']));

    var_dump($retorno);

    foreach ($retorno as $dados) {
        echo "<tr>";
        echo "<td>" . $dados->id . "</td>";
        echo "<td style='text-align: center'>" . formataData($dados->begin_date) . "</td>";
        echo "<td style='text-align: center'>" . formataData($dados->end_date) . "</td>";
        echo "<td style='text-align: center'>" . formataData($dados->date_created) . "</td>";
        echo "<td>" . $dados->created_from . "</td>";
        echo "<td style='text-align: center'><a href='https://api.mercadopago.com/v1/account/bank_report/" . $dados->file_name . "?access_token=" . $_SESSION['producao_token'] . "'><i class='fa fa-download'></i></a></td>";
        echo "</tr>";
    }
}

if ($_GET['acao'] == "formatarDatas") {
    echo $_POST['data_inicial'] . "|" . $_POST['data_final'];
}

if ($_GET['acao'] == "pedidos") {
    echo "<tr>";
    echo '<td><a href="det_pedido.php?id=' . $_POST['id'] . '">' . $_POST['id'] . '</a></td>';
    echo "<td>" . formataData($_POST['criacao']) . "</td>";
    echo "<td>" . formataData($_POST['fechamento']) . "</td>";
    echo "<td>" . formataData($_POST['expira']) . "</td>";
    echo "<td>" . formataData($_POST['atualizacao']) . "</td>";
    echo "<td style='text-align: right'>R$ " . number_format($_POST['total'], 2, ',', '.') . "</td>";
    echo "<td style='text-align: center'>" . verificaStatus($_POST['status']) . "</td>";
    echo "</tr>";
}

if ($_GET['acao'] == "itens") {

    $itens = json_decode($_POST['itens']);
    //var_dump($_POST['itens']);
    $cont = 1;
    $total = 0.00;
    foreach ($itens as $item) {
        echo "<tr>";
        echo "<td>" . $cont++ . "</td>";
        echo "<td>" . $item->item->id . "</td>";
        echo "<td>" . $item->item->title . "</td>";
        echo "<td>" . $item->quantity . "</td>";
        echo "<td style='text-align: right'>" . number_format($item->unit_price, 2, ',', '.') . "</td>";
        echo "<td style='text-align: right'>" . number_format(($item->quantity * $item->unit_price), 2, ',', '.') . "</td>";
        echo "</tr>";
        $total += ($item->quantity * $item->unit_price);
    }
    echo "<tr>";
    echo "<td colspan='5' style='text-align: right'><strong>Total do pedido:</strong></td>";
    echo "<td style='text-align: right'><strong>" . number_format($total, 2, ',', '.')  . "</strong></td>";
    echo "</tr>";
}

if ($_GET['acao'] == "pagamentos") {

    var_dump(str_replace("&", "", $_POST['pagamentos']));

    $pagamentos = json_decode($_POST['pagamentos']);

    foreach ($pagamentos as $pagamento) {

        if ($pagamento->transaction_amount < $pagamento->total_paid_amount) {
            $diferenca = $pagamento->total_paid_amount - $pagamento->transaction_amount;
            if ($diferenca != $pagamento->shipping_cost) {
                $taxa = ($pagamento->total_paid_amount - $pagamento->transaction_amount);
            }
        }

        echo "<tr>";
        echo "<td><a href='#' data-toggle='modal' data-target='#pagamento' onclick='detalhaPagamento($pagamento->id)'>" . $pagamento->id . "</a></td>";
        echo "<td>" . formataData($pagamento->date_created) . "</td>";
        if (isset($pagamento->date_approved)) {
            echo "<td>" . formataData($pagamento->date_approved) . "</td>";
        } else {
            echo "<td></td>";
        }

        echo "<td style='text-align: center'>" . $pagamento->installments . "</td>";
        if ($pagamento->installments > 1) {
            echo "<td style='text-align: right'>R$ " . number_format($pagamento->installment_amount, 2, ',', '.') . "</td>";
        } else {
            echo "<td style='text-align: right'>R$ " . number_format($pagamento->transaction_amount, 2, ',', '.') . "</td>";
        }
        echo "<td style='text-align: right'>R$ " . number_format($pagamento->total_paid_amount, 2, ',', '.') . "</td>";
        echo "<td style='text-align: right'>R$ " . number_format($pagamento->shipping_cost, 2, ',', '.') . "</td>";
        echo "<td style='text-align: right'>R$ " . number_format($pagamento->taxes_amount + $taxa, 2, ',', '.') . "</td>";

        echo "</tr>";
    }
}

if ($_GET['acao'] == "etiquetas") {
    $dados = json_decode($_POST['dados']);

    print_r($_POST['dados']);

    /*
    $url_nf = "http://api.mercadolibre.com/users/" . $_SESSION['seller_id'] . "/invoices/shipments/" . $dados->shipping->id . "?access_token=" . $_SESSION['producao_token'];

    if ($dados->shipping->status == "ready_to_ship" && $dados->shipping->substatus == "ready_for_pickup") {
        echo "<tr>";
        echo "<td><input type='checkbox' value='" . $dados->shipping->status . "' /></td>";
        echo "<td>" . $dados->id . "</td>";
        echo "<td>" . buscaNumNota($url_nf) . "</td>";
        echo "<td>R$ " . number_format($dados->total_amount, 2, ',', '.') . "</td>";
        echo "</tr>";
    }*/
}

function buscaNumNota($url_nf)
{
    $retorno = json_decode(file_get_contents($url_nf, 0));

    return $retorno->invoice_number;
}

function verificaStatus($status)
{

    switch ($status) {
        case "confirmed":
            return '<span class="label label-primary">Nova Venda</span>';
        case "payment_required":
            return '<span class="label label-info">Aguardando Pagamento</span>';
        case "payment_in_process":
            return '<span class="label label-info">Processando Pagamento</span>';
        case "partially_paid":
            return '<span class="label label-warning">Pagamento Parcial</span>';
        case "paid":
            return '<span class="label label-success">Pagamento Realizado</span>';
        case "cancelled":
            return '<span class="label label-danger">Cancelado</span>';
        case "invalid":
            return '<span class="label label-danger">Inválido</span>';
    }
}

function formataData($data)
{
    if ($data != "null") {
        return substr($data, 8, 2) . "/" . substr($data, 5, 2) . "/" . substr($data, 0, 4);
    } else {
        return "";
    }
}



function safe_json_encode($value, $options = 0, $depth = 512)
{
    $encoded = json_encode($value, $options, $depth);
    if ($encoded === false && $value && json_last_error() == JSON_ERROR_UTF8) {
        $encoded = json_encode(utf8ize($value), $options, $depth);
    }
    return $encoded;
}

function utf8ize($d)
{
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string($d)) {
        return utf8_encode($d);
    }
    return $d;
}
