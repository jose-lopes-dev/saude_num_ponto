<?php
require_once __DIR__ . "/../model/connection.php";
session_start();

header("Content-Type: application/json");

if (!isset($_SESSION["id"])) {
    echo json_encode([]);
    exit;
}

$userId = (int) $_SESSION["id"];

/* =======================
   OBTÉM CÓDIGO RH
======================= */
$r = $conn->query("
    SELECT codigo 
    FROM rh 
    WHERE id_utilizador = $userId
");

if (!$r || !$r->num_rows) {
    echo json_encode([]);
    exit;
}

$codigo_rh = (int) $r->fetch_assoc()["codigo"];
$op = $_POST["op"] ?? "";

/* =======================
   LISTAR EVENTOS MANUAIS
======================= */
if ($op === "listar") {

    $res = $conn->query("
        SELECT 
            id,
            titulo,
            categoria,
            data_inicio,
            data_fim,
            descricao
        FROM calendario_nutricionista
        WHERE codigo_rh = $codigo_rh
        ORDER BY data_inicio
    ");

    $out = [];

    while ($e = $res->fetch_assoc()) {
        $out[] = [
            "id" => $e["id"],
            "title" => $e["titulo"],
            "start" => $e["data_inicio"],
            "end" => $e["data_fim"],
            "extendedProps" => [
                "categoria" => $e["categoria"],
                "descricao" => $e["descricao"]
            ]
        ];
    }

    echo json_encode($out);
    exit;
}

/* =======================
   LISTAR CONSULTAS
======================= */
if ($op === "listar_consultas") {

    $res = $conn->query("
        SELECT 
            c.id,
            c.data_hora,
            cl.nome_completo AS cliente
        FROM consulta c
        JOIN cliente cl ON cl.codigo = c.id_cliente
        WHERE c.id_prestador = $codigo_rh
          AND c.data_hora IS NOT NULL
        ORDER BY c.data_hora
    ");

    $out = [];

    while ($c = $res->fetch_assoc()) {

        $inicio = $c["data_hora"];
        $fim = date("Y-m-d H:i:s", strtotime($inicio . " +1 hour"));

        $out[] = [
            "id" => $c["id"],
            "data_hora" => $inicio,
            "data_fim" => $fim,
            "cliente" => $c["cliente"]
        ];
    }

    echo json_encode($out);
    exit;
}


/* =======================
   GUARDAR / EDITAR EVENTO
======================= */
if ($op === "guardar") {

    $id = $_POST["id"] ?? "";

    $titulo = $conn->real_escape_string($_POST["titulo"] ?? "");
    $categoria = $conn->real_escape_string($_POST["categoria"] ?? "Evento Próprio");
    $inicio = $_POST["inicio"] ?? "";
    $fim = $_POST["fim"] ?? "";
    $descricao = $conn->real_escape_string($_POST["descricao"] ?? "");

    if ($id) {

        $id = (int) $id;

        $conn->query("
            UPDATE calendario_nutricionista
            SET 
                titulo = '$titulo',
                categoria = '$categoria',
                data_inicio = '$inicio',
                data_fim = '$fim',
                descricao = '$descricao'
            WHERE id = $id
              AND codigo_rh = $codigo_rh
        ");

    } else {

        $conn->query("
            INSERT INTO calendario_nutricionista
            (codigo_rh, titulo, categoria, data_inicio, data_fim, descricao)
            VALUES
            ($codigo_rh, '$titulo', '$categoria', '$inicio', '$fim', '$descricao')
        ");
    }

    echo json_encode(["ok" => 1]);
    exit;
}

/* =======================
   CONCLUIR EVENTO
======================= */
if ($op === "concluir") {

    $id = (int) $_POST["id"];

    $conn->query("
        UPDATE calendario_nutricionista
        SET categoria = 'Concluído'
        WHERE id = $id
          AND codigo_rh = $codigo_rh
    ");

    echo json_encode(["ok" => 1]);
    exit;
}

/* =======================
   REMOVER EVENTO
======================= */
if ($op === "remover") {

    $id = (int) $_POST["id"];

    $conn->query("
        DELETE FROM calendario_nutricionista
        WHERE id = $id
          AND codigo_rh = $codigo_rh
    ");

    echo json_encode(["ok" => 1]);
    exit;
}

/* =======================
   FALLBACK
======================= */
echo json_encode([]);
