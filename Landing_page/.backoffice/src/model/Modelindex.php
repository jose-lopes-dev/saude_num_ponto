<?php
// modelindex.php
require_once 'connection.php'; // garante $conn (mysqli)

class Dashboard {

    // util: formatar valor para display PT
    private function fmt($v) {
        return number_format(floatval($v), 2, ',', '.');
    }

    // 1) Saldo total -> usa conta_bancaria (tipo: 'entrada' / 'saida' ou 'Entrada' / 'Saída')
    public function getSaldoTotal() {
        global $conn;

        $r1 = $conn->query("SELECT SUM(valor) AS total FROM conta_bancaria WHERE LOWER(tipo)='entrada'");
        $entradas = floatval($r1->fetch_assoc()['total'] ?? 0);

        $r2 = $conn->query("SELECT SUM(valor) AS total FROM conta_bancaria WHERE LOWER(tipo)='saida' OR LOWER(tipo)='saída'");
        $saidas = floatval($r2->fetch_assoc()['total'] ?? 0);

        $saldo = $entradas - $saidas;

        // percentagem relativa simples (se quiseres outro cálculo, diz)
        $percent = $entradas > 0 ? (($entradas - $saidas) / max(1, $entradas)) * 100 : 0;
        $percent = round($percent, 2);

        return json_encode([
            'saldo_raw' => $saldo,
            'saldo' => $this->fmt($saldo),
            'percentagemRaw' => $percent
        ]);
    }

    // 2) Custos do mês de Setembro do ano atual
    public function getCustosSetembro() {
        global $conn;
        // usar mês actual e comparar com mês anterior (ajusta ano quando necessário)
        $ano = intval(date('Y'));
        $mes = intval(date('n'));
        $mes_ant = $mes - 1;
        $ano_ant = $ano;
        if ($mes_ant === 0) { $mes_ant = 12; $ano_ant = $ano - 1; }

        $q = $conn->query("SELECT SUM(valor) AS total FROM custo WHERE MONTH(mes_referencia) = $mes AND YEAR(mes_referencia) = $ano");
        $total = floatval($q->fetch_assoc()['total'] ?? 0);

        // percentagem em relação ao mês anterior (usa mês/ano anteriores corretamente)
        $q2 = $conn->query("SELECT SUM(valor) AS total FROM custo WHERE MONTH(mes_referencia) = $mes_ant AND YEAR(mes_referencia) = $ano_ant");
        $ant = floatval($q2->fetch_assoc()['total'] ?? 0);
        $percent = $ant > 0 ? round((($total - $ant) / $ant) * 100, 2) : 0;

        return json_encode([
            'total_raw' => $total,
            'total' => $this->fmt($total),
            'percentagem' => $percent
        ]);
    }

    // 3) Rendimentos do mês de Setembro (tabela venda)
    public function getRendimentosSetembro() {
        global $conn;
        // usar mês actual e comparar com mês anterior (ajusta ano quando necessário)
        $ano = intval(date('Y'));
        $mes = intval(date('n'));
        $mes_ant = $mes - 1;
        $ano_ant = $ano;
        if ($mes_ant === 0) { $mes_ant = 12; $ano_ant = $ano - 1; }

        $q = $conn->query("SELECT SUM(valor) AS total FROM venda WHERE MONTH(data_venda) = $mes AND YEAR(data_venda) = $ano");
        $total = floatval($q->fetch_assoc()['total'] ?? 0);

        // percentagem em relação ao mês anterior (usa mês/ano anteriores corretamente)
        $q2 = $conn->query("SELECT SUM(valor) AS total FROM venda WHERE MONTH(data_venda) = $mes_ant AND YEAR(data_venda) = $ano_ant");
        $ant = floatval($q2->fetch_assoc()['total'] ?? 0);
        $percent = $ant > 0 ? round((($total - $ant) / $ant) * 100, 2) : 0;

        return json_encode([
            'rendimentos_raw' => $total,
            'rendimentos' => $this->fmt($total),
            'percentagem' => $percent
        ]);
    }

    // 4) RAI = rendimentos - custos (setembro)
    public function getRAISetembro() {
        global $conn;
        // usar mês actual e comparar com mês anterior (ajusta ano quando necessário)
        $ano = intval(date('Y'));
        $mes = intval(date('n'));
        $mes_ant = $mes - 1;
        $ano_ant = $ano;
        if ($mes_ant === 0) { $mes_ant = 12; $ano_ant = $ano - 1; }

        $qC = $conn->query("SELECT SUM(valor) AS total FROM custo WHERE MONTH(mes_referencia) = $mes AND YEAR(mes_referencia) = $ano");
        $custos = floatval($qC->fetch_assoc()['total'] ?? 0);

        $qR = $conn->query("SELECT SUM(valor) AS total FROM venda WHERE MONTH(data_venda) = $mes AND YEAR(data_venda) = $ano");
        $rend = floatval($qR->fetch_assoc()['total'] ?? 0);

        $rai = $rend - $custos;
        $percent = $custos != 0 ? round((($rai) / $custos) * 100, 2) : 0;

        return json_encode([
            'rai_raw' => $rai,
            'rai' => $this->fmt($rai),
            'percentagem' => $percent
        ]);
    }

    // 5) Dados mensais para o gráfico (custos + rendimentos) - devolve arrays com 12 valores (jan-dez)
    public function getGraficoMensal() {
        global $conn;
        $ano = date('Y');

        $custos = [];
        $rendimentos = [];

        for ($m = 1; $m <= 12; $m++) {
            $q1 = $conn->query("SELECT SUM(valor) AS total FROM custo WHERE MONTH(mes_referencia) = $m AND YEAR(mes_referencia) = $ano");
            $c = floatval($q1->fetch_assoc()['total'] ?? 0);
            $custos[] = $c;

            $q2 = $conn->query("SELECT SUM(valor) AS total FROM venda WHERE MONTH(data_venda) = $m AND YEAR(data_venda) = $ano");
            $r = floatval($q2->fetch_assoc()['total'] ?? 0);
            $rendimentos[] = $r;
        }

        return json_encode([
            'custos' => $custos,
            'rendimentos' => $rendimentos
        ]);
    }
}
