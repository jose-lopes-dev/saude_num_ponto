<?php
require_once __DIR__ . "/connection.php";

class PlanoNutricionista {

    public function listarClientes() {
        global $conn;

        $res = $conn->query("
            SELECT codigo, nome_completo
            FROM cliente
            ORDER BY nome_completo
        ");

        $html = "<option value=''>Seleciona o cliente</option>";

        if ($res) {
            while ($c = $res->fetch_assoc()) {
                $html .= "<option value='{$c["codigo"]}'>{$c["nome_completo"]}</option>";
            }
        }

        return $html;
    }

    public function enviarPlano($userId, $cliente, $ficheiro) {
        global $conn;

        $res = $conn->query("
            SELECT codigo
            FROM rh
            WHERE id_utilizador = $userId
        ");

        if (!$res || $res->num_rows === 0) {
            return ["flag" => false, "msg" => "Utilizador não é nutricionista"];
        }

        $codigo_rh = $res->fetch_assoc()["codigo"];

        if ($ficheiro["error"] !== 0) {
            return ["flag" => false, "msg" => "Erro no ficheiro"];
        }

        $ext = strtolower(pathinfo($ficheiro["name"], PATHINFO_EXTENSION));
        if (!in_array($ext, ["pdf", "doc", "docx"])) {
            return ["flag" => false, "msg" => "Formato inválido"];
        }

        $dir = __DIR__ . "/../../uploads/planos/";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $nome = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "", $ficheiro["name"]);
        $destino = $dir . $nome;

        if (!move_uploaded_file($ficheiro["tmp_name"], $destino)) {
            return ["flag" => false, "msg" => "Erro ao guardar ficheiro"];
        }

        $ok = $conn->query("
            INSERT INTO plano_nutricionista
            (codigo_rh, codigo_cliente, ficheiro)
            VALUES ($codigo_rh, $cliente, '$nome')
        ");

        if (!$ok) {
            return ["flag" => false, "msg" => $conn->error];
        }

$nomeOriginal = $conn->real_escape_string($ficheiro["name"]);
$nomeFicheiro = $conn->real_escape_string($nome);
$caminho = "uploads/planos/$nomeFicheiro";

$ok2 = $conn->query("
    INSERT INTO plano_ficheiros
    (cliente_id, nutricionista_id, nome_original, nome_ficheiro, caminho, data_envio)
    VALUES
    ($cliente, $codigo_rh, '$nomeOriginal', '$nomeFicheiro', '$caminho', NOW())
");

if (!$ok2) {
    return ["flag" => false, "msg" => $conn->error];
}


        return ["flag" => true, "msg" => "Plano enviado com sucesso"];
    }

    public function listarPlanosEnviados($nutricionista) {
        global $conn;

        $res = $conn->query("
            SELECT p.ficheiro, c.nome_completo
            FROM plano_nutricionista p
            JOIN cliente c ON c.codigo = p.codigo_cliente
            WHERE p.codigo_rh = $nutricionista
            ORDER BY p.id DESC
        ");

        if (!$res || $res->num_rows === 0) {
            return "<p class='text-muted'>Nenhum ficheiro enviado.</p>";
        }

        $html = "<ul class='list-group'>";

        while ($r = $res->fetch_assoc()) {
            $html .= "
                <li class='list-group-item d-flex justify-content-between align-items-center'>
                    <span>
                        {$r["ficheiro"]}
                        <br>
                        <small class='text-muted'>Cliente: {$r["nome_completo"]}</small>
                    </span>
                    <a href='uploads/planos/{$r["ficheiro"]}' target='_blank'
                       class='btn btn-sm btn-primary'>
                        Abrir
                    </a>
                </li>
            ";
        }

        return $html . "</ul>";
    }
}
