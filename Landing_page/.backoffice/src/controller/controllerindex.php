<?php
// controllerindex.php
require_once __DIR__ . '/../model/modelindex.php';

$dashboard = new Dashboard();

if (!isset($_POST['op'])) {
    echo json_encode(['error' => 'Operação não especificada']);
    exit;
}

$op = $_POST['op'] + 0; // forçar int

// Sem switch/case: várias ifs simples
if ($op == 1) {
    echo $dashboard->getSaldoTotal();
}

if ($op == 2) {
    echo $dashboard->getCustosSetembro();
}

if ($op == 3) {
    echo $dashboard->getRendimentosSetembro();
}

if ($op == 4) {
    echo $dashboard->getRAISetembro();
}

if ($op == 5) {
    echo $dashboard->getGraficoMensal();
}
