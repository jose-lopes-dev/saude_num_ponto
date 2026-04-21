<?php
require_once '../model/modelEventos.php';
$rh = new Eventos();

if ($_POST['op'] == 'listar') {
    echo json_encode($rh->listarEventos());

} else if ($_POST['op'] == 'inserir') {
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $data_fim = $_POST['data_fim'] ?? '';
    $data_inicio = $_POST['data_inicio'] ?? $data_fim;
    $categoria = $_POST['categoria'] ?? 'Obrigações Declarativas';
    $res = $rh->inserirEvento($titulo, $descricao, $data_inicio, $data_fim, $categoria);
    echo json_encode(['success' => $res]);

} else if ($_POST['op'] == 'atualizar') {
    $id = intval($_POST['id'] ?? 0);
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $data_fim = $_POST['data_fim'] ?? '';
    $data_inicio = $_POST['data_inicio'] ?? $data_fim;
    $categoria = $_POST['categoria'] ?? 'Obrigações Declarativas';
    $res = $rh->atualizarEvento($id, $titulo, $descricao, $data_inicio, $data_fim, $categoria);
    echo json_encode(['success' => $res]);

} else if ($_POST['op'] == 'concluir') {
    $id = intval($_POST['id'] ?? 0);
    $res = $rh->concluirEvento($id);
    echo json_encode(['success' => $res]);

} else if ($_POST['op'] == 'apagar') {
    $id = intval($_POST['id'] ?? 0);
    $res = $rh->apagarEvento($id);
    echo json_encode(['success' => $res]);

} else {
    echo json_encode(['error' => 'Operação inválida']);
}
?>
