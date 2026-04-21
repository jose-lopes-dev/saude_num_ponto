<?php
require_once "connection.php";

class ExercicioPT {

    public function registar($p) {
        global $conn;

        $nome = trim($p["nome"] ?? "");
        $grupo = $p["grupo"] ?? "";
        $equipamento = $p["equipamento"] ?? "";
        $tipo = $p["tipo"] ?? "";
        $dificuldade = $p["dificuldade"] ?? "";
        $descricao = $p["descricao"] ?? "";
        $video_url = $p["video_url"] ?? "";
        $imagem_url = $p["imagem_url"] ?? "";

        if ($nome == "" || $grupo == "" || $equipamento == "" || $tipo == "") {
            return json_encode(["flag"=>false, "msg"=>"Preencha os campos obrigatórios"]);
        }

        $stmt = $conn->prepare("
            INSERT INTO exercicio (nome, grupo, equipamento, tipo, dificuldade, descricao, video_url, imagem_url, ativo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
        ");
        $stmt->bind_param("ssssssss", $nome, $grupo, $equipamento, $tipo, $dificuldade, $descricao, $video_url, $imagem_url);

        if ($stmt->execute()) {
            return json_encode(["flag"=>true, "msg"=>"Exercício registado com sucesso"]);
        }

        return json_encode(["flag"=>false, "msg"=>"Erro ao registar exercício"]);
    }

    public function listar($p) {
        global $conn;

        $page = (int)($p["page"] ?? 1);
        $perPage = (int)($p["per_page"] ?? 10);
        if ($page < 1) $page = 1;
        if ($perPage < 1) $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $f_grupo = $p["f_grupo"] ?? "todos";
        $f_equip = $p["f_equip"] ?? "todos";
        $f_pesquisa = trim($p["f_pesquisa"] ?? "");

        $where = " WHERE ativo = 1 ";
        $types = "";
        $params = [];

        if ($f_grupo !== "" && $f_grupo !== "todos") {
            $where .= " AND grupo = ? ";
            $types .= "s";
            $params[] = $f_grupo;
        }

        if ($f_equip !== "" && $f_equip !== "todos") {
            $where .= " AND equipamento = ? ";
            $types .= "s";
            $params[] = $f_equip;
        }

        if ($f_pesquisa !== "") {
            $where .= " AND nome LIKE ? ";
            $types .= "s";
            $params[] = "%".$f_pesquisa."%";
        }

        $sqlCount = "SELECT COUNT(*) AS total FROM exercicio $where";
        $stmtC = $conn->prepare($sqlCount);
        if ($types !== "") {
            $stmtC->bind_param($types, ...$params);
        }
        $stmtC->execute();
        $total = (int)($stmtC->get_result()->fetch_assoc()["total"] ?? 0);

        $sql = "
            SELECT id_exercicio, nome, grupo, equipamento, tipo, dificuldade, imagem_url
            FROM exercicio
            $where
            ORDER BY nome ASC
            LIMIT ? OFFSET ?
        ";

        $stmt = $conn->prepare($sql);

        $types2 = $types . "ii";
        $params2 = $params;
        $params2[] = $perPage;
        $params2[] = $offset;

        $stmt->bind_param($types2, ...$params2);

        $stmt->execute();
        $res = $stmt->get_result();

        $html = "";
        while ($r = $res->fetch_assoc()) {
            $id = (int)$r["id_exercicio"];

            $html .= "<tr>";
            $html .= "<td>".htmlspecialchars($r["nome"])."</td>";
            $html .= "<td>".htmlspecialchars($r["grupo"])."</td>";
            $html .= "<td>".htmlspecialchars($r["equipamento"])."</td>";
            $html .= "<td>".htmlspecialchars($r["tipo"])."</td>";
            $html .= "<td>".htmlspecialchars($r["dificuldade"] ?? "")."</td>";

            $img = trim($r["imagem_url"] ?? "");
            $html .= "<td class='text-center'>";
            if ($img !== "") {
                $safeImg = htmlspecialchars($img);
                $html .= "<a class='btn btn-sm btn-outline-info btn-icon' href='{$safeImg}' target='_blank' title='Ver imagem'>
                            <i class='ri-eye-line'></i>
                        </a>";
            } else {
                $html .= "<span class='text-muted'>-</span>";
            }
            $html .= "</td>";

            $html .= "<td class='text-center'>
                        <button type='button' class='btn btn-sm btn-warning btn-icon' onclick='getDadosExercicio($id)' title='Editar'>
                            <i class='ri-pencil-line'></i>
                        </button>
                    </td>";

            $html .= "<td class='text-center'>
                        <button type='button' class='btn btn-sm btn-danger btn-icon' onclick='removerExercicio($id)' title='Remover'>
                            <i class='ri-delete-bin-line'></i>
                        </button>
                    </td>";

            $html .= "</tr>";
        }

        $pag = "";

        $totalPages = ($perPage > 0) ? (int)ceil($total / $perPage) : 1;
        if ($totalPages < 1) $totalPages = 1;

        $prev = $page - 1;
        $next = $page + 1;

        $disabledPrev = ($page <= 1) ? " disabled" : "";
        $disabledNext = ($page >= $totalPages) ? " disabled" : "";

        $prevPage = ($page <= 1) ? 1 : $prev;
        $nextPage = ($page >= $totalPages) ? $totalPages : $next;

        $pag .= "<li class='page-item{$disabledPrev}'>
                    <a class='page-link' href='#' data-page='{$prevPage}' aria-label='Anterior'>&laquo;</a>
                </li>";

        $pag .= "<li class='page-item{$disabledNext}'>
                    <a class='page-link' href='#' data-page='{$nextPage}' aria-label='Próxima'>&raquo;</a>
                </li>";

        $start = ($total === 0) ? 0 : ($offset + 1);
        $end = min($offset + $perPage, $total);
        $info = "A mostrar {$start}–{$end} de {$total}";

        return json_encode(["flag" => true, "html" => $html, "paginacao" => $pag, "info" => $info]);
    }


    public function remover($p) {
        global $conn;

        $id = (int)($p["id"] ?? 0);
        if ($id <= 0) {
            return json_encode(["flag"=>false, "msg"=>"ID inválido"]);
        }

        $stmt = $conn->prepare("UPDATE exercicio SET ativo = 0 WHERE id_exercicio = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return json_encode(["flag"=>true, "msg"=>"Exercício removido"]);
        }

        return json_encode(["flag"=>false, "msg"=>"Erro ao remover"]);
    }

    public function getDados($p) {
        global $conn;

        $id = (int)($p["id"] ?? 0);
        if ($id <= 0) {
            return json_encode(["flag"=>false, "msg"=>"ID inválido"]);
        }

        $stmt = $conn->prepare("
            SELECT id_exercicio, nome, grupo, equipamento, tipo, dificuldade, descricao, video_url, imagem_url
            FROM exercicio
            WHERE id_exercicio = ? AND ativo = 1
            LIMIT 1
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            return json_encode(["flag"=>false, "msg"=>"Exercício não encontrado"]);
        }

        $r = $res->fetch_assoc();
        $r["flag"] = true;

        return json_encode($r);
    }

    public function editar($p) {
        global $conn;

        $id = (int)($p["id"] ?? 0);
        $nome = trim($p["nome"] ?? "");
        $grupo = $p["grupo"] ?? "";
        $equipamento = $p["equipamento"] ?? "";
        $tipo = $p["tipo"] ?? "";
        $dificuldade = $p["dificuldade"] ?? "";
        $descricao = $p["descricao"] ?? "";
        $video_url = $p["video_url"] ?? "";
        $imagem_url = $p["imagem_url"] ?? "";

        if ($id <= 0 || $nome == "" || $grupo == "" || $equipamento == "" || $tipo == "") {
            return json_encode(["flag"=>false, "msg"=>"Preencha os campos obrigatórios"]);
        }

        $stmt = $conn->prepare("
            UPDATE exercicio
            SET nome = ?, grupo = ?, equipamento = ?, tipo = ?, dificuldade = ?, descricao = ?, video_url = ? , imagem_url = ?
            WHERE id_exercicio = ? AND ativo = 1
        ");
        $stmt->bind_param("ssssssssi", $nome, $grupo, $equipamento, $tipo, $dificuldade, $descricao, $video_url, $imagem_url, $id);

        if ($stmt->execute()) {
            return json_encode(["flag"=>true, "msg"=>"Exercício atualizado"]);
        }

        return json_encode(["flag"=>false, "msg"=>"Erro ao atualizar"]);
    }

    public function listarSelect($p) {
        global $conn;

        $grupo = $p["grupo"] ?? "todos";
        $equipamento = $p["equipamento"] ?? "todos";
        $q = trim($p["q"] ?? "");

        $where = " WHERE ativo = 1 ";
        $types = "";
        $params = [];

        if ($grupo !== "" && $grupo !== "todos") {
            $where .= " AND grupo = ? ";
            $types .= "s";
            $params[] = $grupo;
        }

        if ($equipamento !== "" && $equipamento !== "todos") {
            $where .= " AND equipamento = ? ";
            $types .= "s";
            $params[] = $equipamento;
        }

        if ($q !== "") {
            $where .= " AND nome LIKE ? ";
            $types .= "s";
            $params[] = "%".$q."%";
        }

        $sql = "
            SELECT id_exercicio AS id, nome AS text
            FROM exercicio
            $where
            ORDER BY nome ASC
            LIMIT 50
        ";

        $stmt = $conn->prepare($sql);
        if ($types !== "") $stmt->bind_param($types, ...$params);

        $stmt->execute();
        $res = $stmt->get_result();

        $items = [];
        while ($r = $res->fetch_assoc()) $items[] = $r;

        return json_encode(["flag"=>true, "items"=>$items]);
    }
}

