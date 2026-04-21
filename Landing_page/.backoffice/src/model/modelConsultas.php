<?php
require_once 'connection.php';

class ModelConsultas {

  private function json($x){ 
    return json_encode($x, JSON_UNESCAPED_UNICODE); 
  }

  private function validarDataHora($data, $hora){
    $data = trim((string)$data);
    $hora = trim((string)$hora);

    if ($data === '' || $hora === '') return false;
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) return false;
    if (!preg_match('/^\d{2}:\d{2}$/', $hora)) return false;

    $d = DateTime::createFromFormat('Y-m-d', $data);
    $h = DateTime::createFromFormat('H:i', $hora);

    return ($d && $d->format('Y-m-d') === $data) && ($h && $h->format('H:i') === $hora);
  }

  // op=1 — listar todas
  public function listarTodas(){
    global $conn;
      $sql = "
        SELECT
          id,
          DATE_FORMAT(data_hora,'%Y-%m-%d') AS data,
          DATE_FORMAT(data_hora,'%H:%i') AS hora,
          preco,
          (SELECT nome_completo FROM cliente WHERE codigo = id_cliente) AS cliente,
          (SELECT nome_completo FROM rh WHERE codigo = id_prestador) AS profissional,
          (SELECT descricao FROM servico WHERE id = id_servico) AS servico,
          (SELECT descricao FROM estado WHERE id = id_estado) AS estado
        FROM consulta
        ORDER BY data_hora
      ";
    $res = $conn->query($sql);
    $out = [];
    while($r = $res->fetch_assoc()) $out[] = $r;
    return $this->json($out);
  }

  // op=2 — obter uma consulta
  public function obter($id){
      global $conn;

      $sql = "
        SELECT
          id,
          id_cliente,
          id_prestador,
          id_servico,
          DATE_FORMAT(data_hora,'%Y-%m-%d') AS data,
          DATE_FORMAT(data_hora,'%H:%i') AS hora,
          preco,
          id_estado,
          (SELECT nome_completo FROM cliente WHERE codigo = id_cliente) AS cliente,
          (SELECT nome_completo FROM rh      WHERE codigo = id_prestador) AS profissional,
          (SELECT descricao FROM servico WHERE id = id_servico) AS servico,
          (SELECT descricao FROM estado  WHERE id = id_estado) AS estado
        FROM consulta
        WHERE id = ? LIMIT 1
      ";

      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $id);
      $stmt->execute();
      $row = $stmt->get_result()->fetch_assoc();

      if (!$row) {
          return $this->json([]);
      }

      $extras = [];
      $qe = $conn->prepare("
          SELECT id_servico_extra 
          FROM consulta_servico_extra
          WHERE id_consulta = ?
      ");
      $qe->bind_param("i", $id);
      $qe->execute();
      $resExtras = $qe->get_result();
      while ($e = $resExtras->fetch_assoc()) {
          $extras[] = (int)$e['id_servico_extra'];
      }

      $row['extras'] = $extras;

      return $this->json($row);
  }

  // op=3 — KPIs
  public function kpis($year, $month){
    global $conn;

    $sql = "
      SELECT 
        COUNT(*) AS agendadas,
        SUM(CASE WHEN e.descricao = 'Concluido' THEN 1 ELSE 0 END) AS concluidas,
        COALESCE(SUM(CASE WHEN e.descricao = 'Concluido' THEN c.preco ELSE 0 END),0) AS receita
      FROM consulta c
      LEFT JOIN estado e ON e.id = c.id_estado
      WHERE YEAR(c.data_hora) = $year
        AND MONTH(c.data_hora) = $month
    ";

    $r = $conn->query($sql)->fetch_assoc() ?: [];

    return $this->json([
      'agendadas'  => (int)($r['agendadas'] ?? 0),
      'concluidas' => (int)($r['concluidas'] ?? 0),
      'receita'    => (float)($r['receita'] ?? 0)
    ]);
  }

  // op=4 — eventos do mês
  public function eventosDoMes($year, $month){
      global $conn;

      $sql = "
        SELECT
          DATE_FORMAT(c.data_hora, '%Y-%m-%d') AS date,
          DATE_FORMAT(c.data_hora, '%H:%i')    AS hora,
          cli.nome_completo AS cliente,
          s.descricao AS servico,
          c.preco AS valor,
          e.descricao AS estado
        FROM consulta c
        LEFT JOIN cliente cli ON cli.codigo = c.id_cliente
        LEFT JOIN servico s   ON s.id      = c.id_servico
        LEFT JOIN estado e    ON e.id      = c.id_estado
        WHERE YEAR(c.data_hora) = $year
          AND MONTH(c.data_hora) = $month
        ORDER BY c.data_hora
      ";

      $res = $conn->query($sql);
      $out = [];
      while ($r = $res->fetch_assoc()) $out[] = $r;

      return $this->json($out);
  }

  // op=5 — criar
  public function criar($codigo_cliente, $id_prestador, $id_servico, $id_servico_extra, $data, $hora, $id_estado){
      global $conn;

      $ps = $conn->prepare("SELECT preco FROM servico WHERE id=?");
      $ps->bind_param("i", $id_servico);
      $ps->execute();
      $preco_base = (float)($ps->get_result()->fetch_assoc()['preco'] ?? 0);

      $extras = isset($_POST['extras']) && is_array($_POST['extras'])
          ? $_POST['extras']
          : [];

      $preco_extra = 0.0;

      if (!empty($extras)) {
          $ids = array_map('intval', $extras);
          $ids = array_filter($ids, fn($v) => $v > 0);

          if (!empty($ids)) {
              $placeholders = implode(',', array_fill(0, count($ids), '?'));
              $types        = str_repeat('i', count($ids));

              $sqlExtras = "SELECT SUM(preco) AS total FROM servico WHERE id IN ($placeholders)";
              $stmtExtras = $conn->prepare($sqlExtras);
              $stmtExtras->bind_param($types, ...$ids);
              $stmtExtras->execute();

              $row = $stmtExtras->get_result()->fetch_assoc();
              $preco_extra = (float)($row['total'] ?? 0);
          }
      }

      $preco = $preco_base + $preco_extra;

      if (!$this->validarDataHora($data, $hora)) {
        return $this->json(['ok'=>false, 'msg'=>'Data/Hora inválida.']);
      }

      $hoje = new DateTime('today');
      $dt   = DateTime::createFromFormat('Y-m-d H:i:s', $data.' '.$hora.':00');
      if ($dt < $hoje) {
        return $this->json(['ok'=>false, 'msg'=>'Não é possível marcar consultas em datas passadas.']);
      }

      $data_hora = $data . ' ' . $hora . ':00';

      $stmt = $conn->prepare("
          INSERT INTO consulta (id_cliente, id_prestador, id_servico, data_hora, preco, id_estado)
          VALUES (?, ?, ?, ?, ?, ?)
      ");

      $stmt->bind_param(
          "iiisdi",
          $codigo_cliente,   
          $id_prestador,
          $id_servico,
          $data_hora,
          $preco,
          $id_estado
      );

      $ok = $stmt->execute();
      $idConsulta = $conn->insert_id;

      if ($ok && $idConsulta > 0 && !empty($extras)) {
          $stmtExtra = $conn->prepare("
              INSERT INTO consulta_servico_extra (id_consulta, id_servico_extra)
              VALUES (?, ?)
          ");

          foreach ($extras as $id_extra) {
              $id_extra = (int)$id_extra;
              if ($id_extra <= 0) continue;

              $stmtExtra->bind_param("ii", $idConsulta, $id_extra);
              $stmtExtra->execute();
          }
      }

      return $this->json(['ok'=>$ok, 'id'=>$idConsulta]);
  }

  // op=6 — editar
  public function editar($id, $codigo_cliente, $id_prestador, $id_servico, $id_servico_extra, $data, $hora, $id_estado){
      global $conn;

      $id = (int)$id;

      $ps = $conn->prepare("SELECT preco FROM servico WHERE id=?");
      $ps->bind_param("i", $id_servico);
      $ps->execute();
      $preco_base = (float)($ps->get_result()->fetch_assoc()['preco'] ?? 0);

      $extras = isset($_POST['extras']) && is_array($_POST['extras'])
          ? $_POST['extras']
          : [];

      $preco_extra = 0.0;

      if (!empty($extras)) {
          $ids = array_map('intval', $extras);
          $ids = array_filter($ids, fn($v) => $v > 0);

          if (!empty($ids)) {
              $placeholders = implode(',', array_fill(0, count($ids), '?'));
              $types        = str_repeat('i', count($ids));

              $sqlExtras = "SELECT SUM(preco) AS total FROM servico WHERE id IN ($placeholders)";
              $stmtExtras = $conn->prepare($sqlExtras);
              $stmtExtras->bind_param($types, ...$ids);
              $stmtExtras->execute();

              $row = $stmtExtras->get_result()->fetch_assoc();
              $preco_extra = (float)($row['total'] ?? 0);
          }
      }

      $preco = $preco_base + $preco_extra;

      if (!$this->validarDataHora($data, $hora)) {
        return $this->json(['ok'=>false, 'msg'=>'Data/Hora inválida.']);
      }

      $data_hora = $data . ' ' . $hora . ':00';

      $stmt = $conn->prepare("
          UPDATE consulta
          SET id_cliente=?,
              id_prestador=?,
              id_servico=?,
              data_hora=?,
              preco=?,
              id_estado=?
          WHERE id=?
      ");

      $stmt->bind_param(
          "iiisdii",
          $codigo_cliente, 
          $id_prestador,
          $id_servico,
          $data_hora,
          $preco,
          $id_estado,
          $id
      );

      $ok = $stmt->execute();

      $del = $conn->prepare("DELETE FROM consulta_servico_extra WHERE id_consulta = ?");
      $del->bind_param("i", $id);
      $del->execute();

      if (!empty($extras)) {
          $stmtExtra = $conn->prepare("
              INSERT INTO consulta_servico_extra (id_consulta, id_servico_extra)
              VALUES (?, ?)
          ");

          foreach ($extras as $id_extra) {
              $id_extra = (int)$id_extra;
              if ($id_extra <= 0) continue;

              $stmtExtra->bind_param("ii", $id, $id_extra);
              $stmtExtra->execute();
          }
      }

      return $this->json(['ok'=>$ok]);
  }

  // op=7 — apagar
  public function apagar($id){
    global $conn;
    $id = (int)$id;
    $ok = $conn->query("DELETE FROM consulta WHERE id=$id");
    return $this->json(['ok'=>$ok]);
  }

  // op=8 — clientes
  public function clientes($q=''){
    global $conn;
    $stmt = $conn->prepare("
      SELECT codigo AS id, nome_completo AS nome
      FROM cliente
      WHERE nome_completo LIKE CONCAT('%', ?, '%')
      ORDER BY nome_completo
      LIMIT 50
    ");
    $stmt->bind_param("s", $q);
    $stmt->execute();
    $res = $stmt->get_result();
    $out = [];
    while($r = $res->fetch_assoc()){
      $r['id'] = (int)$r['id'];
      $out[] = $r;
    }
    return $this->json($out);
  }

  // op=9 — profissionais
  public function profissionais($q=''){
    global $conn;
    $stmt = $conn->prepare("
      SELECT codigo AS id, nome_completo AS nome
      FROM rh
      WHERE nome_completo LIKE CONCAT('%', ?, '%')
      ORDER BY nome_completo
      LIMIT 50
    ");
    $stmt->bind_param("s", $q);
    $stmt->execute();
    $res = $stmt->get_result();
    $out = [];
    while($r = $res->fetch_assoc()){
      $r['id'] = (int)$r['id'];
      $out[] = $r;
    }
    return $this->json($out);
  }

  // op=10 — listar com paginação
  public function listarPagina(int $lim, int $off, string $q) : string {
    global $conn;

    $where = "(? = '' 
      OR (SELECT nome_completo FROM cliente WHERE codigo = c.id_cliente) LIKE CONCAT('%', ?, '%')
      OR (SELECT nome_completo FROM rh      WHERE codigo = c.id_prestador) LIKE CONCAT('%', ?, '%')
      OR (SELECT descricao FROM servico    WHERE id = c.id_servico) LIKE CONCAT('%', ?, '%')
    )";

    $sqlTot = "SELECT COUNT(*) tot FROM consulta c WHERE $where";

    $stTot = $conn->prepare($sqlTot);
    $stTot->bind_param('ssss', $q, $q, $q, $q);
    $stTot->execute();
    $tot = (int)($stTot->get_result()->fetch_assoc()['tot'] ?? 0);
    $stTot->close();

    $sql = "
      SELECT
        c.id,
        (SELECT nome_completo FROM cliente WHERE codigo = c.id_cliente) AS cliente,
        (SELECT nome_completo FROM rh WHERE codigo = c.id_prestador) AS profissional,
        (SELECT descricao FROM servico WHERE id = c.id_servico) AS servico,
        DATE_FORMAT(c.data_hora, '%Y-%m-%d') AS data,
        DATE_FORMAT(c.data_hora, '%H:%i') AS hora,
        c.preco AS valor,
        (SELECT descricao FROM estado WHERE id = c.id_estado) AS estado
      FROM consulta c
      WHERE $where
      ORDER BY c.data_hora DESC
      LIMIT ? OFFSET ?;
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssii', $q,$q,$q,$q,$lim,$off);
    $stmt->execute();

    $rows = [];
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) $rows[] = $r;

    return json_encode(['rows'=>$rows, 'total'=>$tot]);
  }

  // op=11 — obter preço do serviço
  public function precoServico($id_servico){
    global $conn;
    $stmt = $conn->prepare("SELECT preco FROM servico WHERE id=?");
    $stmt->bind_param("i", $id_servico);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $this->json(['ok'=>true, 'preco'=>(float)($row['preco'] ?? 0)]);
  }

  // op=12 — obter preços dos extras
  public function precoExtras($ids) {
      global $conn;
      if (!is_array($ids) || count($ids) == 0) {
          return $this->json(['total' => 0]);
      }

      // filtrar para inteiros
      $ids = array_map('intval', $ids);
      $lista = implode(',', $ids);

      $sql = "SELECT SUM(preco) AS total FROM servico WHERE id IN ($lista)";
      $res = $conn->query($sql);
      $row = $res->fetch_assoc();

      return $this->json(['total' => (float)($row['total'] ?? 0)]);
  }
}
