<?php
// src/model/modelEventos.php
require_once __DIR__ . '/connection.php';

class Eventos {

  private function toDateTimeOrNull($s) {
    if ($s === null || $s === '') return null;
    $s = str_replace('T', ' ', $s);
    // se veio no formato "YYYY-MM-DD HH:MM" acrescenta segundos
    if (strlen($s) === 16) $s .= ':00';
    return $s;
  }

  public function listarEventos() {
    global $conn;
    $sql = "SELECT id, titulo, descricao, data_inicio, data_fim, categoria, COALESCE(concluido, 0) AS concluido, localizacao
            FROM evento
            ORDER BY data_inicio DESC";
    $res = $conn->query($sql);
    $out = [];
    while ($r = $res->fetch_assoc()) $out[] = $r;
    return $out;
  }

  public function inserirEvento($titulo, $descricao, $data_inicio, $data_fim, $categoria) {
    global $conn;
    $data_inicio = $this->toDateTimeOrNull($data_inicio);
    $data_fim = $this->toDateTimeOrNull($data_fim);

    $stmt = $conn->prepare("INSERT INTO evento (titulo, descricao, data_inicio, data_fim, categoria, concluido) VALUES (?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("sssss", $titulo, $descricao, $data_inicio, $data_fim, $categoria);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
  }

  public function atualizarEvento($id, $titulo, $descricao, $data_inicio, $data_fim, $categoria) {
    global $conn;
    $data_inicio = $this->toDateTimeOrNull($data_inicio);
    $data_fim = $this->toDateTimeOrNull($data_fim);
    $stmt = $conn->prepare("UPDATE evento SET titulo=?, descricao=?, data_inicio=?, data_fim=?, categoria=? WHERE id=? AND COALESCE(concluido,0)=0");
    $stmt->bind_param("sssssi", $titulo, $descricao, $data_inicio, $data_fim, $categoria, $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
  }

  public function concluirEvento($id) {
    global $conn;
    $stmt = $conn->prepare("UPDATE evento SET concluido=1 WHERE id=?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
  }

  public function apagarEvento($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM evento WHERE id=?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
  }
}
