<?php
require_once "../model/modelComissoesPsicologo.php";
session_start();

$idUser = $_SESSION["id"] ?? 0;
$op = $_POST["op"] ?? "";

$model = new ModelComissoesPsicologo();

if ($idUser <= 0) {
    echo json_encode(["flag" => false]);
    exit;
}

if ($op === "listar") {
    echo json_encode($model->listarComissoesPsicologo($idUser));
    exit;
}

echo json_encode(["flag" => false]);
exit;
