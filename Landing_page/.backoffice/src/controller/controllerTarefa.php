<?php
require_once __DIR__ . '/../model/modelTarefa.php';
$tarefa = new Tarefa();

$op = $_POST['op'] ?? '';

if ($op == 'listar') {
    echo $tarefa->listar();
}

else if ($op == 'guardar') {
    echo $tarefa->guardar(
        $_POST['id'] ?? '',
        $_POST['id_tipo_obrigacao'],
        $_POST['descricao'],
        $_POST['valor'],
        $_POST['data_vencimento'],
        $_POST['data_pagamento'],
        $_POST['id_estado']
    );
}

else if ($op == 'editar') {
    echo $tarefa->editar($_POST['id']);
}

else if ($op == 'concluir') {
    echo $tarefa->concluir($_POST['id']);
}

else if ($op == 'estados') {
    echo $tarefa->listarEstados();
}

else if ($op == 'tipos') {
    echo $tarefa->listarTipos();
}
?>
