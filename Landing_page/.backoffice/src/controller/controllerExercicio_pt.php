<?php
include_once "../model/modelExercicio_pt.php";

session_start();
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['rh_codigo'])) {
    echo json_encode(['flag'=>false,'msg'=>'Não autenticado']);
    exit;
}

if (!isset($_POST['op'])) {
    echo json_encode(['flag'=>false,'msg'=>'op em falta']);
    exit;
}

$ex = new ExercicioPT();

// op 1 - registar
if ($_POST['op'] == 1) {

    $resp = $ex->registar($_POST);
    echo($resp);

// op 2 - listar
} else if ($_POST['op'] == 2) {

    $resp = $ex->listar($_POST);
    echo($resp);

// op 3 - remover
} else if ($_POST['op'] == 3) {

    $resp = $ex->remover($_POST);
    echo($resp);

// op 4 - get dados
} else if ($_POST['op'] == 4) {

    $resp = $ex->getDados($_POST);
    echo($resp);

// op 5 - editar
} else if ($_POST['op'] == 5) {

    $resp = $ex->editar($_POST);
    echo($resp);

// op 6 - listar p/ select2 
} else if ($_POST['op'] == 6) {

    $resp = $ex->listarSelect($_POST);
    echo($resp);

} else {
    echo json_encode(['flag'=>false,'msg'=>'op inválida']);
    exit;
}
?>
