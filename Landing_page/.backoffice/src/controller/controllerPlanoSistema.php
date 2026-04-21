<?php
require_once "../model/modelPlanoSistema.php";

session_start();
header('Content-Type: application/json; charset=utf-8');

$user_id = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;
if ($user_id <= 0) {
    echo json_encode(['flag' => false, 'msg' => 'Sessão inválida']);
    exit;
}

if (!isset($_POST['op'])) {
    echo json_encode(['flag' => false, 'msg' => 'op em falta']);
    exit;
}

$plano = new PlanoSistema();

if ($_POST['op'] == 1) {
    $id_servico = isset($_POST['id_servico']) ? (int)$_POST['id_servico'] : 0;

    if ($id_servico <= 0) {
        echo json_encode(['flag' => false, 'msg' => 'Plano inválido']);
        exit;
    }

    echo $plano->criarVendaPlanoPendente($user_id, $id_servico);
    exit;
} else if ($_POST['op'] == 2) {
    $id_venda = isset($_POST['id_venda']) ? (int)$_POST['id_venda'] : 0;
    $square_payment_id = isset($_POST['square_payment_id']) ? trim($_POST['square_payment_id']) : '';

    if ($id_venda <= 0 || $square_payment_id === '') {
        echo json_encode(['flag' => false, 'msg' => 'Dados em falta']);
        exit;
    }

    echo $plano->confirmarPagamentoPlano($user_id, $id_venda, $square_payment_id);

    exit;

} else if ($_POST['op'] == 3) {

    $nonce  = isset($_POST['nonce']) ? trim($_POST['nonce']) : '';
    $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;

    echo $plano->criarPagamentoSquare($nonce, $amount);
    exit;
} else if ($_POST['op'] == 4) {

    echo $plano->getPlanoAtual($user_id);
    exit;
} else if ($_POST['op'] == 5) {

    $val = isset($_POST['val']) ? (int)$_POST['val'] : 0;

    echo $plano->setRenovacaoAutomatica($user_id, $val);
    exit;
}

echo json_encode(['flag' => false, 'msg' => 'op inválida']);
exit;
