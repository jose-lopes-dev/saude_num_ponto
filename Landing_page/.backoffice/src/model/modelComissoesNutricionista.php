<?php
require_once __DIR__ . '/connection.php';

class ModelComissoesNutricionista {

    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
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
        if ($r = $res->fetch_assoc()) return $r['codigo'];
        return null;
    }

    private function getComissoes($idUtilizador) {
        $codigoRH = $this->getCodigoRH($idUtilizador);
        if (!$codigoRH) return [];

        $stmt = $this->conn->prepare("
            SELECT
                cc.id,
                cc.percentagem,
                cc.valor_pago,
                cc.valor_comissao,
                e.descricao as estado,
                c.data_hora,
                cl.nome_completo as cliente,
                cc.id_estado
            FROM comissao_consulta cc
            INNER JOIN consulta c ON cc.id_consulta = c.id
            INNER JOIN cliente cl ON cl.codigo = c.id_cliente
            INNER JOIN estado e ON cc.id_estado = e.id
            WHERE cc.codigo_rh = ?
            ORDER BY c.data_hora DESC
        ");

        $stmt->bind_param("i", $codigoRH);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }


    /* =====================================================
       MÉTODO QUE FALTAVA (PARA O CONTROLLER)
    ===================================================== */
    public function listarPorNutricionista($idUtilizador) {

        $dados = $this->getComissoes($idUtilizador);

        if (empty($dados)) {
            return [
                'pendentes' => []
            ];
        }

        return [
            'pendentes' => $dados
        ];
    }

    /* ===================================================== */

    public function getListaHTML($idUtilizador) {
        $dados = $this->getComissoes($idUtilizador);

        if (empty($dados)) {
            return '
                <tr>
                    <td colspan="6" class="text-center">Sem comissões</td>
                </tr>
            ';
        }

        $html = '';

        foreach ($dados as $c) {
            $badge = $c['id_estado'] == 12 ? 'success' : 'warning';
            $texto = $c['id_estado'] == 12 ? 'Paga' : 'Por pagar';

            $html .= "
                <tr>
                    <td>{$c['cliente']}</td>
                    <td>{$c['criado_em']}</td>
                    <td>{$c['valor']} €</td>
<td>{$c['valor_comissao']} €</td>

                    <td><span class='badge bg-{$badge}'>{$texto}</span></td>
                    <td>-</td>
                </tr>
            ";
        }

        return $html;
    }
public function getResumoComissoes($idUtilizador) {

    $codigoRH = $this->getCodigoRH($idUtilizador);
    if (!$codigoRH) {
        return json_encode([
            "totalComissao" => 0,
            "totalRecebido" => 0,
            "totalPorPagar" => 0
        ]);
    }

    $sql = "SELECT
                IFNULL(SUM(valor_comissao),0) totalComissao,
                IFNULL(SUM(CASE WHEN id_estado = 12 THEN valor_comissao ELSE 0 END),0) totalRecebido,
                IFNULL(SUM(CASE WHEN id_estado = 13 THEN valor_comissao ELSE 0 END),0) totalPorPagar
            FROM comissao_consulta
            WHERE codigo_rh = $codigoRH";

    $result = $this->conn->query($sql);
    $row = $result->fetch_assoc();

    return json_encode($row);
}


    public function syncComissoes($idUtilizador) {
        $codigoRH = $this->getCodigoRH($idUtilizador);
        if (!$codigoRH) return json_encode(array("flag" => false, "msg" => "Código RH não encontrado"));

        $percent = 70;

        $sql = "INSERT INTO comissao_consulta
                    (id_consulta, codigo_rh, percentagem, valor_pago, valor_comissao, id_estado)
                SELECT
                    c.id,
                    c.id_prestador,
                    " . $percent . ",
                    c.preco,
                    ROUND(c.preco * (" . $percent . "/100), 2),
                    13
                FROM consulta c
                WHERE c.id_prestador = " . $codigoRH . "
                AND c.id_estado IN (16)
                AND NOT EXISTS (
                    SELECT 1 FROM comissao_consulta cc WHERE cc.id_consulta = c.id
                )";

        if ($this->conn->query($sql) === TRUE) {
            return json_encode(array("flag" => true, "msg" => "Sync OK"));
        } else {
            return json_encode(array("flag" => false, "msg" => "Erro Sync: " . $this->conn->error));
        }
    }

    public function marcarPago($id) {
        $flag = true;
        $msg = "";

        $sql = "UPDATE comissao_consulta SET id_estado = 12, data_pagamento = NOW() WHERE id = " . intval($id);

        if ($this->conn->query($sql) === TRUE) {
            $msg = "Comissão marcada como paga.";
        } else {
            $flag = false;
            $msg = "Erro: " . $this->conn->error;
        }

        $resp = json_encode(array("flag" => $flag, "msg" => $msg));
        return $resp;
    }
}
