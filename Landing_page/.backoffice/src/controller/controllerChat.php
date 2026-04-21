<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../model/modelChat.php';
$model = new ModelChat();

$op = (int)($_POST['op'] ?? 0);
$userId = (int)($_POST['userId'] ?? 0);
$outroId = (int)($_POST['outroId'] ?? 0);

if ($op === 1) {
  echo json_encode($model->conversas($userId));
  exit;
}

if ($op === 2) {
  $afterId = (int)($_POST['afterId'] ?? 0);
  echo json_encode($model->mensagens($userId,$outroId,$afterId));
  exit;
}

if ($op === 3) {
  $msg = trim($_POST['msg'] ?? '');
  echo json_encode(['ok'=>$model->enviar($userId,$outroId,$msg)]);
  exit;
}

if ($op === 4) {
  $model->marcarLidas($userId,$outroId);
  echo json_encode(['ok'=>true]);
  exit;
}

if ($op === 6) {
  echo json_encode($model->searchUsers(trim($_POST['q'] ?? '')));
  exit;
}

echo json_encode([]);
