<?php
require_once __DIR__ . '/../model/connection.php';
session_start();

if (!isset($_SESSION['id'])) exit;

$id_utilizador = intval($_SESSION['id']);

$r = $conn->query("
  SELECT codigo
  FROM cliente
  WHERE id_utilizador = $id_utilizador
");

if (!$r || !$r->num_rows) exit;

$id_cliente = intval($r->fetch_assoc()['codigo']);
$op = $_POST['op'] ?? '';

/* ======================
   LISTAR
====================== */
if ($op === 'listar') {

  $out = [];

  $res = $conn->query("
    SELECT 
      c.id,
      c.data_hora,
      s.descricao AS servico,
      r.nome_completo AS profissional
    FROM consulta c
    JOIN servico s ON s.id = c.id_servico
    JOIN rh r ON r.codigo = c.id_prestador
    WHERE c.id_cliente = $id_cliente
  ");

  while ($c = $res->fetch_assoc()) {
    $out[] = [
      'id' => 'c_'.$c['id'],
      'title' => $c['servico'],
      'start' => $c['data_hora'],
      'end' => $c['data_hora'],
      'editable' => false,
      'extendedProps' => [
        'categoria' => 'Consulta',
        'readonly' => true,
        'profissional' => $c['profissional']
      ]
    ];
  }

  $res = $conn->query("
    SELECT *
    FROM calendario_cliente
    WHERE id_cliente = $id_cliente
  ");

  while ($e = $res->fetch_assoc()) {
    $out[] = [
      'id' => 'e_'.$e['id'],
      'title' => $e['titulo'],
      'start' => $e['data_inicio'],
      'end' => $e['data_fim'],
      'editable' => true,
      'extendedProps' => [
        'categoria' => $e['categoria'],
        'descricao' => $e['descricao'],
        'localizacao' => $e['localizacao']
      ]
    ];
  }

  echo json_encode($out);
  exit;
}

/* ======================
   GUARDAR / EDITAR
====================== */
if ($op === 'guardar' || $op === 'editar') {

  $titulo = $conn->real_escape_string($_POST['titulo']);
  $categoria = $conn->real_escape_string($_POST['categoria']);
  $inicio = $_POST['inicio'];
  $fim = $_POST['fim'];
  $descricao = $conn->real_escape_string($_POST['descricao'] ?? '');
  $localizacao = $conn->real_escape_string($_POST['localizacao'] ?? '');

  if (!empty($_POST['id'])) {
    $id = intval($_POST['id']);
    $conn->query("
      UPDATE calendario_cliente
      SET titulo='$titulo',
          categoria='$categoria',
          data_inicio='$inicio',
          data_fim='$fim',
          descricao='$descricao',
          localizacao='$localizacao'
      WHERE id=$id AND id_cliente=$id_cliente
    ");
  } else {
    $conn->query("
      INSERT INTO calendario_cliente
      (id_cliente, titulo, categoria, data_inicio, data_fim, descricao, localizacao)
      VALUES
      ($id_cliente,'$titulo','$categoria','$inicio','$fim','$descricao','$localizacao')
    ");
  }

  exit;
}

/* ======================
   REMOVER
====================== */
if ($op === 'remover') {
  $id = intval($_POST['id']);
  $conn->query("
    DELETE FROM calendario_cliente
    WHERE id=$id AND id_cliente=$id_cliente
  ");
  exit;
}
