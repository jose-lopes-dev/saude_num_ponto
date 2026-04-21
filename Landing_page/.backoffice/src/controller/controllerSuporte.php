<?php
require_once __DIR__ . '/../model/modelSuporte.php';

$sp = new Suporte();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$op = isset($_POST['op'])
    ? intval($_POST['op'])
    : (isset($_GET['op']) ? intval($_GET['op']) : 0);

/* =========================
   CRIAR PEDIDO
========================= */
if ($op === 1) {

    if (empty($_SESSION['id'])) {
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode([
            "flag" => false,
            "msg"  => "É necessário iniciar sessão para enviar pedido."
        ]);
        exit;
    }

    $id_user  = intval($_SESSION['id']);
    $assunto  = isset($_POST['assunto']) ? intval($_POST['assunto']) : 0;
    $mensagem = isset($_POST['mensagem']) ? trim($_POST['mensagem']) : '';

    header("Content-Type: application/json; charset=UTF-8");
    echo $sp->criarPedido($id_user, $assunto, $mensagem);
    exit;
}

/* =========================
   LISTAR PEDIDOS (HTML)
========================= */
if ($op === 2) {
    echo $sp->getListaPedidosHTML();
    exit;
}

/* =========================
   LISTAR ASSUNTOS
========================= */
if ($op === 3) {
    header("Content-Type: application/json; charset=UTF-8");
    echo $sp->getAssuntosJSON();
    exit;
}

/* =========================
   VER PEDIDO (MODAL)
========================= */
if ($op === 4) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    header("Content-Type: application/json; charset=UTF-8");
    echo $sp->getPedidoJSON($id);
    exit;
}

header("Content-Type: application/json; charset=UTF-8");
echo json_encode([
    "flag" => false,
    "msg"  => "Operação inválida"
]);
exit;
