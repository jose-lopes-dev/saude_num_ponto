<?php
require_once __DIR__ . "/connection.php";

class Plano {

    public function criarPlano($user_id, $titulo, $ingredientesJSON) {
        global $conn;

        $titulo = $conn->real_escape_string($titulo);
        $ingredientes = json_decode($ingredientesJSON, true);

        if (empty($ingredientes)) {
            return json_encode(["flag" => false, "msg" => "Sem ingredientes"]);
        }

        $conn->query("INSERT INTO plano_alimentar (user_id, titulo, total_calorias)
                      VALUES ($user_id, '$titulo', 0)");

        $plano_id = $conn->insert_id;
        $total = 0;

        foreach ($ingredientes as $i) {
            $nome = $conn->real_escape_string($i["nome"]);
            $cal = intval($i["calorias"]);

            $conn->query("INSERT INTO plano_ingredientes (plano_id, nome, calorias)
                          VALUES ($plano_id, '$nome', $cal)");

            $total += $cal;
        }

        $conn->query("UPDATE plano_alimentar SET total_calorias = $total WHERE id = $plano_id");

        return json_encode(["flag" => true, "msg" => "Plano criado com sucesso"]);
    }

    public function listarPlanos($user_id) {
        global $conn;

        $res = $conn->query("SELECT id, titulo, total_calorias
                             FROM plano_alimentar
                             WHERE user_id = $user_id
                             ORDER BY id DESC");

        if ($res->num_rows == 0) {
            return "<p class='text-muted'>Nenhum plano criado ainda.</p>";
        }

        $html = "<div class='list-group'>";

        while ($r = $res->fetch_assoc()) {
            $id = (int)$r["id"];
            $titulo = htmlspecialchars($r["titulo"]);
            $kcal = (int)$r["total_calorias"];

            $html .= "
                <div class='list-group-item d-flex justify-content-between align-items-center'>
                    <div>
                        <div class='fw-semibold'>$titulo</div>
                        <small class='text-muted'>$kcal kcal</small>
                    </div>
                    <div class='d-flex gap-2'>
                        <button class='pt-action-btn pt-action-btn--green' type='button' onclick='verPlano($id)'>
                            <i class='ri-eye-line'></i>
                        </button>
                        <button class='pt-action-btn pt-action-btn--red' type='button' onclick='removerPlanoDireto($id)'>
                            <i class='ri-delete-bin-line'></i>
                        </button>
                    </div>
                </div>
            ";
        }

        return $html . "</div>";

    }

    public function verPlano($id) {
        global $conn;

        $p = $conn->query("SELECT titulo, total_calorias FROM plano_alimentar WHERE id = $id")->fetch_assoc();
        $res = $conn->query("SELECT nome, calorias FROM plano_ingredientes WHERE plano_id = $id");

        $ingredientes = [];
        while ($i = $res->fetch_assoc()) {
            $ingredientes[] = $i;
        }

        return [
            "plano" => ["titulo" => $p["titulo"], "total" => $p["total_calorias"]],
            "ingredientes" => $ingredientes
        ];
    }

    public function removerPlano($id) {
        global $conn;

        $conn->query("DELETE FROM plano_ingredientes WHERE plano_id = $id");
        $conn->query("DELETE FROM plano_alimentar WHERE id = $id");

        return ["flag" => true, "msg" => "Plano eliminado"];
    }

   public function listarFicheirosRecebidos($user_id) {
    global $conn;

    $resCliente = $conn->query("
        SELECT codigo
        FROM cliente
        WHERE id_utilizador = $user_id
    ");

    if (!$resCliente || $resCliente->num_rows === 0) {
        return "<p class='text-muted'>Nenhum ficheiro recebido.</p>";
    }

    $codigoCliente = $resCliente->fetch_assoc()["codigo"];

    $res = $conn->query("
        SELECT nome_original, caminho, data_envio
        FROM plano_ficheiros
        WHERE cliente_id = $codigoCliente
        ORDER BY data_envio DESC
    ");

    if (!$res || $res->num_rows === 0) {
        return "<p class='text-muted'>Nenhum ficheiro recebido.</p>";
    }

    $html = "";

    while ($f = $res->fetch_assoc()) {
        $nome = htmlspecialchars($f["nome_original"], ENT_QUOTES);
        $caminho = htmlspecialchars($f["caminho"], ENT_QUOTES);
        $data = htmlspecialchars($f["data_envio"], ENT_QUOTES);

        $html .= "
        <div class='list-group-item d-flex justify-content-between align-items-center p-3 mb-2'>
            <div class='me-3'>
                <div class='fw-bold text-white'>{$nome}</div>
                <div class='text-muted small'>{$data}</div>
            </div>

            <div class='d-flex gap-2'>
                <button class='pt-action-btn pt-action-btn--green'
                        type='button'
                        onclick=\"verFicheiroNutri('{$caminho}','{$nome}','{$data}')\">
                    <i class='ri-eye-line'></i>
                </button>

                <a class='pt-action-btn pt-action-btn--blue' href='{$caminho}' download>
                    <i class='ri-download-line'></i>
                </a>
            </div>
        </div>";
    }

    return $html; 
}

}
