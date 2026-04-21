<?php
include_once "../model/modelPlanoPT.php";

session_start();
header('Content-Type: application/json; charset=utf-8');

$codigo_rh = isset($_SESSION['rh_codigo']) ? (int)$_SESSION['rh_codigo'] : 0;
if ($codigo_rh <= 0) {
    echo json_encode(['flag'=>false, 'msg'=>'Prestador não encontrado']);
    exit;
}

if (!isset($_POST['op'])) {
    echo json_encode(['flag'=>false, 'msg'=>'op em falta']);
    exit;
}

$pl = new ModelPlanoPT();

// op 1 - listar clientes
if ($_POST['op'] == 1) {

    $resp = $pl->listarClientes($codigo_rh);
    echo ($resp);

// op 2 - listar exercícios 
} else if ($_POST['op'] == 2) {

    $q = trim($_POST['q'] ?? "");
    $grupo = trim($_POST['grupo'] ?? "todos");
    $equip = trim($_POST['equipamento'] ?? "todos");

    $resp = $pl->listarExerciciosSelect($q, $grupo, $equip);
    echo ($resp);

// op 3 - criar plano 
} else if ($_POST['op'] == 3) {

    $cliente_id = (int)($_POST['cliente_id'] ?? 0);
    $titulo = trim($_POST['titulo'] ?? "");

    $resp = $pl->criarPlano($codigo_rh, $cliente_id, $titulo);
    echo ($resp);

// op 4 - guardar dia + exercícios 
} else if ($_POST['op'] == 4) {

    $plano_id = (int)($_POST['plano_id'] ?? 0);
    $dia_semana = (int)($_POST['dia_semana'] ?? 0);
    $nome_dia = trim($_POST['nome_dia'] ?? "");
    $itens = $_POST['itens'] ?? "[]";

    $resp = $pl->guardarDia($codigo_rh, $plano_id, $dia_semana, $nome_dia, $itens);
    echo ($resp);

// op 5 - listar plano
} else if ($_POST['op'] == 5) {

    $cliente_id = (int)($_POST['cliente_id'] ?? 0);

    $resp = $pl->listarPlanos($codigo_rh, $cliente_id);
    echo ($resp);

// op 6 - editar plano
} else if ($_POST['op'] == 6) {

    $plano_id = (int)($_POST['plano_id'] ?? 0);

    $resp = $pl->detalhesPlano($codigo_rh, $plano_id);
    echo ($resp);

// op 7 - eliminar plano
}else if($_POST['op'] == 7){

    $plano_id = (int)($_POST['plano_id'] ?? 0);

    $resp = $pl->eliminarPlano($codigo_rh, $plano_id);
    echo($resp);

// op 8 - publicar plano
} else if ($_POST['op'] == 8) {

    $plano_id = (int)($_POST['plano_id'] ?? 0);
    $publicado = (int)($_POST['publicado'] ?? 1); 

    $resp = $pl->publicarPlano($codigo_rh, $plano_id, $publicado);
    echo($resp);

} else {

    echo json_encode(['flag'=>false, 'msg'=>'op inválida']);
    exit;
}
