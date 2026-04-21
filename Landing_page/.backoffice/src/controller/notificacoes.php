<?php
require_once __DIR__ . "/../model/connection.php";
session_start();

if (!isset($_SESSION["id"])) exit;

$id_utilizador = intval($_SESSION["id"]);
$op = $_POST["op"] ?? "";

/* ======================
   LISTAR
====================== */
if ($op === "listar") {

    $r = $conn->query("
        SELECT id, tipo, titulo, texto, criada_em, lida
        FROM notificacao
        WHERE id_utilizador = $id_utilizador
        ORDER BY criada_em DESC
        LIMIT 10
    ");

    $out = [];

    while ($n = $r->fetch_assoc()) {
        $out[] = $n;
    }

    echo json_encode($out);
    exit;
}

/* ======================
   MARCAR COMO LIDA
====================== */
if ($op === "ler") {

    $nid = intval($_POST["id"]);

    $conn->query("
        UPDATE notificacao
        SET lida = 1
        WHERE id = $nid AND id_utilizador = $id_utilizador
    ");

    exit;
}

/* ======================
   REMOVER TODAS
====================== */
if ($op === "remover_todas") {

    $conn->query("
        DELETE FROM notificacao
        WHERE id_utilizador = $id_utilizador
    ");

    exit;
}
