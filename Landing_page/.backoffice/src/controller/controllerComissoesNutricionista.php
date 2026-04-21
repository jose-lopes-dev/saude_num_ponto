<?php
require_once __DIR__ . '/../model/modelComissoesNutricionista.php';

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode([
        'flag' => false,
        'msg' => 'Não autenticado'
    ]);
    exit;
}

$op = $_POST['op'] ?? null;

$model = new ModelComissoesNutricionista();

if ($op === 'listar') {
    $dados = $model->listarPorNutricionista($_SESSION['id']);

    if ($dados === false) {
        echo json_encode([
            'flag' => false,
            'msg' => 'Nutricionista sem código RH'
        ]);
        exit;
    }

    $estadoFiltro = $_POST['estado'] ?? '';

    $lista = [];

    foreach ($dados['pendentes'] as $c) {
        if ($estadoFiltro === 'pendente' && $c['id_estado'] != 13) continue;
        if ($estadoFiltro === 'paga' && $c['id_estado'] != 12) continue;

        $lista[] = $c;
    }

    $totalGanho = 0;
    $totalRecebido = 0;

    foreach ($lista as $c) {
        $valor = (float)$c['valor_comissao'];
        $totalGanho += $valor;
        if ($c['id_estado'] == 12) {
            $totalRecebido += $valor;
        }
    }

    echo json_encode([
        'flag' => true,
        'pendentes' => $lista,
        'kpis' => [
            'total_ganho' => round($totalGanho, 2),
            'total_recebido' => round($totalRecebido, 2),
            'total_por_receber' => round($totalGanho - $totalRecebido, 2)
        ]
    ]);
} elseif ($op === 'marcarPago') {
    echo $model->marcarPago($_POST['id']);
} elseif ($op === 'sync') {
    echo $model->syncComissoes($_SESSION['id']);
} elseif ($op === 'resumo') {
    echo $model->getResumoComissoes($_SESSION['id'], $_POST['estado'] ?? '');
} else {
    echo json_encode([
        'flag' => false,
        'msg' => 'Operação inválida'
    ]);
}
