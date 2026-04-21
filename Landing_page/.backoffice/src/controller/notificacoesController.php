<?php
require_once __DIR__ . "/../model/connection.php";
session_start();

header("Content-Type: application/json; charset=UTF-8");

if (empty($_SESSION["id"])) {
    echo json_encode([]);
    exit;
}

$id_utilizador = (int) $_SESSION["id"];
$op = $_POST["op"] ?? "";

/* =========================
   LISTAR
========================= */
if ($op === "listar") {

    $stmt = $conn->prepare("
        SELECT id, tipo, referencia_id, titulo, texto, lida, criada_em
        FROM notificacao
        WHERE id_utilizador = ?
        ORDER BY criada_em DESC
        LIMIT 10
    ");
    $stmt->bind_param("i", $id_utilizador);
    $stmt->execute();

    echo json_encode(
        $stmt->get_result()->fetch_all(MYSQLI_ASSOC)
    );
    exit;
}

/* =========================
   MARCAR UMA COMO LIDA
========================= */
if ($op === "ler") {

    $id = (int) ($_POST["id"] ?? 0);

    $stmt = $conn->prepare("
        UPDATE notificacao
        SET lida = 1
        WHERE id = ? AND id_utilizador = ?
    ");
    $stmt->bind_param("ii", $id, $id_utilizador);
    $stmt->execute();

    echo json_encode(["ok" => true]);
    exit;
}

/* =========================
   MARCAR TODAS COMO LIDAS
========================= */
if ($op === "ler_todas") {

    $stmt = $conn->prepare("
        UPDATE notificacao
        SET lida = 1
        WHERE id_utilizador = ?
    ");
    $stmt->bind_param("i", $id_utilizador);
    $stmt->execute();

    echo json_encode(["ok" => true]);
    exit;
}

/* =========================
   LIMPAR TODAS
========================= */
if ($op === "limpar") {

    $stmt = $conn->prepare("
        DELETE FROM notificacao
        WHERE id_utilizador = ?
    ");
    $stmt->bind_param("i", $id_utilizador);
    $stmt->execute();

    echo json_encode(["ok" => true]);
    exit;
}

echo json_encode([]);
exit;
