<?php
require_once __DIR__ . '/../model/modelVendas_nutricionista.php';

session_start();

if (!isset($_SESSION["id"])) {
    header("Content-Type: application/json");
    echo json_encode([
        "flag" => false,
        "msg" => "Não autenticado"
    ]);
    exit;
}

$model = new ModelVendasNutricionista();
$op = isset($_POST["op"]) ? (int)$_POST["op"] : 0;

if ($op === 1) {
    header("Content-Type: text/html; charset=UTF-8");
    echo $model->getClientes($_SESSION["id"]);
    exit;
}

if ($op === 6) {
    header("Content-Type: text/html; charset=UTF-8");
    echo $model->getConsultas(
        $_SESSION["id"],
        $_POST["id_cliente"] ?? 0
    );
    exit;
}



if ($op === 3) {
    header("Content-Type: application/json");
    echo json_encode(
        $model->registarVenda(
            $_SESSION["id"],
            $_POST
        )
    );
    exit;
}

if ($op === 4) {
    header("Content-Type: text/html; charset=UTF-8");
    echo $model->listarVendas($_SESSION["id"]);
    exit;
}

if ($op === 5) {
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
