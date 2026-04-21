<?php

require_once "connection.php";

class ConsultaNutriModel {

    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "database_aio");
        $this->conn->set_charset("utf8mb4");
    }

    /* =============================
        CONSULTAS DO SISTEMA
    ============================== */

   public function listarConsultasNutri($id_utilizador, $estado, $page = 1, $limit = 5) {

    $offset = ($page - 1) * $limit;

    $stmt = $this->conn->prepare("
        SELECT SQL_CALC_FOUND_ROWS
            c.id,
            DATE_FORMAT(c.data_hora, '%d/%m/%Y • %H:%i') AS data_hora,
            cli.nome_completo AS cliente
        FROM consulta c
        INNER JOIN rh r ON r.codigo = c.id_prestador
        INNER JOIN cliente cli ON cli.codigo = c.id_cliente
        WHERE r.id_utilizador = ?
        AND c.id_estado = ?
        ORDER BY c.data_hora DESC
        LIMIT ?, ?
    ");

    $stmt->bind_param("iiii", $id_utilizador, $estado, $offset, $limit);
    $stmt->execute();

    $dados = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $total = $this->conn->query("SELECT FOUND_ROWS() as t")
                        ->fetch_assoc()["t"];

    return [
        "dados" => $dados,
        "total" => $total,
        "paginas" => ceil($total / $limit)
    ];
}


    /* =============================
        DASHBOARD: KPIs
    ============================== */
    public function dashboardKPIs($id_utilizador) {

        // total clientes
        $q1 = $this->conn->prepare("
            SELECT COUNT(DISTINCT c.id_cliente) AS total
            FROM consulta c
            INNER JOIN rh r ON r.codigo = c.id_prestador
            WHERE r.id_utilizador = ?
        ");
        $q1->bind_param("i", $id_utilizador);
        $q1->execute();
        $totalClientes = $q1->get_result()->fetch_assoc()["total"] ?? 0;

        // pendentes
        $q2 = $this->conn->prepare("
            SELECT COUNT(*) AS total
            FROM consulta c
            INNER JOIN rh r ON r.codigo = c.id_prestador
            WHERE r.id_utilizador = ? AND c.id_estado = 13
        ");
        $q2->bind_param("i", $id_utilizador);
        $q2->execute();
        $totalPendentes = $q2->get_result()->fetch_assoc()["total"] ?? 0;

        // consultas de hoje
        $q3 = $this->conn->prepare("
            SELECT COUNT(*) AS total
            FROM consulta c
            INNER JOIN rh r ON r.codigo = c.id_prestador
            WHERE r.id_utilizador = ? 
            AND DATE(c.data_hora) = CURDATE()
        ");
        $q3->bind_param("i", $id_utilizador);
        $q3->execute();
        $totalHoje = $q3->get_result()->fetch_assoc()["total"] ?? 0;

        return [
            "totalClientes" => $totalClientes,
            "totalPendentes" => $totalPendentes,
            "totalHoje" => $totalHoje
        ];
    }

    /* =============================
        DASHBOARD: CONSULTAS HOJE
    ============================== */
    public function dashboardHoje($id_utilizador) {

        $stmt = $this->conn->prepare("
            SELECT 
                cli.nome_completo AS cliente,
                TIME(c.data_hora) AS hora,
                c.id_estado
            FROM consulta c
            INNER JOIN rh r ON r.codigo = c.id_prestador
            INNER JOIN cliente cli ON cli.codigo = c.id_cliente
            WHERE r.id_utilizador = ? 
            AND DATE(c.data_hora) = CURDATE()
            ORDER BY c.data_hora ASC
        ");

        $stmt->bind_param("i", $id_utilizador);
        $stmt->execute();

        $res = $stmt->get_result();
        $lista = [];

        while ($row = $res->fetch_assoc()) {

            $estadoNome =
                $row["id_estado"] == 13 ? "Pendente" :
                ($row["id_estado"] == 15 ? "Aceite" : "Recusada");

            $estadoCor =
                $row["id_estado"] == 13 ? "warning" :
                ($row["id_estado"] == 15 ? "success" : "danger");

            $lista[] = [
                "cliente" => $row["cliente"],
                "hora" => $row["hora"],
                "estado" => $estadoNome,
                "estado_cor" => $estadoCor
            ];
        }

        return $lista;
    }

    /* =============================
        DASHBOARD: ÚLTIMOS CLIENTES
    ============================== */
    public function dashboardUltimosClientes($id_utilizador) {

        $stmt = $this->conn->prepare("
            SELECT 
                cli.nome_completo AS nome,
                DATE(c.data_hora) AS data
            FROM consulta c
            INNER JOIN rh r ON r.codigo = c.id_prestador
            INNER JOIN cliente cli ON cli.codigo = c.id_cliente
            WHERE r.id_utilizador = ?
            AND c.id_estado = 2
            ORDER BY c.data_hora DESC
            LIMIT 5
        ");
        $stmt->bind_param("i", $id_utilizador);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function atualizarEstado($id_consulta, $estado) {

    $stmt = $this->conn->prepare("
        UPDATE consulta 
        SET id_estado = ? 
        WHERE id = ?
    ");

    $stmt->bind_param("ii", $estado, $id_consulta);
    $ok = $stmt->execute();
    $stmt->close();

    return [
        "status" => $ok ? "success" : "error",
        "msg" => $estado == 15 ? "Consulta aceite!" : "Consulta recusada!"
    ];
}


    public function dashboardGraficoSemanal($id_utilizador) {

    $stmt = $this->conn->prepare("
        SELECT 
            DAYOFWEEK(c.data_hora) AS dia_semana,
            COUNT(*) AS total
        FROM consulta c
        INNER JOIN rh r ON r.codigo = c.id_prestador
        WHERE r.id_utilizador = ?
        AND YEARWEEK(c.data_hora, 1) = YEARWEEK(CURDATE(), 1)
        GROUP BY DAYOFWEEK(c.data_hora)
        ORDER BY DAYOFWEEK(c.data_hora)
    ");

    $stmt->bind_param("i", $id_utilizador);
    $stmt->execute();
    $res = $stmt->get_result();

    $mapa = [
        2 => "Seg",
        3 => "Ter",
        4 => "Qua",
        5 => "Qui",
        6 => "Sex",
        7 => "Sáb",
        1 => "Dom"
    ];

    $labels = [];
    $valores = [];

    while ($r = $res->fetch_assoc()) {
        $labels[] = $mapa[$r["dia_semana"]];
        $valores[] = $r["total"];
    }

    return [
        "labels" => $labels,
        "valores" => $valores
    ];
}


    public function getClienteByConsulta($id_consulta) {

        $stmt = $this->conn->prepare("
            SELECT u.id AS id_utilizador
            FROM consulta c
            JOIN cliente cli ON cli.codigo = c.id_cliente
            JOIN utilizador u ON u.id = cli.id_utilizador
            WHERE c.id = ?
            LIMIT 1
        ");
        $stmt->bind_param("i", $id_consulta);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function notificarClienteEstado($id_consulta, $estado) {

        $cli = $this->getClienteByConsulta($id_consulta);
        if (!$cli) return;

        $id_utilizador = (int)$cli["id_utilizador"];

        $titulo = ($estado === 15)
            ? "Consulta confirmada"
            : "Consulta recusada";

        $texto = ($estado === 15)
            ? "A tua consulta foi confirmada pelo nutricionista."
            : "O nutricionista recusou o pedido. Podes marcar outra data.";

        $stmt = $this->conn->prepare("
            INSERT INTO notificacao (id_utilizador, tipo, referencia_id, titulo, texto, lida, criada_em)
            VALUES (?, 'consulta', ?, ?, ?, 0, NOW())
        ");
        $stmt->bind_param("iiss", $id_utilizador, $id_consulta, $titulo, $texto);
        $stmt->execute();
        $stmt->close();
    }

public function proximasConsultasNutri($idNutri) {

    $stmt = $this->conn->prepare("
        SELECT 
            cli.nome_completo AS cliente,
            'Consulta Nutrição' AS servico,
            DATE(c.data_hora) AS data,
            TIME(c.data_hora) AS hora
        FROM consulta c
        INNER JOIN rh r ON r.codigo = c.id_prestador
        INNER JOIN cliente cli ON cli.codigo = c.id_cliente
        WHERE r.id_utilizador = ?
        AND c.id_estado = 15
        AND c.data_hora >= NOW()
        ORDER BY c.data_hora ASC
        LIMIT 3
    ");

    $stmt->bind_param("i", $idNutri);
    $stmt->execute();

    $res = $stmt->get_result();
    return $res->fetch_all(MYSQLI_ASSOC);
}


}
