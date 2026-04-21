<?php
require_once __DIR__ . "/../model/connection.php";
session_start();

if (!isset($_SESSION["id"])) {
    echo json_encode([]);
    exit;
}

$id = intval($_SESSION["id"]);

$r = $conn->query("
    SELECT COALESCE(rh.nome_completo, c.nome_completo, u.username) AS nome
    FROM utilizador u
    LEFT JOIN rh      ON rh.id_utilizador = u.id
    LEFT JOIN cliente c ON c.id_utilizador = u.id
    WHERE u.id = $id
    LIMIT 1
");

if (!$r || !$r->num_rows) {
    echo json_encode([]);
    exit;
}

$u = $r->fetch_assoc();

$_SESSION['nome'] = $u['nome'];

echo json_encode([
    "nome" => $u["nome"]
]);
