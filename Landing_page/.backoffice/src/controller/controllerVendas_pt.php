<?php
require_once '../model/modelVendas_pt.php';

$vPT = new VendasPT();

if ($_POST['op'] == 1) {

    $res = $vPT->getClientesVendaPt($_POST['idPt']);
    echo($res);

} else if ($_POST['op'] == 2) {

    $res = $vPT->getServicosVendaPt($_POST['idPt']);
    echo($res);

} else if ($_POST['op'] == 3) {

    $res = $vPT->registaVendaPt(
        $_POST['idPt'],
        $_POST['id_cliente'],
        $_POST['id_servico'],
        $_POST['valor'],
        $_POST['data_venda'],
        $_POST['metodo_pagamento'],
        $_POST['id_estado']
    );
    echo($res);

} else if ($_POST['op'] == 4) {

    $res = $vPT->getTabelaVendasPt($_POST['idPt']);
    echo($res);

} else if ($_POST['op'] == 5) {

    $res = $vPT->removeVendaPt($_POST['id']);
    echo($res);
}
