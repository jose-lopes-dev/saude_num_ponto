<?php
require_once '../src/auth/auth.php';
require_once '../src/model/modelAula.php';

header('Content-Type: application/json');

$mdl = new Aula();

$idAula = (int)($_POST['idAula'] ?? 0);
$idUser = $_SESSION['id'] ?? 0;
$tipo   = (int)($_SESSION['tipo'] ?? 0);

if (!$idAula || !$idUser) {
    echo json_encode([
        'success' => false,
        'message' => 'Dados inválidos'
    ]);
    exit;
}

$aula = json_decode($mdl->getAulaById($idAula), true);

if (!$aula) {
    echo json_encode([
        'success' => false,
        'message' => 'Aula não encontrada'
    ]);
    exit;
}

/* =========================
   PERMISSÕES
========================= */

// PT
if ($tipo === 2 && $aula['id_pt'] != $idUser) {
    echo json_encode([
        'success' => false,
        'message' => 'Não tem permissão para esta aula'
    ]);
    exit;
}

// Cliente
if ($tipo === 3) {
    $id_cliente = $mdl->getClienteCodigoByUtilizador($idUser);
    if (!$mdl->clienteJaInscrito($id_cliente, $idAula)) {
        echo json_encode([
            'success' => false,
            'message' => 'Não está inscrito nesta aula'
        ]);
        exit;
    }
}

echo json_encode([
    'success' => true,
    'redirect' => 'video_aula.php?id=' . $idAula
]);
