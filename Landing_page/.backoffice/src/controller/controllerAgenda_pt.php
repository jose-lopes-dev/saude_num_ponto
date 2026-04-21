<?php

include_once '../model/modelAgenda_pt.php';

session_start();
header('Content-Type: application/json; charset=utf-8');

$codigo_rh = (int)$_SESSION['rh_codigo'];
if ($codigo_rh <= 0) {
    echo json_encode(['ok'=>false,'msg'=>'Prestador não encontrado']);
    exit;
}

if (!isset($_POST['op'])) {
    echo json_encode(['ok'=>false,'msg'=>'op em falta']);
    exit;
}

$m = new ModelDisponibilidade();

if ($_POST['op'] == 1) {

    $resp = $m->listarIntervalo(
        $codigo_rh,
        $_POST['start'] ?? '',
        $_POST['end'] ?? ''
    );
    echo($resp);

} else if ($_POST['op'] == 2) {

    $resp = $m->criar(
        $codigo_rh,
        $_POST['inicio'] ?? '',
        $_POST['fim'] ?? '',
        $_POST['motivo'] ?? null
    );
    echo($resp);

} else if ($_POST['op'] == 3) {

    $resp = $m->apagar(
        (int)($_POST['id'] ?? 0),
        $codigo_rh
    );
    echo($resp);

} else if ($_POST['op'] == 4) {

    $resp = $m->listarHorarioSemanal(
        $codigo_rh
    );
    echo($resp);

} else if ($_POST['op'] == 5) {

    $resp = $m->guardarHorarioSemanal(
        $codigo_rh,
        $_POST['items'] ?? '[]'
    );
    echo($resp);

} else if ($_POST['op'] == 6) {

    $resp = $m->listarEventos(
        $codigo_rh,
        $_POST['start'] ?? '',
        $_POST['end'] ?? ''
    );
    echo($resp);

} else if ($_POST['op'] == 7) {

    $resp = $m->criarEvento(
        $codigo_rh,
        $_POST['titulo'] ?? '',
        $_POST['inicio'] ?? '',
        $_POST['fim'] ?? '',
        $_POST['descricao'] ?? ''
    );
    echo($resp);

} else if ($_POST['op'] == 8) {

    $resp = $m->apagarEvento(
        (int)($_POST['id'] ?? 0),
        $codigo_rh
    );
    echo($resp);

} else {

    echo json_encode(['ok' => false, 'msg' => 'op inválida']);
    exit;
}

?>
