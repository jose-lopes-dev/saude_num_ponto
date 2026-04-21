<?php
require_once "connection.php";

class ConsultaModel {

    public function listarPrestadores($id_servico) {
        global $conn;

        if ($id_servico == 8) $funcao = 3;
        if ($id_servico == 7) $funcao = 2;
        if ($id_servico == 6) $funcao = 4;

        $sql = "
            SELECT r.codigo AS id, r.nome_completo AS nome
            FROM rh r
            JOIN utilizador u ON u.id = r.id_utilizador
            WHERE r.id_funcao = ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $funcao);
        $stmt->execute();

        $res = $stmt->get_result();
        $lista = [];

        while ($row = $res->fetch_assoc()) {
            $lista[] = $row;
        }

        return $lista;
    }

    public function buscarIdCliente($id_utilizador) {
        global $conn;

        $stmt = $conn->prepare(
            "SELECT codigo FROM cliente WHERE id_utilizador = ?"
        );
        $stmt->bind_param("i", $id_utilizador);
        $stmt->execute();

        $res = $stmt->get_result();
        $row = $res->fetch_assoc();

        return $row["codigo"] ?? null;
    }

    public function marcarConsulta($id_cliente, $id_prestador, $id_servico, $data_hora) {
        global $conn;
        
        $estado = 13;

        $chave = null;
        if ((int)$id_servico === 7) $chave = "nutri_gratis_mes";
        if ((int)$id_servico === 8) $chave = "pt_gratis_mes";

        $gratuita = 0;
        $preco = 0;

        if ($chave !== null) {

            require_once "../model/modelPlanoSistema.php";
            $plano = new PlanoSistema();

            $preco = (float)$plano->getPrecoServico((int)$id_servico);

            $limite = (int)$plano->getVantagemAtiva((int)$id_cliente, $chave);

            $usadas = (int)$plano->contarGratisMes((int)$id_cliente, (int)$id_servico);

            if ($usadas < $limite) {
                $gratuita = 1;
                $preco = 0;
            }

        } else {
            $preco = 0;
        }

        $stmt = $conn->prepare("
            INSERT INTO consulta
            (id_cliente, id_prestador, id_servico, data_hora, id_estado, preco, gratuita)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("iiisidi", $id_cliente, $id_prestador, $id_servico, $data_hora, $estado, $preco, $gratuita);
        $ok = $stmt->execute();

    if (!$ok) {
        return [
            "status" => "error",
            "msg" => "Erro ao marcar consulta"
        ];
    }

    $consulta_id = $conn->insert_id;

    $stmt2 = $conn->prepare("
        SELECT id_utilizador
        FROM rh
        WHERE codigo = ?
    ");
    $stmt2->bind_param("i", $id_prestador);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $row2 = $res2->fetch_assoc();

    if ($row2) {
        $id_utilizador_prestador = $row2["id_utilizador"];

        $stmt3 = $conn->prepare("
            INSERT INTO notificacao
            (id_utilizador, tipo, referencia_id, titulo, texto)
            VALUES (?, 'consulta', ?, ?, ?)
        ");

            $titulo = "Nova consulta pendente";
            $texto  = "Um cliente marcou uma nova consulta e aguarda confirmação";

            $stmt3->bind_param("iiss", $id_utilizador_prestador, $consulta_id, $titulo, $texto);
            $stmt3->execute();
        }

        if ($chave !== null) {
            $msg = ($gratuita == 1)
                ? "Consulta marcada (gratuita este mês)."
                : "Consulta marcada (já não tens grátis este mês, ficará paga).";
        } else {
            $msg = "Consulta marcada com sucesso!";
        }

        return [
            "status" => "success",
            "msg" => $msg
        ];
    }

    public function proximasConsultasCliente($id_cliente) {
        global $conn;

        $sql = "
            SELECT
                s.descricao AS servico,
                r.nome_completo AS profissional,
                DATE_FORMAT(c.data_hora, '%Y-%m-%d') AS data,
                DATE_FORMAT(c.data_hora, '%H:%i') AS hora
            FROM consulta c
            JOIN servico s      ON s.id = c.id_servico
            JOIN rh r           ON r.codigo = c.id_prestador
            JOIN utilizador u   ON u.id = r.id_utilizador
            WHERE c.id_cliente = ?
            AND c.id_estado = 15
            AND c.data_hora >= NOW()
            ORDER BY c.data_hora ASC
            LIMIT 3
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();

        $res = $stmt->get_result();
        $out = [];

        while ($row = $res->fetch_assoc()) {
            $out[] = $row;
        }

        return $out;
    }
}
