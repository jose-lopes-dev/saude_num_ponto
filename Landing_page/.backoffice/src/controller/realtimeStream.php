<?php
require_once __DIR__ . '/../model/connection.php';
session_start();

header("Content-Type: application/json; charset=UTF-8");

if (empty($_SESSION["id"])) {
    echo json_encode([]);
    exit;
}

$id_utilizador = (int) $_SESSION["id"];
$lastId = isset($_GET["last_id"]) ? (int) $_GET["last_id"] : 0;

$stmt = $conn->prepare("
    SELECT id, evento, payload
    FROM realtime_events
    WHERE id > ?
    AND (
        id_utilizador IS NULL
        OR id_utilizador = ?
    )
    ORDER BY id ASC
    LIMIT 20
");

$stmt->bind_param("ii", $lastId, $id_utilizador);
$stmt->execute();

$res = $stmt->get_result();

$events = [];

while ($row = $res->fetch_assoc()) {
    $row["payload"] = $row["payload"]
        ? json_decode($row["payload"], true)
        : null;
    $events[] = $row;
}

echo json_encode($events);
exit;
