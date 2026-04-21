<?php
require_once 'connection.php';

class ModelAtivos {

  // Listar todos os ativos
  public function listar() {
    global $conn;
    $sql = "SELECT id, descricao, id_categoria, valor_inicial, data_aquisicao, vida_util_meses
            FROM ativo
            ORDER BY id DESC";
    $res = $conn->query($sql);

    $ativos = [];
    while ($r = $res->fetch_assoc()) {
      // Buscar o nome da categoria
      $cat_res = $conn->query("SELECT categoria FROM tipo_ativo WHERE id = ".$r['id_categoria']." LIMIT 1");
      $cat = $cat_res ? $cat_res->fetch_assoc()['categoria'] ?? '' : '';
      $r['categoria_nome'] = $cat;

      // Calcular depreciação e justo valor
      $dep_res = $conn->query("SELECT SUM(valor_depreciacao) AS total FROM depreciacao WHERE id_ativo = ".$r['id']);
      $dep = $dep_res ? $dep_res->fetch_assoc()['total'] ?? 0 : 0;

      $r['dep_acumulada'] = $dep;
      $r['justo_valor'] = max($r['valor_inicial'] - $dep, 0);

      $ativos[] = $r;
    }

    return json_encode($ativos, JSON_UNESCAPED_UNICODE);
  }

  // Detalhar um ativo
  public function detalhe($id) {
    global $conn;
    $id = (int)$id;
    $sql = "SELECT id, descricao, id_categoria, valor_inicial, data_aquisicao, vida_util_meses
            FROM ativo WHERE id = $id LIMIT 1";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();

    if (!$row) {
      return json_encode(["ok" => false, "msg" => "Ativo não encontrado"]);
    }

    $row['vida_util'] = floor($row['vida_util_meses'] / 12);
    return json_encode($row, JSON_UNESCAPED_UNICODE);
  }

  // Listar categorias
  public function categorias() {
    global $conn;
    $res = $conn->query("SELECT id, categoria FROM tipo_ativo ORDER BY categoria");
    $dados = [];
    while ($r = $res->fetch_assoc()) {
      $dados[] = $r;
    }
    return json_encode($dados, JSON_UNESCAPED_UNICODE);
  }

  // Criar ativo
  public function criar($post) {
    global $conn;
    $nome = trim($post['nome'] ?? '');
    $valor = (float)($post['valor_inicial'] ?? 0);
    $vida_anos = (int)($post['vida_util'] ?? 0);
    $id_categoria = (int)($post['id_categoria'] ?? 0);
    $data_aq = $post['data_aquisicao'] ?? '';

    if ($nome === '' || $valor <= 0 || $vida_anos <= 0 || $id_categoria <= 0 || $data_aq === '') {
      return json_encode(["ok" => false, "msg" => "Dados inválidos"]);
    }

    $vida_meses = $vida_anos * 12;

    $sql = "INSERT INTO ativo (descricao, id_categoria, valor_inicial, data_aquisicao, vida_util_meses)
            VALUES ('$nome', $id_categoria, $valor, '$data_aq', $vida_meses)";
    $ok = $conn->query($sql);

    return json_encode(["ok" => $ok]);
  }

  // Atualizar ativo
  public function atualizar($post) {
    global $conn;
    $id = (int)($post['id'] ?? 0);
    if ($id <= 0) return json_encode(["ok" => false, "msg" => "ID inválido"]);

    $updates = [];

    if (!empty($post['nome'])) $updates[] = "descricao = '".trim($post['nome'])."'";
    if (!empty($post['valor_inicial'])) $updates[] = "valor_inicial = ".(float)$post['valor_inicial'];
    if (!empty($post['vida_util'])) $updates[] = "vida_util_meses = ".((int)$post['vida_util'] * 12);
    if (!empty($post['id_categoria'])) $updates[] = "id_categoria = ".(int)$post['id_categoria'];
    if (!empty($post['data_aquisicao'])) $updates[] = "data_aquisicao = '".$post['data_aquisicao']."'";

    if (empty($updates)) return json_encode(["ok" => false, "msg" => "Sem alterações"]);

    $sql = "UPDATE ativo SET ".implode(", ", $updates)." WHERE id = $id";
    $ok = $conn->query($sql);

    return json_encode(["ok" => $ok]);
  }

  // Apagar ativo
  public function apagar($id) {
    global $conn;
    $id = (int)$id;
    if ($id <= 0) return json_encode(["ok" => false, "msg" => "ID inválido"]);

    $sql = "DELETE FROM ativo WHERE id = $id";
    $ok = $conn->query($sql);

    return json_encode(["ok" => $ok]);
  }

  // Gráficos simples
  public function charts() {
    global $conn;

    $bar = [];
    $res = $conn->query("SELECT descricao, valor_inicial FROM ativo ORDER BY id");
    while ($r = $res->fetch_assoc()) $bar[] = $r;

    $donut = [];
    $res = $conn->query("SELECT categoria, COUNT(*) AS total FROM tipo_ativo GROUP BY categoria");
    while ($r = $res->fetch_assoc()) $donut[] = $r;

    $line = [];
    $res = $conn->query("SELECT mes_referencia AS mes, SUM(valor_depreciacao) AS total
                         FROM depreciacao GROUP BY mes_referencia ORDER BY mes");
    while ($r = $res->fetch_assoc()) $line[] = $r;

    return json_encode(["ok" => true, "bar" => $bar, "donut" => $donut, "line" => $line], JSON_UNESCAPED_UNICODE);
  }

  // Timeseries simples
  public function timeseries($id) {
    global $conn;
    $id = (int)$id;
    if ($id <= 0) return json_encode(["ok" => false, "msg" => "ID inválido"]);

    $res = $conn->query("SELECT valor_inicial, data_aquisicao, vida_util_meses FROM ativo WHERE id = $id LIMIT 1");
    $a = $res->fetch_assoc();

    if (!$a) return json_encode(["ok" => false, "msg" => "Ativo não encontrado"]);

    $valor = $a['valor_inicial'];
    $vida = max((int)$a['vida_util_meses'], 1);
    $quota = $valor / $vida;
    $data = $a['data_aquisicao'] ?: date('Y-m-01');

    $serie = [];
    $cursor = new DateTime($data);

    for ($i = 0; $i < $vida; $i++) {
      $valor -= $quota;
      if ($valor < 0) $valor = 0;
      $serie[] = ["x" => $cursor->format('Y-m-01'), "y" => round($valor, 2)];
      $cursor->add(new DateInterval('P1M'));
    }

    return json_encode(["ok" => true, "serie" => $serie], JSON_UNESCAPED_UNICODE);
  }
}
?>
