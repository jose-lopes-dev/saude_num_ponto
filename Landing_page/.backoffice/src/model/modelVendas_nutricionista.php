<?php
require_once __DIR__ . '/connection.php';

class ModelVendasNutricionista {

    public $conn;

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

        if ($row = $res->fetch_assoc()) {
            return (int)$row["codigo"];
        }

        return false;
    }

    /* ==========================
       CLIENTES DO NUTRICIONISTA
    ========================== */
    public function getClientes($idUtilizador) {

        $codigoRH = $this->getCodigoRH($idUtilizador);
        if (!$codigoRH) {
            return '<option value="">Seleciona...</option>';
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
        $stmt->bind_param("i", $codigoRH);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
            $html .= '<option value="'.$row['codigo'].'">'.$row['nome_completo'].'</option>';
        }

        return $html;
    }

    /* ==========================
       SERVIÇOS (CONSULTAS)
    ========================== */
    public function getConsultas($idUtilizador, $idCliente) {

        $html = '<option value="">Seleciona...</option>';

        $sql = "
            SELECT id, descricao, preco
            FROM servico
            ORDER BY descricao
        ";

        $res = mysqli_query($this->conn, $sql);

        while ($row = mysqli_fetch_assoc($res)) {
            $preco = number_format((float)$row['preco'], 2, '.', '');
            $html .= '
                <option value="'.$row['id'].'" data-preco="'.$preco.'">
                    '.$row['descricao'].'
                </option>
            ';
        }

        return $html;
    }

    /* ==========================
       REGISTAR VENDA
    ========================== */
    public function registarVenda($idUtilizador, $dados) {

    $codigoRH = $this->getCodigoRH($idUtilizador);
    if (!$codigoRH) {
        return ["flag" => false, "msg" => "Utilizador inválido"];
    }

    $stmt = $this->conn->prepare("
        INSERT INTO venda
        (id_cliente, id_servico, id_prestador, valor, data_venda, metodo_pagamento, id_estado)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "iiidssi",
        $dados["id_cliente"],
        $dados["id_consulta"],
        $codigoRH,
        $dados["valor"],
        $dados["data_venda"],
        $dados["metodo_pagamento"],
        $dados["id_estado"]
    );

    $stmt->execute();

    return [
        "flag" => true,
        "msg" => "Venda registada com sucesso"
    ];
}

    /* ==========================
       LISTAR VENDAS
    ========================== */
    public function listarVendas($idUtilizador) {

    $codigoRH = $this->getCodigoRH($idUtilizador);
    if (!$codigoRH) {
        return '<tr><td colspan="8" class="text-muted">Sem registos</td></tr>';
    }

    $sql = "
        SELECT
            v.id,
            v.data_venda,
            c.nome_completo,
            s.descricao AS consulta,
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
    $stmt->bind_param("i", $codigoRH);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        return '<tr><td colspan="8" class="text-muted">Sem registos</td></tr>';
    }

    $html = "";

    while ($row = $res->fetch_assoc()) {
        $html .= "
            <tr>
                <td>{$row['id']}</td>
                <td>{$row['data_venda']}</td>
                <td>{$row['nome_completo']}</td>
                <td>{$row['consulta']}</td>
                <td>{$row['valor']} €</td>
                <td>{$row['estado']}</td>
                <td>{$row['metodo_pagamento']}</td>
                <td class='text-center'>
                    <button class='btn btn-sm btn-danger'
                        onclick='removerVenda({$row['id']})'>
                        Remover
                    </button>
                </td>
            </tr>
        ";
    }

    return $html;
}

    /* ==========================
       REMOVER VENDA
    ========================== */
    public function removerVenda($idUtilizador, $idVenda) {

        $codigoRH = $this->getCodigoRH($idUtilizador);
        if (!$codigoRH) {
            return ["flag" => false];
        }

        $stmt = $this->conn->prepare("
            DELETE FROM venda
            WHERE id = ?
        ");
        $stmt->bind_param("i", $idVenda);
        $stmt->execute();

        return [
            "flag" => true,
            "msg" => "Venda removida"
        ];
    }
}
