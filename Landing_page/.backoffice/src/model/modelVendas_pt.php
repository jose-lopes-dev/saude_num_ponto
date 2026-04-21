<?php
require_once 'connection.php';

class VendasPT {

    public $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    /* ==========================
       CLIENTES DO PT
    ========================== */
    public function getClientesVendaPt($idPt) {

        if (!$this->conn) {
            return '<option value="">Sem ligação à BD</option>';
        }

        $html = '<option value="">Seleciona...</option>';

        $sql = "
            SELECT DISTINCT c.codigo, c.nome_completo
            FROM cliente c
            INNER JOIN consulta co ON co.id_cliente = c.codigo
            WHERE co.id_prestador = ?
            ORDER BY c.nome_completo
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idPt);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $html .= '<option value="'.$row['codigo'].'">'.$row['nome_completo'].'</option>';
        }

        return $html;
    }

    /* ==========================
       SERVIÇOS
    ========================== */
    public function getServicosVendaPt($idPt) {

        if (!$this->conn) {
            return '<option value="">Sem ligação à BD</option>';
        }

        $html = '<option value="">Seleciona...</option>';

        $sql = "SELECT id, descricao, preco FROM servico ORDER BY descricao";
        $res = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($res)) {
            $preco = number_format((float)$row['preco'], 2, '.', '');
            $html .= '<option value="'.$row['id'].'" data-preco="'.$preco.'">'.$row['descricao'].'</option>';
        }

        return $html;
    }

    /* ==========================
       REGISTAR VENDA
    ========================== */
    public function registaVendaPt(
        $idPt,
        $idCliente,
        $idServico,
        $valor,
        $dataVenda,
        $metodo,
        $idEstado
    ) {

        if (!$this->conn) {
            return json_encode([
                "flag" => false,
                "msg" => "Sem ligação à BD"
            ]);
        }

        $stmt = $this->conn->prepare("
            INSERT INTO venda
            (id_cliente, id_servico, id_prestador, valor, data_venda, metodo_pagamento, id_estado)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iiidssi",
            $idCliente,
            $idServico,
            $idPt,
            $valor,
            $dataVenda,
            $metodo,
            $idEstado
        );

        $stmt->execute();

        return json_encode([
            "flag" => true,
            "msg" => "Venda registada com sucesso"
        ]);
    }

    /* ==========================
       LISTAR VENDAS DO PT
    ========================== */
    public function getTabelaVendasPt($idPt) {

        if (!$this->conn) {
            return '<tr><td colspan="8" class="text-center">Sem ligação à BD</td></tr>';
        }

        $sql = "
            SELECT
                v.id,
                v.data_venda,
                c.nome_completo,
                s.descricao,
                v.valor,
                e.descricao AS estado,
                v.metodo_pagamento
            FROM venda v
            INNER JOIN cliente c ON c.codigo = v.id_cliente
            INNER JOIN servico s ON s.id = v.id_servico
            LEFT JOIN estado e ON e.id = v.id_estado
            WHERE v.id_prestador = ?
            ORDER BY v.data_venda DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idPt);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            return '
                <tr>
                    <td colspan="8" class="text-muted text-center">
                        Sem registos
                    </td>
                </tr>
            ';
        }

        $html = "";

        while ($row = $res->fetch_assoc()) {

            $html .= '
                <tr>
                    <td>'.$row['id'].'</td>
                    <td>'.$row['data_venda'].'</td>
                    <td>'.$row['nome_completo'].'</td>
                    <td>'.$row['descricao'].'</td>
                    <td>'.$row['valor'].' €</td>
                    <td>'.$row['estado'].'</td>
                    <td>'.$row['metodo_pagamento'].'</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-danger"
                            onclick="removerVenda('.$row['id'].')">
                            Remover
                        </button>
                    </td>
                </tr>
            ';
        }

        return $html;
    }

    /* ==========================
       REMOVER VENDA
    ========================== */
    public function removeVendaPt($idVenda) {

        if (!$this->conn) {
            return json_encode([
                "flag" => false,
                "msg" => "Sem ligação à BD"
            ]);
        }

        $stmt = $this->conn->prepare("
            DELETE FROM venda WHERE id = ?
        ");
        $stmt->bind_param("i", $idVenda);
        $stmt->execute();

        return json_encode([
            "flag" => true,
            "msg" => "Venda removida"
        ]);
    }
}
