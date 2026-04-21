<?php
require_once "../model/ConsultaPsicologoModel.php";
require_once "../model/connection.php";
session_start();

$model = new ConsultaPsicologoModel();
$acao = $_POST["acao"] ?? "";
$idUser = $_SESSION["id"] ?? 0;

if ($acao === "sessionPsicologo") {
    echo json_encode(["id" => $idUser]);
    exit;
}

if ($acao === "dashboard") {

    if ($idUser <= 0) {
        echo json_encode(["erro" => "sessao"]);
        exit;
    }

    echo json_encode([
        "kpis" => $model->dashboardKPIs($idUser),
        "sessoesHoje" => $model->dashboardHoje($idUser),
        "ultimosPacientes" => $model->dashboardUltimosPacientes($idUser),
        "grafico" => $model->dashboardGraficoSemanal($idUser),
        "pendentes" => $model->listarSessoesPsicologo($idUser)["pendentes"]
    ]);
    exit;
}

if ($acao === "listar") {
    echo json_encode($model->listarSessoesPsicologo($idUser));
    exit;
}

if ($acao === "aceitar" || $acao === "recusar") {

    $idConsulta = (int) $_POST["id_consulta"];
   $estado = $acao === "aceitar" ? 15 : 4;


    $model->atualizarEstado($idConsulta, $estado, $idUser);
    $model->notificarClienteEstado($idConsulta, $estado);

    echo json_encode(["status" => "success"]);
    exit;
}

echo json_encode(["erro" => "acao_invalida"]);
exit;
