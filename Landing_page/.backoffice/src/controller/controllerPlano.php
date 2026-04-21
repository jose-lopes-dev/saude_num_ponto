<?php
require_once __DIR__ . '/../model/modelPlano.php';
session_start();

if (!isset($_SESSION["id"])) {
    echo json_encode(["flag" => false, "msg" => "Não autenticado"]);
    exit;
}

$plano = new Plano();
$user_id = $_SESSION["id"];
$op = $_POST["op"] ?? null;

if ($op == 1) {
    echo $plano->criarPlano($user_id, $_POST["titulo"], $_POST["ingredientes"]);
    exit;
}

if ($op == 2) {
    echo $plano->listarPlanos($user_id);
    exit;
}

if ($op == 3) {
    echo json_encode($plano->verPlano($_POST["id"]));
    exit;
}

if ($op == 4) {
    echo json_encode($plano->removerPlano($_POST["id"]));
    exit;
}

if ($op == 10) {
    echo $plano->listarFicheirosRecebidos($user_id);
    exit;
}

echo json_encode(["flag" => false, "msg" => "Operação inválida"]);
