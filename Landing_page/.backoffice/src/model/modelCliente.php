<?php
require_once 'connection.php';

class ModelClientes
{

    private function json($x)
    {
        return json_encode($x, JSON_UNESCAPED_UNICODE);
    }

    // Mantém o nome original
    public function getDashboardClientes()
    {
        global $conn;

        $dados = [
            "kpis" => [],
            "total" => [],
            "novosRecorrentes" => [],
            "receitaMedia" => [],
            "crescimento" => []
        ];

        // KPIs
        $dados['kpis'] = $this->getKPIsClientes();

        // --- Total de clientes por mês ---
        $sql = "
            SELECT MONTH(data_venda) AS mes, COUNT(DISTINCT id_cliente) AS total
            FROM venda
            GROUP BY MONTH(data_venda)
            ORDER BY MONTH(data_venda)
        ";
        $res = $conn->query($sql);
        if ($res) {
            while ($r = $res->fetch_assoc())
                $dados['total'][] = $r;
        }

        // --- Receita média por cliente por mês ---
        $sql = "
            SELECT MONTH(data_venda) AS mes,
                   ROUND(SUM(valor) / COUNT(DISTINCT id_cliente), 2) AS receita_media
            FROM venda
            GROUP BY MONTH(data_venda)
            ORDER BY MONTH(data_venda)
        ";
        $res = $conn->query($sql);
        if ($res) {
            while ($r = $res->fetch_assoc())
                $dados['receitaMedia'][] = $r;
        }

        // --- Novos vs Recorrentes (feito em PHP, sem JOIN/aliases) ---
        // 1) primeira compra por cliente (mês)
        $primeira_sql = "
            SELECT id_cliente, MIN(MONTH(data_venda)) AS primeiro_mes
            FROM venda
            GROUP BY id_cliente
        ";
        $res = $conn->query($primeira_sql);
        $primeiraCompra = []; // id_cliente => primeiro_mes
        if ($res) {
            while ($r = $res->fetch_assoc()) {
                $primeiraCompra[$r['id_cliente']] = (int) $r['primeiro_mes'];
            }
        }

        // 2) percorrer vendas por mês e contar novos/recorrentes por mês
        $vendas_sql = "
            SELECT id_cliente, MONTH(data_venda) AS mes
            FROM venda
            ORDER BY MONTH(data_venda)
        ";
        $res = $conn->query($vendas_sql);
        $mapMes = []; // mes => ['novos'=>int,'recorrentes'=>int]
        if ($res) {
            while ($r = $res->fetch_assoc()) {
                $mes = (int) $r['mes'];
                $cliente = $r['id_cliente'];
                if (!isset($mapMes[$mes]))
                    $mapMes[$mes] = ['mes' => $mes, 'novos' => 0, 'recorrentes' => 0];

                if (isset($primeiraCompra[$cliente]) && $primeiraCompra[$cliente] === $mes) {
                    $mapMes[$mes]['novos']++;
                } else {
                    $mapMes[$mes]['recorrentes']++;
                }
            }
        }
        // ordenar por mês e preencher dados
        ksort($mapMes);
        foreach ($mapMes as $m)
            $dados['novosRecorrentes'][] = $m;

        // --- Crescimento do nº de clientes (%) por mês ---
        // vamos calcular total de clientes distintos por mês e depois % mês a mês
        $totais_sql = "
            SELECT MONTH(data_venda) AS mes, COUNT(DISTINCT id_cliente) AS total
            FROM venda
            GROUP BY MONTH(data_venda)
            ORDER BY MONTH(data_venda)
        ";
        $res = $conn->query($totais_sql);
        $totais = []; // array of ['mes'=>int,'total'=>int]
        if ($res) {
            while ($r = $res->fetch_assoc())
                $totais[] = ['mes' => (int) $r['mes'], 'total' => (int) $r['total']];
        }

        // calcular crescimento entre meses consecutivos
        for ($i = 1; $i < count($totais); $i++) {
            $anterior = $totais[$i - 1]['total'];
            $atual = $totais[$i]['total'];
            // evita divisão por zero: se anterior == 0 então definimos crescimento = 0
            if ($anterior > 0) {
                $pct = round((($atual - $anterior) / $anterior) * 100, 2);
            } else {
                $pct = 0;
            }
            $dados['crescimento'][] = [
                'mes' => $totais[$i]['mes'],
                'total' => $atual,
                'crescimento' => $pct
            ];
        }

        return $this->json($dados);
    }

    // Função KPIs mantendo o comportamento original (simplificada)
    private function getKPIsClientes()
    {
        global $conn;
        $kpi = ["total" => 0, "novos_mes" => 0, "receita_total" => 0, "crescimento" => 0];

        // Total de clientes distintos
        $res = $conn->query("SELECT COUNT(DISTINCT codigo) AS total FROM cliente");
        if ($res && ($r = $res->fetch_assoc()))
            $kpi['total'] = (int) $r['total'];

        // Receita total (soma de todas as vendas)
        $res = $conn->query("SELECT SUM(valor) AS receita_total FROM venda");
        if ($res && ($r = $res->fetch_assoc()))
            $kpi['receita_total'] = (float) ($r['receita_total'] ?? 0);

        // Novos clientes do mês anterior (contagem por primeira compra)
        $res = $conn->query("
            SELECT COUNT(*) AS novos_mes FROM (
                SELECT id_cliente, MIN(MONTH(data_venda)) AS primeiro_mes
                FROM venda
                GROUP BY id_cliente
                HAVING primeiro_mes = MONTH(CURDATE() - INTERVAL 1 MONTH)
            ) t
        ");
        if ($res && ($r = $res->fetch_assoc()))
            $kpi['novos_mes'] = (int) $r['novos_mes'];

        // Crescimento simples: diferença entre max e min de clientes por mês (%)
        $res = $conn->query("
            SELECT MIN(t.total) AS min_total, MAX(t.total) AS max_total FROM (
                SELECT MONTH(data_venda) AS mes, COUNT(DISTINCT id_cliente) AS total
                FROM venda
                GROUP BY MONTH(data_venda)
            ) t
        ");
        if ($res && ($r = $res->fetch_assoc())) {
            $min = (int) ($r['min_total'] ?? 0);
            $max = (int) ($r['max_total'] ?? 0);
            if ($min > 0)
                $kpi['crescimento'] = round((($max - $min) / $min) * 100, 2);
            else
                $kpi['crescimento'] = 0;
        }

        return $kpi;
    }
}
?>