<?php
require_once '../model/modelFinancas.php';
$model = new ModelFinancas();

$op = $_POST['op'] ?? '';
header('Content-Type: application/json');

if($op === 'getData'){
    echo json_encode([
        'prestacoes' => $model->getEmprestimos(),
        'kpis' => $model->getKPIs()
    ]);
    exit;
}

if($op === 'marcarPago'){
    $id = intval($_POST['id'] ?? 0);
    echo json_encode($model->marcarPago($id));
    exit;
}

echo json_encode(['error'=>'op inválida']);
