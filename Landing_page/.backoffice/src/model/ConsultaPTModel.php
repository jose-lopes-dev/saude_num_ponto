<?php

class ConsultaPTModel {

    private $db;

    public function __construct() {
        $this->db = new PDO(
            "mysql:host=localhost;dbname=database_aio;charset=utf8",
            "root",
            ""
        );
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function listarPorEstado($id_prestador, $estado) {
        $sql = "
        SELECT c.id, DATE_FORMAT(c.data_hora, '%d/%m/%Y • %H:%i') AS data_hora, cl.nome_completo AS cliente
        FROM consulta c
        JOIN cliente cl ON cl.codigo = c.id_cliente
        JOIN utilizador u ON u.id = cl.id_utilizador
        WHERE c.id_prestador = ?
        AND c.id_estado = ?
        ORDER BY c.data_hora DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_prestador, $estado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getCodigoPrestador($id_utilizador) {
        $sql = "SELECT codigo FROM rh WHERE id_utilizador = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_utilizador]);
        return $stmt->fetchColumn();
    }


    public function atualizarEstado($id_consulta, $estado, $id_prestador) {
        $sql = "
            UPDATE consulta
            SET id_estado = ?
            WHERE id = ?
            AND id_prestador = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$estado, $id_consulta, $id_prestador]);
    }

    public function getClienteByConsulta($id_consulta) {

        $sql = "
            SELECT u.id AS id_utilizador
            FROM consulta c
            INNER JOIN cliente cl ON cl.codigo = c.id_cliente
            INNER JOIN utilizador u ON u.id = cl.id_utilizador
            WHERE c.id = ?
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_consulta]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

  public function proximasConsultasPT($id_prestador)
{
    $sql = "
        SELECT
            cl.nome_completo AS cliente,
            s.descricao AS servico,
            DATE(c.data_hora) AS data,
            TIME(c.data_hora) AS hora
        FROM consulta c
        JOIN cliente cl ON cl.codigo = c.id_cliente
        JOIN servico s ON s.id = c.id_servico
        WHERE c.id_prestador = ?
        AND c.id_estado = 15
        AND c.data_hora >= NOW()
        ORDER BY c.data_hora ASC
        LIMIT 3
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$id_prestador]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function notificarClienteEstado($id_consulta, $estado) {

        global $conn;

        $cli = $this->getClienteByConsulta($id_consulta);
        if (!$cli) return;

        $id_utilizador = (int)$cli["id_utilizador"];

        $titulo = ($estado === 15) 
            ? "Consulta confirmada" 
            : "Consulta recusada";

        $texto  = ($estado === 15)
            ? "A tua consulta foi confirmada pelo personal trainer."
            : "O personal trainer recusou o pedido. Podes marcar outra data.";

        $stmt = $conn->prepare("
            INSERT INTO notificacao (id_utilizador, tipo, referencia_id, titulo, texto, lida, criada_em)
            VALUES (?, 'consulta', ?, ?, ?, 0, NOW())
        ");

        $stmt->bind_param("iiss", $id_utilizador, $id_consulta, $titulo, $texto);
        $stmt->execute();
        $stmt->close();
    }

    public function listarConsultasCalendarioPT($id_prestador, $year, $month) {
    global $conn;

    $sql = "
        SELECT
            DATE(c.data_hora) AS data_consulta,
            TIME(c.data_hora) AS hora_consulta,
            c.id_estado AS estado,
            c.preco,
            s.descricao AS servico,
            cl.nome_completo AS cliente
        FROM consulta c
        INNER JOIN servico s ON s.id = c.id_servico
        INNER JOIN cliente cl ON cl.codigo = c.id_cliente
        WHERE c.id_prestador = ?
          AND YEAR(c.data_hora) = ?
          AND MONTH(c.data_hora) = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $id_prestador, $year, $month);
    $stmt->execute();

    $res = $stmt->get_result();
    $out = [];

    while ($row = $res->fetch_assoc()) {
        $out[] = $row;
    }

    return $out;
}

}
