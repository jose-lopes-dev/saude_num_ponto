<?php
require_once 'connection.php';

class ModelDisponibilidade {

  private function json($x){
    return json_encode($x, JSON_UNESCAPED_UNICODE);
  }

  private function validarDateTime($dt){
    $dt = trim((string)$dt);
    if ($dt === '') return false;

    // aceita "YYYY-MM-DD HH:MM" ou "YYYY-MM-DD HH:MM:SS"
    if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $dt)) {
      $dt .= ':00';
    }
    if (!preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $dt)) return false;

    $d = DateTime::createFromFormat('Y-m-d H:i:s', $dt);
    return ($d && $d->format('Y-m-d H:i:s') === $dt) ? $dt : false;
  }

  private function validarHora($h){
    $h = trim((string)$h);
    if ($h === '') return false;

    // aceita HH:MM ou HH:MM:SS
    if (preg_match('/^\d{2}:\d{2}$/', $h)) $h .= ':00';
    if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $h)) return false;

    $d = DateTime::createFromFormat('H:i:s', $h);
    return ($d && $d->format('H:i:s') === $h) ? $h : false;
  }

  private function validarDiaSemana($dia){
    $dia = trim((string)$dia);
    $validos = ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
    return in_array($dia, $validos, true) ? $dia : false;
  }

  // op=1 — listar indisponibilidades no intervalo
  public function listarIntervalo($codigo_rh, $start, $end){
    global $conn;

    $codigo_rh = (int)$codigo_rh;
    $startOk = $this->validarDateTime($start);
    $endOk   = $this->validarDateTime($end);

    if (!$codigo_rh || !$startOk || !$endOk) {
      return $this->json(['ok' => false, 'msg' => 'Parâmetros inválidos', 'rows' => []]);
    }

    $sql = "
      SELECT id,
             DATE_FORMAT(inicio,'%Y-%m-%d %H:%i:%s') AS inicio,
             DATE_FORMAT(fim,'%Y-%m-%d %H:%i:%s')    AS fim,
             motivo
      FROM indisponibilidade_prestador
      WHERE codigo_rh = ?
        AND fim > ?
        AND inicio < ?
      ORDER BY inicio
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $codigo_rh, $startOk, $endOk);
    $stmt->execute();

    $res = $stmt->get_result();
    $out = [];
    while($r = $res->fetch_assoc()) $out[] = $r;

    return $this->json(['ok' => true, 'rows' => $out]);
  }

  // op=2 — criar indisponibilidade
  public function criar($codigo_rh, $inicio, $fim, $motivo){
    global $conn;

    $codigo_rh = (int)$codigo_rh;
    $inicioOk = $this->validarDateTime($inicio);
    $fimOk    = $this->validarDateTime($fim);
    $motivo   = trim((string)$motivo);
    if ($motivo === '') $motivo = null;

    if (!$codigo_rh || !$inicioOk || !$fimOk) {
      return $this->json(['ok' => false, 'msg' => 'Parâmetros inválidos']);
    }

    if (strtotime($fimOk) <= strtotime($inicioOk)) {
      return $this->json(['ok' => false, 'msg' => 'O fim tem de ser maior que o início']);
    }

    $sql = "INSERT INTO indisponibilidade_prestador (codigo_rh, inicio, fim, motivo)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $codigo_rh, $inicioOk, $fimOk, $motivo);
    $ok = $stmt->execute();

    if (!$ok) {
      return $this->json(['ok' => false, 'msg' => 'Erro ao guardar indisponibilidade']);
    }

    return $this->json(['ok' => true, 'id' => $stmt->insert_id]);
  }

  // op=3 — apagar indisponibilidade (só do próprio prestador)
  public function apagar($id, $codigo_rh){
    global $conn;

    $id = (int)$id;
    $codigo_rh = (int)$codigo_rh;
    if (!$id || !$codigo_rh) {
      return $this->json(['ok' => false, 'msg' => 'Parâmetros inválidos']);
    }

    $stmt = $conn->prepare("DELETE FROM indisponibilidade_prestador WHERE id = ? AND codigo_rh = ?");
    $stmt->bind_param("ii", $id, $codigo_rh);
    $ok = $stmt->execute();

    return $this->json(['ok' => $ok ? true : false]);
  }

  // op=4 — listar horário semanal (disponibilidade_prestador)
  public function listarHorarioSemanal($codigo_rh){
    global $conn;

    $codigo_rh = (int)$codigo_rh;
    if (!$codigo_rh) return $this->json(['ok' => false, 'msg' => 'Parâmetros inválidos', 'rows' => []]);

    $sql = "
      SELECT id,
             dia_semana,
             DATE_FORMAT(hora_inicio, '%H:%i:%s') AS hora_inicio,
             DATE_FORMAT(hora_fim,    '%H:%i:%s') AS hora_fim,
             ativo
      FROM disponibilidade_prestador
      WHERE codigo_rh = ?
      ORDER BY FIELD(dia_semana,'Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'),
               hora_inicio
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codigo_rh);
    $stmt->execute();

    $res = $stmt->get_result();
    $rows = [];
    while($r = $res->fetch_assoc()) $rows[] = $r;

    return $this->json(['ok' => true, 'rows' => $rows]);
  }

  // op=5 — guardar horário semanal (substitui tudo)
  public function guardarHorarioSemanal($codigo_rh, $itemsJson){
    global $conn;

    $codigo_rh = (int)$codigo_rh;
    if (!$codigo_rh) return $this->json(['ok' => false, 'msg' => 'Parâmetros inválidos']);

    $items = json_decode($itemsJson, true);
    if (!is_array($items)) return $this->json(['ok' => false, 'msg' => 'Items inválidos']);

    // valida e normaliza
    $norm = [];
    foreach ($items as $it) {
      $dia = $this->validarDiaSemana($it['dia_semana'] ?? '');
      $hi  = $this->validarHora($it['hora_inicio'] ?? '');
      $hf  = $this->validarHora($it['hora_fim'] ?? '');
      $at  = isset($it['ativo']) ? (int)$it['ativo'] : 1;

      // permite linhas incompletas serem ignoradas
      if (!$dia || !$hi || !$hf) continue;

      if (strtotime("1970-01-01 $hf") <= strtotime("1970-01-01 $hi")) {
        return $this->json(['ok' => false, 'msg' => "Hora fim tem de ser maior que hora início ($dia)"]);
      }

      $norm[] = [
        'dia_semana' => $dia,
        'hora_inicio' => $hi,
        'hora_fim' => $hf,
        'ativo' => ($at ? 1 : 0)
      ];
    }

    // guarda: delete + insert em transação
    $conn->begin_transaction();
    try {
      $del = $conn->prepare("DELETE FROM disponibilidade_prestador WHERE codigo_rh = ?");
      $del->bind_param("i", $codigo_rh);
      $del->execute();

      if (count($norm) > 0) {
        $ins = $conn->prepare("
          INSERT INTO disponibilidade_prestador (codigo_rh, dia_semana, hora_inicio, hora_fim, ativo)
          VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($norm as $r) {
          $ins->bind_param("isssi", $codigo_rh, $r['dia_semana'], $r['hora_inicio'], $r['hora_fim'], $r['ativo']);
          $ins->execute();
        }
      }

      $conn->commit();
      return $this->json(['ok' => true]);

    } catch (Exception $e) {
      $conn->rollback();
      return $this->json(['ok' => false, 'msg' => 'Erro ao guardar horário semanal']);
    }
  }

    public function listarEventos($codigo_rh, $start, $end){
    global $conn;

    $codigo_rh = (int)$codigo_rh;
    $startOk = $this->validarDateTime($start);
    $endOk   = $this->validarDateTime($end);

    if (!$codigo_rh || !$startOk || !$endOk) {
      return $this->json(['ok' => false, 'msg' => 'Parâmetros inválidos', 'rows' => []]);
    }

    $sql = "
      SELECT id,
            titulo,
            DATE_FORMAT(inicio,'%Y-%m-%d %H:%i:%s') AS inicio,
            DATE_FORMAT(fim,'%Y-%m-%d %H:%i:%s')    AS fim,
            descricao
      FROM evento_prestador
      WHERE codigo_rh = ?
        AND fim > ?
        AND inicio < ?
      ORDER BY inicio
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $codigo_rh, $startOk, $endOk);
    $stmt->execute();

    $res = $stmt->get_result();
    $rows = [];
    while($r = $res->fetch_assoc()) $rows[] = $r;

    return $this->json(['ok' => true, 'rows' => $rows]);
  }

  public function criarEvento($codigo_rh, $titulo, $inicio, $fim, $descricao){
    global $conn;

    $codigo_rh = (int)$codigo_rh;
    $titulo = trim((string)$titulo);
    $descricao = trim((string)$descricao);

    $inicioOk = $this->validarDateTime($inicio);
    $fimOk    = $this->validarDateTime($fim);

    if (!$codigo_rh || $titulo === '' || !$inicioOk || !$fimOk) {
      return $this->json(['ok' => false, 'msg' => 'Parâmetros inválidos']);
    }

    if (strtotime($fimOk) <= strtotime($inicioOk)) {
      return $this->json(['ok' => false, 'msg' => 'Hora fim tem de ser maior que hora início']);
    }

    $stmt = $conn->prepare("INSERT INTO evento_prestador (codigo_rh, titulo, inicio, fim, descricao) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $codigo_rh, $titulo, $inicioOk, $fimOk, $descricao);
    $ok = $stmt->execute();

    return $this->json(['ok' => $ok ? true : false]);
  }

  public function apagarEvento($id, $codigo_rh){
    global $conn;

    $id = (int)$id;
    $codigo_rh = (int)$codigo_rh;

    if (!$id || !$codigo_rh) return $this->json(['ok' => false, 'msg' => 'Parâmetros inválidos']);

    $stmt = $conn->prepare("DELETE FROM evento_prestador WHERE id = ? AND codigo_rh = ?");
    $stmt->bind_param("ii", $id, $codigo_rh);
    $ok = $stmt->execute();

    return $this->json(['ok' => $ok ? true : false]);
  }
}