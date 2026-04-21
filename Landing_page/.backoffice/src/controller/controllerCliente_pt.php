<?php

require_once '../model/modelCliente_pt.php';

$cliPt = new ClientePt();

if ($_POST['op'] == 1) {

    $res = $cliPt->getStatsClientesPt($_POST['idPt']);
    echo($res);

} else if ($_POST['op'] == 2) {

    $res = $cliPt->getListaClientesPt($_POST['idPt']);
    echo($res);

} else if ($_POST['op'] == 3) {

    $res = $cliPt->desativarClientePt($_POST['idCliente']);
    echo($res);

} else if ($_POST['op'] == 4) {

    $res = $cliPt->getDadosClientePt($_POST['idCliente']);
    echo($res);

} else if ($_POST['op'] == 5) {

    $res = $cliPt->guardaEditClientePt(
        $_POST['idCliente'],
        $_POST['nome'],
        $_POST['contacto'],
        $_POST['email'],
        $_POST['dataNasc'],
        $_POST['estado'],
        $_POST['objetivo'],
    );
    echo($res);

} else if ($_POST['op'] == 6) {

    $res = $cliPt->alteraEstadoClientePt(
        $_POST['idCliente'],
        $_POST['novoEstado']
    );
    echo($res);
}

?>
