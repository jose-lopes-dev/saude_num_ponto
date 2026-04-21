<?php

require_once '../model/modelTreino.php';
$treino = new Treino();

// O AJAX envia sempre via POST com campo 'op'
$op = isset($_POST['op']) ? $_POST['op'] : '';

if ($op == 1) {
    // registar (backoffice) — campos esperados
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $duracao_min = $_POST['duracao_min'] ?? 0;
    $nivel = $_POST['nivel'] ?? 'iniciante';
    $grupo = $_POST['grupo_muscular'] ?? '';
    $video_url = $_POST['video_url'] ?? '';
    $thumbnail = $_POST['thumbnail'] ?? '';
    echo $treino->registaTreino($titulo, $descricao, $duracao_min, $nivel, $grupo, $video_url, $thumbnail, 1);
    exit;
}

if ($op == 2) {
    // listar treinos (retorna HTML com os cards)
    echo $treino->getListaTreinos();
    exit;
}

if ($op == 3) {
    // obter treino por id (JSON)
    $id = intval($_POST['id']);
    echo $treino->getTreinoById($id);
    exit;
}

// default
echo json_encode(['flag'=>false, 'msg'=>'Operação inválida']);
