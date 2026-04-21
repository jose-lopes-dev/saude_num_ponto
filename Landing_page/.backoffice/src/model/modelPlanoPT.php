<?php
require_once "connection.php";

class ModelPlanoPT {

    public function listarClientes($codigo_rh) {
        global $conn;

        $stmt = $conn->prepare("SELECT codigo, nome_completo FROM cliente ORDER BY nome_completo ASC");
        $stmt->execute();
        $res = $stmt->get_result();

        $clientes = [];
        while ($r = $res->fetch_assoc()) {
            $clientes[] = [
                "codigo" => (int)$r["codigo"],
                "nome_completo" => $r["nome_completo"]
            ];
        }

        return json_encode(["flag"=>true, "clientes"=>$clientes]);
    }

    public function listarExerciciosSelect($q, $grupo, $equip) {
        global $conn;

        $qLike = "%".$q."%";

        $sql = "SELECT id_exercicio, nome FROM exercicio WHERE ativo=1";
        $params = [];
        $types = "";

        if ($grupo !== "" && strtolower($grupo) !== "todos") {
            $sql .= " AND grupo = ?";
            $types .= "s";
            $params[] = $grupo;
        }
        if ($equip !== "" && strtolower($equip) !== "todos") {
            $sql .= " AND equipamento = ?";
            $types .= "s";
            $params[] = $equip;
        }

        $sql .= " AND nome LIKE ? ORDER BY nome ASC LIMIT 50";
        $types .= "s";
        $params[] = $qLike;

        $stmt = $conn->prepare($sql);
        if ($types !== "") {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $res = $stmt->get_result();

        $items = [];
        while ($r = $res->fetch_assoc()) {
            $items[] = [
                "id" => (int)$r["id_exercicio"],
                "text" => $r["nome"]
            ];
        }

        return json_encode(["flag"=>true, "items"=>$items]);
    }

    public function criarPlano($codigo_rh, $cliente_id, $titulo) {
        global $conn;

        if ($cliente_id <= 0) {
            return json_encode(["flag"=>false, "msg"=>"Seleciona um cliente"]);
        }
        if ($titulo == "") $titulo = "Plano de Treino";

        $stmt = $conn->prepare("
            INSERT INTO plano_pt (codigo_rh, codigo_cliente, titulo, criado_por)
            VALUES (?, ?, ?, 'PT')
        ");
        $stmt->bind_param("iis", $codigo_rh, $cliente_id, $titulo);

        if (!$stmt->execute()) {
            return ["flag"=>false, "msg"=>"Erro ao criar plano"];
        }

        return json_encode(["flag"=>true, "msg"=>"Plano criado", "plano_id"=>$stmt->insert_id]);
    }

    public function guardarDia($codigo_rh, $plano_id, $dia_semana, $nome_dia, $itens_json) {
        global $conn;

        if ($plano_id <= 0 || $dia_semana < 1 || $dia_semana > 7) {
            return json_encode(["flag"=>false, "msg"=>"Dados inválidos"]);
        }

        $stmt = $conn->prepare("SELECT id FROM plano_pt WHERE id=? AND codigo_rh=? LIMIT 1");
        $stmt->bind_param("ii", $plano_id, $codigo_rh);
        $stmt->execute();
        $res = $stmt->get_result();
        if (!$res || $res->num_rows == 0) {
            return ["flag"=>false, "msg"=>"Plano não encontrado"];
        }

        $itens = json_decode($itens_json, true);
        if (!is_array($itens)) $itens = [];

        $stmtDia = $conn->prepare("
            INSERT INTO plano_pt_dia (plano_id, dia_semana, nome)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE nome = VALUES(nome)
        ");
        $stmtDia->bind_param("iis", $plano_id, $dia_semana, $nome_dia);
        if (!$stmtDia->execute()) {
            return ["flag"=>false, "msg"=>"Erro ao guardar dia"];
        }

        $stmtGet = $conn->prepare("SELECT id FROM plano_pt_dia WHERE plano_id=? AND dia_semana=? LIMIT 1");
        $stmtGet->bind_param("ii", $plano_id, $dia_semana);
        $stmtGet->execute();
        $resGet = $stmtGet->get_result();
        $diaRow = $resGet->fetch_assoc();
        $plano_dia_id = (int)$diaRow["id"];

        $stmtDel = $conn->prepare("DELETE FROM plano_pt_dia_exercicio WHERE plano_dia_id=?");
        $stmtDel->bind_param("i", $plano_dia_id);
        $stmtDel->execute();

        if (count($itens) > 0) {
            $stmtIns = $conn->prepare("
                INSERT INTO plano_pt_dia_exercicio
                (plano_dia_id, id_exercicio, ordem, series, reps, descanso_seg, rpe, tempo, observacoes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            foreach ($itens as $idx => $it) {
                $id_ex = (int)($it["id_exercicio"] ?? 0);
                if ($id_ex <= 0) continue;

                $ordem = (int)($it["ordem"] ?? ($idx+1));
                $series = isset($it["series"]) && $it["series"] !== "" ? (int)$it["series"] : null;
                $reps = trim($it["reps"] ?? "");
                $desc = isset($it["descanso_seg"]) && $it["descanso_seg"] !== "" ? (int)$it["descanso_seg"] : null;
                $rpe = isset($it["rpe"]) && $it["rpe"] !== "" ? (float)$it["rpe"] : null;
                $tempo = trim($it["tempo"] ?? "");
                $obs = trim($it["observacoes"] ?? "");

                $series2 = $series === null ? 0 : $series;
                $desc2   = $desc === null ? 0 : $desc;
                $rpe2    = $rpe === null ? 0.0 : $rpe;

                $stmtIns->bind_param(
                    "iiiisidss",
                    $plano_dia_id,
                    $id_ex,
                    $ordem,
                    $series2,
                    $reps,
                    $desc2,
                    $rpe2,
                    $tempo,
                    $obs
                );

                if (!$stmtIns->execute()) {
                    return ["flag"=>false, "msg"=>"Erro ao inserir exercícios do dia"];
                }
            }
        }

        return json_encode(["flag"=>true, "msg"=>"Dia guardado"]);
    }

    public function listarPlanos($codigo_rh, $cliente_id) {
        global $conn;

        if ($cliente_id <= 0) return ["flag"=>true, "planos"=>[]];

        $stmt = $conn->prepare("
            SELECT id, titulo, criado_em, publicado
            FROM plano_pt
            WHERE codigo_rh=? AND codigo_cliente=?
            ORDER BY id DESC
        ");
        $stmt->bind_param("ii", $codigo_rh, $cliente_id);
        $stmt->execute();
        $res = $stmt->get_result();

        $planos = [];
        while ($r = $res->fetch_assoc()) {
            $planos[] = [
                "id" => (int)$r["id"],
                "titulo" => $r["titulo"] ?? "Plano",
                "criado_em" => $r["criado_em"],
                "publicado" => (int)$r["publicado"]
            ];
        }

        return json_encode(["flag"=>true, "planos"=>$planos]);
    }

    public function detalhesPlano($codigo_rh, $plano_id) {
        global $conn;

        $stmt = $conn->prepare("
            SELECT id, codigo_cliente, titulo, criado_em
            FROM plano_pt
            WHERE id=? AND codigo_rh=?
            LIMIT 1
        ");
        $stmt->bind_param("ii", $plano_id, $codigo_rh);
        $stmt->execute();
        $res = $stmt->get_result();

        if (!$res || $res->num_rows == 0) {
            return ["flag"=>false, "msg"=>"Plano não encontrado"];
        }
        $plano = $res->fetch_assoc();

        $stmtD = $conn->prepare("
            SELECT id, dia_semana, nome
            FROM plano_pt_dia
            WHERE plano_id=?
            ORDER BY dia_semana ASC
        ");
        $stmtD->bind_param("i", $plano_id);
        $stmtD->execute();
        $resD = $stmtD->get_result();

        $dias = [];
        while ($d = $resD->fetch_assoc()) {
            $dia_id = (int)$d["id"];

            $stmtE = $conn->prepare("
                SELECT pde.id_exercicio, e.nome, pde.ordem, pde.series, pde.reps, pde.descanso_seg, pde.rpe, pde.tempo, pde.observacoes
                FROM plano_pt_dia_exercicio pde
                INNER JOIN exercicio e ON e.id_exercicio = pde.id_exercicio
                WHERE pde.plano_dia_id=?
                ORDER BY pde.ordem ASC
            ");
            $stmtE->bind_param("i", $dia_id);
            $stmtE->execute();
            $resE = $stmtE->get_result();

            $exs = [];
            while ($x = $resE->fetch_assoc()) {
                $exs[] = [
                    "id_exercicio" => (int)$x["id_exercicio"],
                    "nome" => $x["nome"],
                    "ordem" => (int)$x["ordem"],
                    "series" => (int)$x["series"],
                    "reps" => $x["reps"],
                    "descanso_seg" => (int)$x["descanso_seg"],
                    "rpe" => (float)$x["rpe"],
                    "tempo" => $x["tempo"],
                    "observacoes" => $x["observacoes"]
                ];
            }

            $dias[] = [
                "dia_semana" => (int)$d["dia_semana"],
                "nome" => $d["nome"] ?? "",
                "exercicios" => $exs
            ];
        }

        return json_encode(["flag"=>true, "plano"=>$plano, "dias"=>$dias]);
    }

    public function eliminarPlano($codigo_rh, $plano_id){
        global $conn;

        if($plano_id <= 0){
            return json_encode(['flag'=>false,'msg'=>'Plano inválido']);
        }

        $stmt = $conn->prepare("SELECT id FROM plano_pt WHERE id=? AND codigo_rh=? LIMIT 1");
        $stmt->bind_param("ii", $plano_id, $codigo_rh);
        $stmt->execute();
        $res = $stmt->get_result();

        if(!$res || $res->num_rows == 0){
            return json_encode(['flag'=>false,'msg'=>'Plano não encontrado']);
        }

        $stmt2 = $conn->prepare("DELETE FROM plano_pt WHERE id=? AND codigo_rh=?");
        $stmt2->bind_param("ii", $plano_id, $codigo_rh);

        if($stmt2->execute()){
            return json_encode(['flag'=>true,'msg'=>'Plano eliminado']);
        }

        return json_encode(['flag'=>false,'msg'=>'Erro ao eliminar plano']);
    }

    public function publicarPlano($codigo_rh, $plano_id, $publicado) {
        global $conn;

        if ($plano_id <= 0) {
            return json_encode(["flag"=>false, "msg"=>"Plano inválido"]);
        }

        $publicado = $publicado ? 1 : 0;

        $stmt = $conn->prepare("
            SELECT id
            FROM plano_pt
            WHERE id=? AND codigo_rh=? AND criado_por='PT'
            LIMIT 1
        ");
        $stmt->bind_param("ii", $plano_id, $codigo_rh);
        $stmt->execute();
        $res = $stmt->get_result();

        if (!$res || $res->num_rows == 0) {
            return json_encode(["flag"=>false, "msg"=>"Plano não encontrado"]);
        }

        $stmt2 = $conn->prepare("
            UPDATE plano_pt
            SET publicado=?
            WHERE id=? AND codigo_rh=? AND criado_por='PT'
        ");
        $stmt2->bind_param("iii", $publicado, $plano_id, $codigo_rh);

        if ($stmt2->execute()) {
            $msg = $publicado ? "Plano publicado e visível para o cliente" : "Plano voltou a rascunho";
            return json_encode(["flag"=>true, "msg"=>$msg]);
        }

        return json_encode(["flag"=>false, "msg"=>"Erro ao atualizar publicação"]);
    }
}
