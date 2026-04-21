<?php
require_once __DIR__ . '/connection.php';

class Custos {
    public function custosPorMes($mes) {
        global $conn;

        $meses = [
            "janeiro" => 1, "fevereiro" => 2, "março" => 3, "abril" => 4,
            "maio" => 5, "junho" => 6, "julho" => 7, "agosto" => 8,
            "setembro" => 9, "outubro" => 10, "novembro" => 11, "dezembro" => 12
        ];

        $mes_num = $meses[strtolower($mes)] ?? 1;

        // Buscar descrição individual e somar valores iguais
        $sql = "SELECT descricao, SUM(valor) AS total
                FROM custo
                WHERE MONTH(mes_referencia) = ?
                GROUP BY descricao";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $mes_num);
        $stmt->execute();
        $result = $stmt->get_result();

        $valores = [];
        $descricoes = [];

        while ($row = $result->fetch_assoc()) {
            $valores[] = (float)$row['total'];
            $descricoes[] = $row['descricao'];
        }

        return json_encode([
            "valores" => $valores,
            "descricoes" => $descricoes
        ]);
    }

    public function evolucaoGastos() {
        global $conn;

        $sql = "SELECT MONTH(mes_referencia) AS mes, SUM(valor) AS total
                FROM custo
                GROUP BY MONTH(mes_referencia)
                ORDER BY MONTH(mes_referencia)";

        $result = $conn->query($sql);

        $labels = [];
        $valores = [];

        $mesAbrev = ["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"];

        while ($row = $result->fetch_assoc()) {
            $mes = (int)$row['mes'];
            $labels[] = $mesAbrev[$mes-1];
            $valores[] = (float)$row['total'];
        }

        return json_encode([
            "labels" => $labels,
            "valores" => $valores
        ]);
    }
}
