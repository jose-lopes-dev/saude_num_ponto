<?php

class ConsultaPsicologoModel {

    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "database_aio");
        $this->conn->set_charset("utf8mb4");
    }

    public function listarSessoesPsicologo($id_utilizador) {

        $stmt = $this->conn->prepare("
            SELECT c.id, DATE_FORMAT(c.data_hora, '%d/%m/%Y • %H:%i') AS data_hora, c.id_estado, cli.nome_completo AS paciente
            FROM consulta c
            INNER JOIN rh r ON r.codigo = c.id_prestador
            INNER JOIN cliente cli ON cli.codigo = c.id_cliente
            WHERE r.id_utilizador = ?
            ORDER BY c.data_hora DESC
        ");
        $stmt->bind_param("i", $id_utilizador);
        $stmt->execute();
        $res = $stmt->get_result();

        $pendentes = [];
        $aceites = [];
        $recusadas = [];

        while ($row = $res->fetch_assoc()) {
            if ($row["id_estado"] == 13) $pendentes[] = $row;
            if ($row["id_estado"] == 15) $aceites[] = $row;
            if ($row["id_estado"] == 4) $recusadas[] = $row;
        }

        return [
            "pendentes" => $pendentes,
            "aceites" => $aceites,
            "recusadas" => $recusadas
        ];
    }

    public function dashboardKPIs($id_utilizador) {

        $q1 = $this->conn->prepare("
            SELECT COUNT(DISTINCT c.id_cliente) AS total
            FROM consulta c
            INNER JOIN rh r ON r.codigo = c.id_prestador
            WHERE r.id_utilizador = ?
        ");
        $q1->bind_param("i", $id_utilizador);
        $q1->execute();
        $totalPacientes = $q1->get_result()->fetch_assoc()["total"] ?? 0;

        $q2 = $this->conn->prepare("
            SELECT COUNT(*) AS total
            FROM consulta c
            INNER JOIN rh r ON r.codigo = c.id_prestador
            WHERE r.id_utilizador = ? AND c.id_estado = 1
        ");
        $q2->bind_param("i", $id_utilizador);
        $q2->execute();
        $totalPendentes = $q2->get_result()->fetch_assoc()["total"] ?? 0;

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
            "totalPacientes" => $totalPacientes,
            "totalPendentes" => $totalPendentes,
            "totalHoje" => $totalHoje
        ];
    }

    public function dashboardHoje($id_utilizador) {

        $stmt = $this->conn->prepare("
            SELECT 
                cli.nome_completo AS paciente,
                DATE_FORMAT(c.data_hora, '%H:%i') AS hora,
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
                "paciente" => $row["paciente"],
                "hora" => $row["hora"],
                "estado" => $estadoNome,
                "estado_cor" => $estadoCor
            ];
        }

        return $lista;
    }

    public function dashboardUltimosPacientes($id_utilizador) {

        $stmt = $this->conn->prepare("
            SELECT 
                cli.nome_completo AS nome,
                DATE_FORMAT(c.data_hora, '%d/%m/%Y • %H:%i') AS data_hora
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

    $msg = "Estado atualizado.";

    if ($estado == 15) {
        $msg = "Sessão aceite!";
    }

    if ($estado == 4) {
        $msg = "Sessão recusada!";
    }

    return [
        "status" => $ok ? "success" : "error",
        "msg" => $msg
    ];
}


    public function dashboardGraficoSemanal($id_utilizador) {

        $stmt = $this->conn->prepare("
            SELECT DAY(c.data_hora) AS dia,
                   COUNT(*) AS total
            FROM consulta c
            INNER JOIN rh r ON r.codigo = c.id_prestador
            WHERE r.id_utilizador = ?
            AND MONTH(c.data_hora) = MONTH(CURDATE())
            GROUP BY DAY(c.data_hora)
        ");
        $stmt->bind_param("i", $id_utilizador);
        $stmt->execute();
        $res = $stmt->get_result();

        $labels = [];
        $valores = [];

        while ($r = $res->fetch_assoc()) {
            $labels[] = $r["dia"];
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
            ? "A tua consulta foi confirmada pelo psicólogo."
            : "O psicólogo recusou o pedido. Podes marcar outra data.";
        $stmt = $this->conn->prepare("
            INSERT INTO notificacao (id_utilizador, tipo, referencia_id, titulo, texto, lida, criada_em)
            VALUES (?, 'consulta', ?, ?, ?, 0, NOW())
        ");
        $stmt->bind_param("iiss", $id_utilizador, $id_consulta, $titulo, $texto);
        $stmt->execute();
        $stmt->close();
    }
}
