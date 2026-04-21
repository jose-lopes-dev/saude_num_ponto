<?php

require_once '../model/modelCliente_nutri.php';

$cliNutri = new ClienteNutri();

if ($_POST['op'] == 1) {

    $res = $cliNutri->getStatsClientesNutri($_POST['idNutri']);
    echo $res;

} else if ($_POST['op'] == 2) {

    $res = $cliNutri->getListaClientesNutri($_POST['idNutri']);
    echo $res;

}  else if ($_POST['op'] == 4) {

    $res = $cliNutri->getDadosClienteNutri($_POST['idCliente']);
    echo $res;

} else if ($_POST['op'] == 5) {

    $res = $cliNutri->guardaEditClienteNutri(
        $_POST['idCliente'],
        $_POST['nome'],
        $_POST['telefone'],   // atenção: vem do JS como telefone
        $_POST['email'],
        $_POST['dataNasc'],
        $_POST['estado'],
        $_POST['objetivo']
    );
    echo $res;

} else if ($_POST['op'] == 6) {

    $res = $cliNutri->alteraEstadoClienteNutri(
        $_POST['idCliente'],
        $_POST['novoEstado']
    );
    echo $res;
}
