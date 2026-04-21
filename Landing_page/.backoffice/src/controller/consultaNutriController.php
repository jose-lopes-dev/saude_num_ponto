<?php
require_once "../model/ConsultaNutriModel.php";
require_once "../model/connection.php";
session_start();

$model = new ConsultaNutriModel();
$acao = $_POST["acao"] ?? "";
$idUser = $_SESSION["id"] ?? 0;


/* =====================
   VALIDAR SESSÃO
===================== */
if ($acao === "sessionNutri") {
    echo json_encode(["id" => $idUser]);
    exit;
}


/* =====================
   DASHBOARD
===================== */
if ($acao === "dashboard") {

    if ($idUser <= 0) {
        echo json_encode(["erro" => "sessao"]);
        exit;
    }

    echo json_encode([
        "kpis" => $model->dashboardKPIs($idUser),
        "consultasHoje" => $model->dashboardHoje($idUser),
        "ultimosClientes" => $model->dashboardUltimosClientes($idUser),
        "grafico" => $model->dashboardGraficoSemanal($idUser),

        // ✅ CORRIGIDO AQUI
        "pendentes" => $model->listarConsultasNutri($idUser, 13, 1, 5)["dados"]
    ]);

    exit;
}


/* =====================
   LISTAR CONSULTAS (PÁGINA CONSULTAS)
===================== */
if ($acao === "listar") {

    $estado = (int)$_POST["estado"];
    $page   = (int)($_POST["page"] ?? 1);

    echo json_encode(
        $model->listarConsultasNutri($idUser, $estado, $page, 5)
    );

    exit;
}


/* =====================
   ACEITAR / RECUSAR
===================== */
if ($acao === "aceitar" || $acao === "recusar") {

    $idConsulta = (int) $_POST["id_consulta"];
    $estado = $acao === "aceitar" ? 15 : 4;

    $model->atualizarEstado($idConsulta, $estado);
    $model->notificarClienteEstado($idConsulta, $estado);

    echo json_encode(["status" => "success"]);
    exit;
}


/* =====================
   PRÓXIMAS CONSULTAS
===================== */
if ($acao === "proximasConsultasNutri") {

    if ($idUser <= 0) {
        echo json_encode([]);
        exit;
    }

    echo json_encode(
        $model->proximasConsultasNutri($idUser)
    );

    exit;
}


echo json_encode(["erro" => "acao_invalida"]);
exit;
