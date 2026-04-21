<?php
require_once __DIR__ . '/../model/modelVendas.php';
$m = new Vendas();

if ($_POST['op'] == 1) {
    $end = $_POST['end'] ?? date('Y-m');
    echo $m->packsTrimestre($end);

} else if ($_POST['op'] == 2) {
    $ym = isset($_POST['ym']) ? substr((string)$_POST['ym'], 0, 7) : date('Y-m');
    echo $m->indivGrupoPorMes($ym);

} else if ($_POST['op'] == 3) {
    $ym   = isset($_POST['ym']) ? substr((string)$_POST['ym'], 0, 7) : date('Y-m');
    $modo = $_POST['modo'] ?? 'tri';
    echo $m->consultasResumoMes($ym, $modo);

} else if ($_POST['op'] == 4) {
    $lim = isset($_POST['lim']) && is_numeric($_POST['lim']) ? (int)$_POST['lim'] : 25;
    $off = isset($_POST['off']) && is_numeric($_POST['off']) ? (int)$_POST['off'] : 0;
    $q   = isset($_POST['q']) ? $_POST['q'] : '';
    echo $m->ultimasVendas($lim, $off, $q);

} else if ($_POST['op'] == 5) {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    echo $m->apagarVenda($id);

} else if ($_POST['op'] == 6) {
    $id = isset($_POST['id_venda']) ? (int)$_POST['id_venda'] : 0;

    if ($id <= 0 || empty($_FILES['ficheiro']['tmp_name'])) {
        echo json_encode(['ok' => false, 'msg' => 'Pedido inválido']);
    } else {
        echo $m->guardarFatura($id, $_FILES['ficheiro']);
    }

} else {
    echo json_encode(['ok' => false, 'msg' => 'op inválida']);
}
?>
