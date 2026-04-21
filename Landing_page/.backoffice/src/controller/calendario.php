<?php


// Ligação à base de dados
$conn = new mysqli('localhost', 'root', '', 'database_aio');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'msg' => 'Erro de ligação à base de dados.']);
    exit;
}
$conn->set_charset('utf8mb4');

$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

// === LISTAR EVENTOS ===
if ($acao === 'listar') {
    $sql = "SELECT id, titulo, descricao, data_inicio, data_fim, categoria, localizacao, concluido FROM evento";
    $res = $conn->query($sql);
    $eventos = [];

    while ($row = $res->fetch_assoc()) {
        $categoria = $row['categoria'] ?? 'Outros';

        // Define a cor conforme a categoria
        $classe = 'bg-secondary-subtle';
        if ($categoria === 'Urgente') $classe = 'bg-danger-subtle';
        elseif ($categoria === 'Concluído') $classe = 'bg-success-subtle';
        elseif ($categoria === 'Reuniões') $classe = 'bg-primary-subtle';
        elseif ($categoria === 'Obrigações Declarativas') $classe = 'bg-info-subtle';

        // Se estiver concluído, fica verde
        if ((int)$row['concluido'] === 1) {
            $classe = 'bg-success-subtle';
        }

        $eventos[] = [
            'id' => (string)$row['id'],
            'title' => $row['titulo'],
            'start' => $row['data_inicio'],
            'end' => $row['data_fim'],
            'classNames' => [$classe],
            'extendedProps' => [
                'descricao' => $row['descricao'] ?? '',
                'categoria' => $categoria,
                'localizacao' => $row['localizacao'] ?? '',
                'concluido' => (int)$row['concluido']
            ]
        ];
    }

    echo json_encode($eventos);
    exit;
}

// === ADICIONAR EVENTO ===
if ($acao === 'adicionar') {
    $titulo = trim($_POST['nome_evento'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_fim = $_POST['data_fim'] ?? '';
    $categoria = trim($_POST['categoria'] ?? '');
    $localizacao = trim($_POST['localizacao'] ?? '');
    $concluido = 0;

    $stmt = $conn->prepare("INSERT INTO evento (titulo, descricao, data_inicio, data_fim, categoria, localizacao, concluido) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $titulo, $descricao, $data_inicio, $data_fim, $categoria, $localizacao, $concluido);
    $ok = $stmt->execute();

    echo json_encode([
        'status' => $ok ? 'success' : 'error',
        'msg' => $ok ? 'Evento adicionado com sucesso.' : 'Erro ao adicionar evento.'
    ]);
    exit;
}

// === EDITAR EVENTO ===
if ($acao === 'editar') {
    $id = intval($_POST['id'] ?? 0);
    $titulo = $_POST['nome_evento'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $data_inicio = $_POST['data_inicio'] ?? '';
    $data_fim = $_POST['data_fim'] ?? '';
    $categoria = $_POST['categoria'] ?? 'Outros';
    $localizacao = $_POST['localizacao'] ?? '';
    $concluido = intval($_POST['concluido'] ?? 0);

    $stmt = $conn->prepare("UPDATE evento SET titulo=?, descricao=?, data_inicio=?, data_fim=?, categoria=?, localizacao=?, concluido=? WHERE id=?");
    $stmt->bind_param("ssssssii", $titulo, $descricao, $data_inicio, $data_fim, $categoria, $localizacao, $concluido, $id);
    $ok = $stmt->execute();

    echo json_encode([
        'status' => $ok ? 'success' : 'error',
        'msg' => $ok ? 'Evento atualizado com sucesso.' : 'Erro ao atualizar evento.'
    ]);
    exit;
}

// === REMOVER EVENTO ===
if ($acao === 'remover') {
    $id = intval($_POST['id'] ?? 0);
    $stmt = $conn->prepare("DELETE FROM evento WHERE id=?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();

    echo json_encode([
        'status' => $ok ? 'success' : 'error',
        'msg' => $ok ? 'Evento removido com sucesso.' : 'Erro ao remover evento.'
    ]);
    exit;
}

// === AÇÃO INVÁLIDA ===
echo json_encode(['status' => 'error', 'msg' => 'Ação inválida.']);
exit;
?>
