<?php
include_once '../model/modelDashboard_pt.php';

session_start();

header('Content-Type: application/json; charset=utf-8');

$codigo_rh = isset($_SESSION['rh_codigo']) ? (int)$_SESSION['rh_codigo'] : 0;
if ($codigo_rh <= 0) {
    echo json_encode(['ok'=>false,'msg'=>'Prestador não encontrado']);
    exit;
}

if (!isset($_POST['op'])) {
    echo json_encode(['flag'=>false,'msg'=>'op em falta']);
    exit;
}

$dash = new DashboardPT();

// op 1 - KPIs resumo
if($_POST['op'] == 1){

    $resp = $dash -> getResumoDashboard($codigo_rh);

    echo($resp);

// op 2 - gráfico consultas por dia da semana
}else if($_POST['op'] == 2){

    $resp = $dash -> getConsultasSemana($codigo_rh);

    echo($resp);

// op 3 - gráfico consultas por estado
}else if($_POST['op'] == 3){

    $resp = $dash -> getConsultasPorEstado($codigo_rh);

    echo($resp);

// op 4 - tabela próximas consultas
}else if($_POST['op'] == 4){

    $resp = $dash -> getProximasConsultas($codigo_rh);

    echo($resp);

} else{

    echo json_encode(['flag'=>false,'msg'=>'op inválida']);
    exit;
}

?>
