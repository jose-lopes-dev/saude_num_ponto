<?php

class ModelComissoesPsicologo {

    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "database_aio");
        $this->conn->set_charset("utf8mb4");
    }

    private function getCodigoRH($idUtilizador) {

        $stmt = $this->conn->prepare("
            SELECT codigo
            FROM rh
            WHERE id_utilizador = ?
            LIMIT 1
        ");

        $stmt->bind_param("i", $idUtilizador);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            return (int)$row["codigo"];
        }

        return null;
    }

    public function listarComissoesPsicologo($id_utilizador) {

        $codigoRH = $this->getCodigoRH($id_utilizador);
        if (!$codigoRH) {
            return ["flag" => false, "comissoes" => []];
        }

        $stmt = $this->conn->prepare("
            SELECT 
                cli.nome_completo AS paciente,
                c.data_hora AS consulta,
                cc.valor_comissao,
                cc.id_estado,
                cc.criado_em
            FROM consulta c
            INNER JOIN cliente cli ON cli.codigo = c.id_cliente
            LEFT JOIN comissao_consulta cc
                ON cc.id_consulta = c.id
               AND cc.codigo_rh = ?
            WHERE c.id_prestador = ?
            ORDER BY c.data_hora DESC
        ");

        $stmt->bind_param("ii", $codigoRH, $codigoRH);
        $stmt->execute();
        $res = $stmt->get_result();

        $lista = [];

        while ($r = $res->fetch_assoc()) {

            if ($r["valor_comissao"] === null) {
                $r["valor_comissao"] = round($r["consulta"] ? 0 : 0, 2);
                $r["id_estado"] = 13;
            }

            $lista[] = $r;
        }

        return [
            "flag" => true,
            "comissoes" => $lista
        ];
    }
}
