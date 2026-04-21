<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Evita cache (importante para logout)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Verifica se está autenticado
if (!isset($_SESSION['id'])) {
    header("Location: /Projeto_Final_AIO/Landing_page/.backoffice/login.html");
    exit;
}

require_once __DIR__ . '/../model/connection.php';

if (!empty($_SESSION['id'])) {
    $id = (int) $_SESSION['id'];

    $stmt = $conn->prepare("
        SELECT 
            u.id,
            u.username,
            u.id_tipo_user,
        COALESCE(rh.nome_completo, c.nome_completo, u.username) AS nome
        FROM utilizador u
        LEFT JOIN rh      ON rh.id_utilizador = u.id
        LEFT JOIN cliente c ON c.id_utilizador = u.id
        WHERE u.id = ?
        LIMIT 1
        ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    $row = $res->fetch_assoc();

    $_SESSION['id']   = (int)$row['id'];
    $_SESSION['tipo'] = (int)$row['id_tipo_user'];
    $_SESSION['nome'] = $row['nome'];         
    $_SESSION['username'] = $row['username'];

}
