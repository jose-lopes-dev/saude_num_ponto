<?php
require_once '../model/modelAtivos.php';
$m = new ModelAtivos();

if ($_POST['op'] == 1) {
    echo $m->listar();

} else if ($_POST['op'] == 2) {
    echo $m->detalhe($_POST['id'] ?? 0);

} else if ($_POST['op'] == 3) {
    echo $m->categorias();

} else if ($_POST['op'] == 4) {
    echo $m->criar($_POST);

} else if ($_POST['op'] == 5) {
    echo $m->atualizar($_POST);

} else if ($_POST['op'] == 6) {
    echo $m->apagar($_POST['id'] ?? 0);

} else if ($_POST['op'] == 7) {
    echo $m->charts();

} else if ($_POST['op'] == 8) {
    echo $m->timeseries($_POST['id'] ?? 0);

} else {
    echo json_encode(['ok' => false, 'msg' => 'op inválida']);
}
?>
