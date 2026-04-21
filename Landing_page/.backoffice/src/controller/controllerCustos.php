<?php
require_once __DIR__ . '/../model/modelCustos.php';

$model = new Custos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Custos por mês (donut)
    if (isset($_POST['mes'])) {
        $mes = $_POST['mes'];
        echo $model->custosPorMes($mes);
        exit;
    }

    // Evolução de gastos (line chart)
    if (isset($_POST['op']) && $_POST['op'] === 'evolucaoGastos') {
        echo $model->evolucaoGastos();
        exit;
    }
}

echo json_encode(["erro" => "Requisição inválida"]);
