<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once __DIR__ . '/connection.php';

class Rendimentos {
    public function graficoTrimestral($trimestre) {
        global $conn;

        // Definir meses de cada trimestre
        $intervalos = [
            1 => [1, 3],
            2 => [4, 6],
            3 => [7, 9],
            4 => [10, 12]
        ];

        $mesesNomes = [
            1 => "Janeiro", 2 => "Fevereiro", 3 => "Março",
            4 => "Abril", 5 => "Maio", 6 => "Junho",
            7 => "Julho", 8 => "Agosto", 9 => "Setembro",
            10 => "Outubro", 11 => "Novembro", 12 => "Dezembro"
        ];

        $ini = $intervalos[$trimestre][0] ?? 1;
        $fim = $intervalos[$trimestre][1] ?? 3;

        // Query para buscar os valores do trimestre
        $sql = "SELECT MONTH(data_venda) AS mes, SUM(valor) AS total 
                FROM venda 
                WHERE MONTH(data_venda) BETWEEN ? AND ?
                GROUP BY mes ORDER BY mes";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $ini, $fim);
        $stmt->execute();
        $result = $stmt->get_result();

        $labels = [];
        $valores = [];

        while ($row = $result->fetch_assoc()) {
            $labels[] = $mesesNomes[(int)$row['mes']];
            $valores[] = (float)$row['total'];
        }

        return json_encode([
            "labels" => $labels,
            "valores" => $valores
        ]);
    }
}
