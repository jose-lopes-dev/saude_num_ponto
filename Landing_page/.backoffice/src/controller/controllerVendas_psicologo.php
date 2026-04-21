<?php
require_once __DIR__ . '/../model/ModelVendasPsicologo.php';

session_start();

if (!isset($_SESSION["id"])) {
    header("Content-Type: application/json");
    echo json_encode([
        "flag" => false,
        "msg" => "Não autenticado"
    ]);
    exit;
}

$model = new ModelVendasPsicologo();
$op = isset($_POST["op"]) ? $_POST["op"] : "";

if ($op === "clientes") {
    header("Content-Type: text/html; charset=UTF-8");
    echo $model->getClientes($_SESSION["id"]);
    exit;
}

if ($op === "sessoes") {
    header("Content-Type: text/html; charset=UTF-8");
    echo $model->getConsultas(
        $_SESSION["id"],
        $_POST["id_cliente"] ?? 0
    );
    exit;
}

if ($op === "guardar") {
    header("Content-Type: application/json");
    echo json_encode(
        $model->registarVenda(
            $_SESSION["id"],
            $_POST
        )
    );
    exit;
}

if ($op === "listar") {
    header("Content-Type: text/html; charset=UTF-8");
    echo $model->listarVendas($_SESSION["id"]);
    exit;
}

if ($op === "remover") {
    header("Content-Type: application/json");
    echo json_encode(
        $model->removerVenda(
            $_SESSION["id"],
            $_POST["id"] ?? 0
        )
    );
    exit;
}

header("Content-Type: application/json");
echo json_encode([
    "flag" => false,
    "msg" => "Operação inválida"
]);
