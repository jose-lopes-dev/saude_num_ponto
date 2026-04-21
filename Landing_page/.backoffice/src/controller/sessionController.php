<?php
session_start();

header('Content-Type: application/json');

echo json_encode([
    'logged'     => isset($_SESSION['id']),
    'user_id'    => $_SESSION['id'] ?? null,
    'tipo'       => $_SESSION['tipo'] ?? null,
    'cliente_id' => $_SESSION['cliente_id'] ?? null,
    'nome'       => $_SESSION['nome'] ?? null,
]);

