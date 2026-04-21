<?php
header("Content-Type: application/json; charset=UTF-8");

session_start(); 

require_once "../model/ConsultaModel.php";

$model = new ConsultaModel();
$acao = $_POST['acao'] ?? '';

if ($acao === "sessionCliente") {
    echo json_encode([
        "id" => $_SESSION["id"] ?? 0
    ]);
    exit;
}

if ($acao === "prestadores") {

    $id_servico = intval($_POST["id_servico"] ?? 0);
    echo json_encode($model->listarPrestadores($id_servico));
    exit;
}

if ($acao === "marcar") {

    $id_utilizador = intval($_POST["id_cliente"] ?? 0);
    $id_cliente = $model->buscarIdCliente($id_utilizador);

    if (!$id_cliente) {
        echo json_encode([
            "status" => "error",
            "msg" => "Cliente não encontrado."
        ]);
        exit;
    }

    $id_prestador = intval($_POST["id_prestador"] ?? 0);
    $id_servico   = intval($_POST["id_servico"] ?? 0);
    $data_hora    = $_POST["data_hora"] ?? "";

    echo json_encode(
        $model->marcarConsulta(
            $id_cliente,
            $id_prestador,
            $id_servico,
            $data_hora
        )
    );
    exit;
}

if ($acao === "proximasConsultasCliente") {

    $id_utilizador = $_SESSION["id"] ?? 0;
    if (!$id_utilizador) {
        echo json_encode([]);
        exit;
    }

    $id_cliente = $model->buscarIdCliente($id_utilizador);
    if (!$id_cliente) {
        echo json_encode([]);
        exit;
    }

    echo json_encode(
        $model->proximasConsultasCliente($id_cliente)
    );
    exit;
}

echo json_encode([
    "status" => "error",
    "msg" => "Ação inválida"
]);
exit;
