<?php
require_once __DIR__ . '/../model/modelPlanoNutricionista.php';
session_start();

header("Content-Type: application/json");

if (!isset($_SESSION["id"])) {
    echo json_encode(["flag" => false, "msg" => "Não autenticado"]);
    exit;
}

$nutricionista = $_SESSION["id"];
$model = new PlanoNutricionista();

if (isset($_POST["op"])) {

    if ($_POST["op"] === "clientes") {
        echo json_encode([
            "flag" => true,
            "html" => $model->listarClientes()
        ]);
        exit;
    }

    if ($_POST["op"] === "enviados") {
        echo json_encode([
            "flag" => true,
            "html" => $model->listarPlanosEnviados($nutricionista)
        ]);
        exit;
    }
}

if (isset($_FILES["ficheiro"]) && isset($_POST["cliente"])) {
    echo json_encode(
        $model->enviarPlano(
            $nutricionista,
            $_POST["cliente"],
            $_FILES["ficheiro"]
        )
    );
    exit;
}

echo json_encode(["flag" => false, "msg" => "Pedido inválido"]);
