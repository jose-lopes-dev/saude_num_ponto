<?php
require_once '../model/modelConsultas.php';
$m = new ModelConsultas();

if ($_POST['op'] == 1) {
    echo $m->listarTodas();

} else if ($_POST['op'] == 2) {
    echo $m->obter(intval($_POST['id'] ?? 0));

} else if ($_POST['op'] == 3) {
    echo $m->kpis(intval($_POST['year']), intval($_POST['month']));

} else if ($_POST['op'] == 4) {
    echo $m->eventosDoMes(intval($_POST['year']), intval($_POST['month']));

} else if ($_POST['op'] == 5) {
    $codigo_cliente    = $_POST['codigo_cliente']    ?? null;
    $id_prestador      = $_POST['id_prestador']      ?? null;
    $id_servico        = $_POST['id_servico']        ?? null;
    $id_servico_extra  = $_POST['id_servico_extra']  ?? null;
    $data              = $_POST['data']              ?? null;  
    $hora              = $_POST['hora']              ?? null;
    $id_estado         = $_POST['id_estado']         ?? 15;

    echo $m->criar($codigo_cliente, $id_prestador, $id_servico, $id_servico_extra, $data, $hora, $id_estado);

} else if ($_POST['op'] == 6) {
    $id                = $_POST['id']                ?? null;
    $codigo_cliente    = $_POST['codigo_cliente']    ?? null;
    $id_prestador      = $_POST['id_prestador']      ?? null;
    $id_servico        = $_POST['id_servico']        ?? null;
    $id_servico_extra  = $_POST['id_servico_extra']  ?? null;
    $data              = $_POST['data']              ?? null;
    $hora              = $_POST['hora']              ?? null;
    $id_estado         = $_POST['id_estado']         ?? 15;
    
    echo $m->editar($id, $codigo_cliente, $id_prestador, $id_servico, $id_servico_extra, $data, $hora, $id_estado);

} else if ($_POST['op'] == 7) {
    echo $m->apagar(intval($_POST['id'] ?? 0));

} else if ($_POST['op'] == 8) {
    echo $m->clientes($_POST['q'] ?? '');

} else if ($_POST['op'] == 9) {
    echo $m->profissionais($_POST['q'] ?? '');

} else if ($_POST['op'] == 10) {
    $lim = isset($_POST['lim']) && is_numeric($_POST['lim']) ? (int)$_POST['lim'] : 10;
    $off = isset($_POST['off']) && is_numeric($_POST['off']) ? (int)$_POST['off'] : 0;
    $q   = isset($_POST['q']) ? $_POST['q'] : '';
    echo $m->listarPagina($lim, $off, $q);
    
} else if ($_POST['op'] == 11) {
    echo $m->precoServico($_POST['id_servico'] ?? 0);

} else if ($_POST['op'] == 12) { 
    $ids = $_POST['ids'] ?? [];
    echo $m->precoExtras($ids);

} else {
    echo json_encode(['ok' => false, 'msg' => 'op inválida']);
}
?>
