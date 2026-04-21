<?php
require_once __DIR__ . '/connection.php';

class Tarefa {


  public function listar() {
    global $conn;

    $sql = "
        SELECT 
            obrigacao.id,
            tipo_obrigacao.descricao AS tipo_obrigacao,
            obrigacao.descricao,
            obrigacao.valor,
            obrigacao.data_vencimento,
            obrigacao.data_pagamento,
            estado.descricao AS estado
        FROM obrigacao
        LEFT JOIN tipo_obrigacao ON obrigacao.id_tipo_obrigacao = tipo_obrigacao.id
        LEFT JOIN estado ON obrigacao.id_estado = estado.id
        ORDER BY obrigacao.id DESC
    ";

    $result = $conn->query($sql);
    if (!$result) {
        die('Erro ao listar tarefas: ' . $conn->error);
    }

    $html = '';
    while ($r = $result->fetch_assoc()) {
        $html .= "
            <tr>
                <td>{$r['id']}</td>
                <td>{$r['tipo_obrigacao']}</td>
                <td>{$r['descricao']}</td>
                <td>{$r['valor']}</td>
                <td>{$r['data_vencimento']}</td>
                <td>{$r['data_pagamento']}</td>
                <td>{$r['estado']}</td>
                <td><button class='btn btn-primary btn-sm' onclick='editarTarefa({$r['id']})'>Editar</button></td>
                <td><button class='btn btn-success btn-sm' onclick='concluirTarefa({$r['id']})'>Concluir</button></td>
            </tr>
        ";
    }

    return $html;
}


    // ========================
    // GUARDAR (INSERT / UPDATE)
    // ========================
    public function guardar($id, $id_tipo_obrigacao, $descricao, $valor, $data_vencimento, $data_pagamento, $id_estado) {
        global $conn;

        if (empty($descricao) || empty($valor)) {
            return json_encode(["flag" => false, "msg" => "Preencha todos os campos obrigatórios."]);
        }

        if (empty($id)) {
            $stmt = $conn->prepare("
                INSERT INTO obrigacao (id_tipo_obrigacao, descricao, valor, data_vencimento, data_pagamento, id_estado)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("isdssi", $id_tipo_obrigacao, $descricao, $valor, $data_vencimento, $data_pagamento, $id_estado);
        } else {
            $stmt = $conn->prepare("
                UPDATE obrigacao
                SET id_tipo_obrigacao=?, descricao=?, valor=?, data_vencimento=?, data_pagamento=?, id_estado=?
                WHERE id=?
            ");
            $stmt->bind_param("isdssii", $id_tipo_obrigacao, $descricao, $valor, $data_vencimento, $data_pagamento, $id_estado, $id);
        }

        $ok = $stmt->execute();
        $stmt->close();

        return json_encode([
            "flag" => $ok,
            "msg" => $ok ? "Tarefa guardada com sucesso!" : "Erro ao guardar tarefa."
        ]);
    }

    // ========================
    // EDITAR TAREFA
    // ========================
    public function editar($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM obrigacao WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return json_encode($res);
    }

    // ========================
    // CONCLUIR TAREFA
    // ========================
    public function concluir($id) {
        global $conn;
        $stmt = $conn->prepare("UPDATE obrigacao SET id_estado=3 WHERE id=?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();

        return json_encode([
            "flag" => $ok,
            "msg" => $ok ? "Tarefa concluída com sucesso!" : "Erro ao concluir tarefa."
        ]);
    }

    // ========================
    // ESTADOS (para o <select>)
    // ========================
public function listarEstados() {
    global $conn;
    $res = $conn->query("SELECT id, descricao FROM estado ORDER BY descricao ASC");
    $html = "";
    while ($r = $res->fetch_assoc()) {
        $html .= "<option value='{$r['id']}'>{$r['descricao']}</option>";
    }
    return $html;
}


    // ========================
    // TIPOS (para o <select>)
    // ========================
    public function listarTipos() {
        global $conn;
        $res = $conn->query("SELECT id, descricao FROM tipo_obrigacao ORDER BY descricao ASC");
        $html = "";
        while ($r = $res->fetch_assoc()) {
            $html .= "<option value='{$r['id']}'>{$r['descricao']}</option>";
        }
        return $html;
    }
}
?>
