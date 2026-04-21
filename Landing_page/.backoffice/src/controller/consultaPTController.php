<?php
session_start();
require_once "../model/ConsultaPTModel.php";
require_once "../model/connection.php";

$model = new ConsultaPTModel();
$acao = $_POST["acao"] ?? "";

if ($acao === "sessionPT") {
    echo json_encode([
        "id" => $_SESSION["id"] ?? 0
    ]);
    exit;
}

if (!isset($_SESSION["id"])) {
    echo json_encode([]);
    exit;
}

$id_utilizador = $_SESSION["id"];
$id_prestador = $model->getCodigoPrestador($id_utilizador);

if (!$id_prestador) {
    echo json_encode([
        "pendentes" => [],
        "aceites" => [],
        "recusadas" => []
    ]);
    exit;
}

if ($acao === "listar") {
    echo json_encode([
        "pendentes" => $model->listarPorEstado($id_prestador, 13),
        "aceites" => $model->listarPorEstado($id_prestador, 15),
        "recusadas" => $model->listarPorEstado($id_prestador, 4)
    ]);
    exit;
}

if ($acao === "proximasConsultasPT") {
    echo json_encode(
        $model->proximasConsultasPT($id_prestador)
    );
    exit;
}


if ($acao === "aceitar" || $acao === "recusar") {

    $idConsulta = (int) $_POST["id_consulta"];
    $estado = $acao === "aceitar" ? 15 : 4;

    $model->atualizarEstado($idConsulta, $estado, $id_prestador);
    $model->notificarClienteEstado($idConsulta, $estado);
    
    echo json_encode(["status" => "success"]);
    exit;

    }

    if ($acao === "calendarPT") {

    $year  = (int)($_POST["year"] ?? 0);
    $month = (int)($_POST["month"] ?? 0);

    if (!$year || !$month) {
        echo json_encode([]);
        exit;
    }

    echo json_encode(
        $model->listarConsultasCalendarioPT($id_prestador, $year, $month)
    );
    exit;
}


echo json_encode(["erro" => "acao_invalida"]);
exit;
