<?php
require_once '../model/modelFornecedor.php';
$rh = new Fornecedor();

if ($_POST['op'] == 1) {
    echo $rh->registaFornecedor(
        $_POST['fornecedor'],
        $_POST['descricao'],
        $_POST['total_debito'],
        $_POST['total_credito'],
        $_POST['saldo'],
        $_POST['data']
    );

} else if ($_POST['op'] == 2) {
    $mes = $_POST['mes'] ?? '';
    if ($mes != "") {
        echo $rh->getListaFornecedoresPorMes($mes);
    } else {
        echo $rh->getListaFornecedores();
    }

} else if ($_POST['op'] == 3) {
    echo $rh->concluirFornecedor($_POST['id']);

} else if ($_POST['op'] == 4) {
    echo $rh->getDadosFornecedor($_POST['id']);

} else if ($_POST['op'] == 5) {
    echo $rh->guardaEditFornecedor(
        $_POST['id'],
        $_POST['fornecedor'],
        $_POST['descricao'],
        $_POST['total_debito'],
        $_POST['total_credito'],
        $_POST['saldo'],
        $_POST['data']
    );
}
?>
