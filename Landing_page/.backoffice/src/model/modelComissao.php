<?php
require_once 'connection.php';

class ModelComissao {

  // Lista de comissões
  public function getListaComissoes() {
    global $conn;
    $dados = [];

    $sql = "
      SELECT 
        comissao.id, 
        rh.nome_completo as nome, 
        funcao.descricao AS funcao, 
        comissao.numero_consultas, 
        comissao.total_pagar,
        DATE_FORMAT(comissao.data_prevista, '%Y-%m-%d') AS data_prevista,
        comissao.id_estado
      FROM comissao
      JOIN rh ON comissao.codigo_rh = rh.codigo
      JOIN funcao ON comissao.id_funcao = funcao.id
      ORDER BY comissao.id DESC
    ";

    $res = $conn->query($sql);
    if ($res) {
      while ($r = $res->fetch_assoc()) {
        $r['total_pagar'] = (float)$r['total_pagar'];
        $dados[] = $r;
      }
    }
    return $dados;
  }

  // Gráficos
  public function getDadosGraficos() {
    global $conn;

    $conn->query("SET lc_time_names = 'pt_PT'");

    $dados = [
        'meses' => [],
        'totalPorMes' => [],
        'funcoes' => []
    ];

    
    // 1 - TOTAL POR MÊS
    $sql = "
        SELECT 
    YEAR(data_prevista) AS ano,
    MONTH(data_prevista) AS mes_num,
    DATE_FORMAT(MIN(data_prevista), '%b/%Y') AS mes,
    SUM(total_pagar) AS total
FROM comissao
GROUP BY YEAR(data_prevista), MONTH(data_prevista)
ORDER BY ano, mes_num

    ";

    $res = $conn->query($sql);

    $mapMes = [];

    while ($r = $res->fetch_assoc()) {
        $mes = ucfirst($r['mes']);
        $dados['meses'][] = $mes;
        $dados['totalPorMes'][] = (float)$r['total'];
        $mapMes[$mes] = true;
    }

    // 2 - TOTAL POR FUNÇÃO / MÊS
    $sql = "
        SELECT
    f.descricao AS funcao,
    YEAR(c.data_prevista) AS ano,
    MONTH(c.data_prevista) AS mes_num,
    DATE_FORMAT(MIN(c.data_prevista), '%b/%Y') AS mes,
    SUM(c.total_pagar) AS total
FROM comissao c
JOIN funcao f ON c.id_funcao = f.id
GROUP BY f.descricao, YEAR(c.data_prevista), MONTH(c.data_prevista)
ORDER BY f.descricao, ano, mes_num
    ";

    $res = $conn->query($sql);

    $funcoes = [];

    while ($r = $res->fetch_assoc()) {
        $mes = ucfirst($r['mes']);
        $funcao = $r['funcao'];

        if (!isset($funcoes[$funcao])) {
            $funcoes[$funcao] = array_fill_keys($dados['meses'], 0);
        }

        $funcoes[$funcao][$mes] = (float)$r['total'];
    }

    foreach ($funcoes as $nome => $valores) {
        $dados['funcoes'][] = [
            'nome' => $nome,
            'valores' => array_values($valores)
        ];
    }

    return $dados;
}


  // Marcar comissão como paga
  public function marcarComoPago($id) {
    global $conn;
    $id = (int)$id;
    $ok = $conn->query("UPDATE comissao SET id_estado = 12 WHERE id = $id");
    return ['flag' => $ok, 'msg' => $ok ? 'Comissão marcada como paga!' : 'Erro BD: ' . $conn->error];
  }

  // Lista de salários
  public function getListaSalarios() {
    global $conn;
    $dados = [];

    $sql = "
      SELECT 
        salario.id, 
        rh.nome_completo as nome, 
        funcao.descricao AS funcao,
        salario.salario_bruto, 
        salario.salario_liquido,
        DATE_FORMAT(salario.data_prevista, '%Y-%m-%d') AS data_prevista,
        salario.id_estado
      FROM salario
      JOIN rh ON salario.codigo_rh = rh.codigo
      JOIN funcao ON salario.id_funcao = funcao.id
      ORDER BY salario.id DESC
    ";

    $res = $conn->query($sql);
    if ($res) {
      while ($r = $res->fetch_assoc()) {
        $r['salario_liquido'] = (float)$r['salario_liquido'];
        $dados[] = $r;
      }
    }
    return $dados;
  }

  // Marcar salário como pago
  public function marcarSalarioComoPago($id) {
    global $conn;
    $id = (int)$id;
    $ok = $conn->query("UPDATE salario SET id_estado = 12 WHERE id = $id");
    return ['flag' => $ok, 'msg' => $ok ? 'Salário marcado como pago!' : 'Erro BD: ' . $conn->error];
  }
}
?>
