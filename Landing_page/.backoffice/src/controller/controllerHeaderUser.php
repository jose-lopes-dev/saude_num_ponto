<?php
require_once __DIR__ . '/../model/connection.php';

session_start();
header("Content-Type: application/json");

if (empty($_SESSION["id"])) {
    echo json_encode(["flag" => false]);
    exit;
}

$id = $_SESSION["id"];

/*
 id_tipo_user:
 1 = Admin
 2 = Cliente
 3 = Prestador
*/

$sql = "
    SELECT
        u.email,
        u.foto,
        tu.nome AS role,
        COALESCE(
            a.nome_completo,
            c.nome_completo,
            rh.nome_completo
        ) AS nome
    FROM utilizador u
    INNER JOIN tipo_user tu ON tu.id = u.id_tipo_user
    LEFT JOIN admin a ON a.id_utilizador = u.id
    LEFT JOIN cliente c ON c.id_utilizador = u.id
    LEFT JOIN rh ON rh.id_utilizador = u.id
    WHERE u.id = ?
    LIMIT 1
";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$dados = $stmt->get_result()->fetch_assoc();

echo json_encode([
    "flag" => true,
    "dados" => [
        "nome" => $dados["nome"] ?: $dados["email"],
        "role" => $dados["role"],
        "foto" => $dados["foto"]
    ]
]);
