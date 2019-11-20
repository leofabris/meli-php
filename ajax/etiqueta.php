<?php

session_start();

$shipment_id = $_GET['id'];
//$url = "https://api.mercadolibre.com/shipment_labels?shipment_ids=" . $shipment_id . "&response_type=zpl2&access_token=" . $_SESSION['producao_token'];
$url2 = "https://api.mercadolibre.com/shipment_labels?shipment_ids=28149188706&response_type=zpl2&access_token=" . $_SESSION['producao_token'];
$url_nf = "http://api.mercadolibre.com/users/" . $_SESSION['seller_id'] . "/invoices/shipments/" . $shipment_id . "?access_token=" . $_SESSION['producao_token'];
$download = "../etiquetas/etiqueta.zip";
$dir = "../etiquetas/";
$nome_comum = "Etiqueta de Envio.txt";
download($url2, $download);
unzip($download, $dir);
renomear($nome_comum, $url_nf, $dir);
unlink($download);

function renomear($arquivo, $url_nf, $dir)
{
    rename($dir . $arquivo, $dir . buscaNumNota($url_nf) . ".txt");
}

function buscaNumNota($url_nf)
{
    $retorno = json_decode(file_get_contents($url_nf, 0));

    return $retorno->invoice_number;
}

function unzip($download, $dir)
{
    $zip = new ZipArchive;
    if ($zip->open($download) == TRUE) {
        $zip->extractTo($dir);
        $zip->close();
        echo "Extract ok!";
    } else {
        echo "Extract error!";
    }
}

function download($url2, $download)
{
    $rh = fopen($url2, 'rb');
    $wh = fopen($download, 'wb');
    if ($rh === false || $wh === false) {
        // error reading or opening file
        return true;
    }
    while (!feof($rh)) {
        if (fwrite($wh, fread($rh, 1024)) === FALSE) {
            return true;
        }
    }
    fclose($rh);
    fclose($wh);
    
    return false;
}
