<?php
require_once __DIR__ . "/connection.php";

class Suporte {

    public function getAssuntosJSON() {
        global $conn;

        $sql = "SELECT id, titulo FROM suporte_assuntos ORDER BY titulo ASC";
        $res = $conn->query($sql);

        $data = [];

        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $data[] = [
                    "id"   => $row["id"],
                    "nome" => $row["titulo"]
                ];
            }
        }

        return json_encode($data);
    }

    public function criarPedido($user_id, $assunto_id, $mensagem) {
        global $conn;

        $user_id    = intval($user_id);
        $assunto_id = intval($assunto_id);
        $mensagem   = $conn->real_escape_string($mensagem);

        if ($user_id <= 0) {
            return json_encode(["flag" => false, "msg" => "Utilizador inválido."]);
        }

        if ($assunto_id <= 0) {
            return json_encode(["flag" => false, "msg" => "Assunto inválido."]);
        }

        if (trim($mensagem) === "") {
            return json_encode(["flag" => false, "msg" => "Mensagem vazia."]);
        }

        $sql = "
            INSERT INTO suporte_pedidos
                (user_id, assunto_id, mensagem, estado, criado_em)
            VALUES
                ($user_id, $assunto_id, '$mensagem', 'aberto', NOW())
        ";

        if ($conn->query($sql)) {

            /* ============================
               NOTIFICAR ADMINS
            ============================ */

            $admins = $conn->query("
                SELECT u.id
                FROM utilizador u
                INNER JOIN tipo_user tu ON tu.id = u.id_tipo_user
                WHERE tu.nome = 'admin'
            ");

            if ($admins) {
                while ($a = $admins->fetch_assoc()) {

                    $aid = (int) $a["id"];

                    $conn->query("
                        INSERT INTO notificacao
                            (id_utilizador, titulo, texto, lida, criada_em)
                        VALUES
                            (
                                $aid,
                                'Novo pedido de suporte',
                                'Foi criado um novo pedido de suporte.',
                                0,
                                NOW()
                            )
                    ");
                }
            }

            return json_encode([
                "flag" => true,
                "msg"  => "Pedido enviado com sucesso!"
            ]);
        }

        return json_encode([
            "flag" => false,
            "msg"  => "Erro ao guardar pedido."
        ]);
    }

    public function getListaPedidosHTML() {
        global $conn;

        $html = "";

        $sql = "
            SELECT 
                p.id,
                p.mensagem,
                p.estado,
                p.criado_em,
                p.imagem,
                a.titulo AS assunto,
                tu.nome AS role,
                COALESCE(c.nome_completo, rh.nome_completo, ad.nome_completo, u.email) AS username
            FROM suporte_pedidos p
            LEFT JOIN suporte_assuntos a ON p.assunto_id = a.id
            LEFT JOIN utilizador u ON p.user_id = u.id
            LEFT JOIN tipo_user tu ON tu.id = u.id_tipo_user
            LEFT JOIN cliente c ON c.id_utilizador = u.id
            LEFT JOIN rh ON rh.id_utilizador = u.id
            LEFT JOIN admin ad ON ad.id_utilizador = u.id
            ORDER BY p.criado_em DESC
        ";

        $res = $conn->query($sql);

        if ($res) {
            while ($row = $res->fetch_assoc()) {

                $imagem = (!empty($row["imagem"]))
                    ? "<a href='{$row["imagem"]}' target='_blank' class='btn btn-info btn-sm'>Ver</a>"
                    : "<span class='text-muted'>Sem</span>";

                $html .= "
                    <tr>
                        <td>".$this->esc($row['id'])."</td>
                        <td>".$this->esc($row['username'])."</td>
                        <td>".$this->esc($row['role'])."</td>
                        <td>".$this->esc($row['assunto'])."</td>
                        <td>".$this->esc($row['estado'])."</td>
                        <td>".$imagem."</td>
                        <td>
                            <button class='btn btn-primary btn-sm' onclick='verPedido(".$row['id'].")'>Ver</button>
                        </td>
                    </tr>
                ";
            }
        }

        return $html;
    }

    public function getPedidoJSON($id) {
        global $conn;

        $id = intval($id);
        if ($id <= 0) {
            return json_encode(null);
        }

        $sql = "
            SELECT 
                p.id,
                p.mensagem,
                p.estado,
                p.criado_em,
                p.imagem,
                a.titulo AS assunto,
                tu.nome AS role,
                u.email AS user_email,
                COALESCE(c.nome_completo, rh.nome_completo, ad.nome_completo) AS username
            FROM suporte_pedidos p
            LEFT JOIN suporte_assuntos a ON p.assunto_id = a.id
            LEFT JOIN utilizador u ON p.user_id = u.id
            LEFT JOIN tipo_user tu ON tu.id = u.id_tipo_user
            LEFT JOIN cliente c ON c.id_utilizador = u.id
            LEFT JOIN rh ON rh.id_utilizador = u.id
            LEFT JOIN admin ad ON ad.id_utilizador = u.id
            WHERE p.id = $id
            LIMIT 1
        ";

        $res = $conn->query($sql);

        if ($res && $row = $res->fetch_assoc()) {
            return json_encode($row);
        }

        return json_encode(null);
    }

    private function esc($s) {
        return htmlspecialchars((string)$s, ENT_QUOTES, "UTF-8");
    }
}
