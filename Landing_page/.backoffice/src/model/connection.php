<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "database_aio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    // Em produção usa um log em ficheiro em vez de echo/die
    die(json_encode(["flag" => false, "msg" => "Erro de ligação à BD: " . $conn->connect_error]));
}

// Assegura charset UTF-8
$conn->set_charset("utf8mb4");
