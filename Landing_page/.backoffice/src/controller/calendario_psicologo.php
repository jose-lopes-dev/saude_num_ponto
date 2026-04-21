<?php
require_once __DIR__ . "/../model/connection.php";
session_start();

if (!isset($_SESSION["id"])) exit;

$userId = intval($_SESSION["id"]);

$r = $conn->query("SELECT codigo FROM rh WHERE id_utilizador = $userId");
if (!$r || !$r->num_rows) exit;

$codigo_rh = intval($r->fetch_assoc()["codigo"]);
$op = $_POST["op"] ?? "";

/* =======================
   LISTAR EVENTOS MANUAIS
======================= */
if ($op === "listar") {

    $res = $conn->query("
        SELECT id, titulo, categoria, data_inicio, data_fim, descricao
        FROM calendario_psicologo
        WHERE codigo_rh = $codigo_rh
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
   LISTAR SESSÕES
======================= */
if ($op === "listar_sessoes") {

    $res = $conn->query("
        SELECT 
            c.id,
            c.data_hora,
            cl.nome_completo AS cliente
        FROM consulta c
        JOIN cliente cl ON cl.codigo = c.id_cliente
        WHERE c.id_prestador = $codigo_rh
    ");

    $out = [];

    while ($c = $res->fetch_assoc()) {
        $out[] = [
            "id" => $c["id"],
            "data_hora" => $c["data_hora"],
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
    $titulo = $conn->real_escape_string($_POST["titulo"]);
    $categoria = $conn->real_escape_string($_POST["categoria"]);
    $inicio = $_POST["inicio"];
    $fim = $_POST["fim"];
    $descricao = $conn->real_escape_string($_POST["descricao"] ?? "");

    if ($id) {
        $id = intval($id);
        $conn->query("
            UPDATE calendario_psicologo
            SET titulo='$titulo',
                categoria='$categoria',
                data_inicio='$inicio',
                data_fim='$fim',
                descricao='$descricao'
            WHERE id=$id AND codigo_rh=$codigo_rh
        ");
    } else {
        $conn->query("
            INSERT INTO calendario_psicologo
            (codigo_rh, titulo, categoria, data_inicio, data_fim, descricao)
            VALUES
            ($codigo_rh, '$titulo', '$categoria', '$inicio', '$fim', '$descricao')
        ");
    }

    exit;
}

/* =======================
   CONCLUIR EVENTO
======================= */
if ($op === "concluir") {

    $id = intval($_POST["id"]);

    $conn->query("
        UPDATE calendario_psicologo
        SET categoria='Concluído'
        WHERE id=$id AND codigo_rh=$codigo_rh
    ");

    exit;
}

/* =======================
   REMOVER EVENTO
======================= */
if ($op === "remover") {

    $id = intval($_POST["id"]);

    $conn->query("
        DELETE FROM calendario_psicologo
        WHERE id=$id AND codigo_rh=$codigo_rh
    ");

    exit;
}
