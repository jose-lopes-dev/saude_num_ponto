<?php
require_once "connection.php";
session_start();

class Dashboard
{

    function carregarDados()
    {
        global $conn;

        $id = $_SESSION["id"] ?? 0;
        if (!$id) {
            return ["flag" => false, "msg" => "Sessão inválida"];
        }


        $sql = "SELECT peso 
                FROM cliente 
                WHERE id_utilizador = ?
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $dadosCliente = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $sql2 = "SELECT peso 
                 FROM progresso_cliente 
                 WHERE id_utilizador = ?
                 ORDER BY data_registo ASC";

        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        $historico = [];
        while ($row = $result2->fetch_assoc()) {
            $historico[] = (float)$row["peso"];
        }
        $stmt2->close();


        $sql3 = "SELECT calorias, tempo_treino 
                 FROM progresso_cliente
                 WHERE id_utilizador = ?
                 ORDER BY data_registo DESC
                 LIMIT 1";

        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("i", $id);
        $stmt3->execute();
        $ultimo = $stmt3->get_result()->fetch_assoc();
        $stmt3->close();

        return [
            "flag" => true,
            "peso" => $dadosCliente["peso"] ?? null,
            "calorias" => $ultimo["calorias"] ?? null,
            "tempo" => $ultimo["tempo_treino"] ?? null,
            "historico" => $historico
        ];
    }



    function atualizarDados($peso, $calorias, $tempo)
    {
        global $conn;

        $id = $_SESSION["id"] ?? 0;
        if (!$id) {
            return ["flag" => false, "msg" => "Sessão inválida"];
        }


        $sql1 = "UPDATE cliente SET peso = ?
                 WHERE id_utilizador = ?
                 LIMIT 1";

        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("di", $peso, $id);
        $ok1 = $stmt1->execute();
        $stmt1->close();

        if (!$ok1) {
            return ["flag" => false, "msg" => "Erro ao atualizar cliente"];
        }

        $sql2 = "INSERT INTO progresso_cliente (id_utilizador, peso, calorias, tempo_treino, data_registo)
                 VALUES (?, ?, ?, ?, NOW())";

        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("iddi", $id, $peso, $calorias, $tempo);
        $ok2 = $stmt2->execute();
        $stmt2->close();

        if (!$ok2) {
            return ["flag" => false, "msg" => "Erro ao guardar histórico"];
        }

        return [
            "flag" => true,
            "msg" => "Dados atualizados com sucesso"
        ];
    }

function proximasConsultas()
{
    global $conn;

    $idUtilizador = $_SESSION["id"] ?? 0;
    if (!$idUtilizador) {
        return [];
    }

    $stmt = $conn->prepare("
        SELECT 
            'Consulta' AS titulo,
            DATE(c.data_hora) AS data,
            TIME(c.data_hora) AS hora_inicio,
            TIME(DATE_ADD(c.data_hora, INTERVAL 30 MINUTE)) AS hora_fim
        FROM consulta c
        INNER JOIN cliente cl ON cl.codigo = c.id_cliente
        WHERE cl.id_utilizador = ?
        AND c.data_hora >= NOW()
        AND c.id_estado = 15
        ORDER BY c.data_hora ASC
        LIMIT 3
    ");

    $stmt->bind_param("i", $idUtilizador);
    $stmt->execute();

    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}



function proximasAulas()
{
    global $conn;

    $idUtilizador = $_SESSION["id"] ?? 0;
    if (!$idUtilizador) {
        return [];
    }

    // BUSCAR CODIGO DO CLIENTE
    $stmtCliente = $conn->prepare("
        SELECT codigo 
        FROM cliente 
        WHERE id_utilizador = ?
        LIMIT 1
    ");
    $stmtCliente->bind_param("i", $idUtilizador);
    $stmtCliente->execute();
    $rowCliente = $stmtCliente->get_result()->fetch_assoc();
    $stmtCliente->close();

    $idCliente = $rowCliente["codigo"] ?? 0;
    if (!$idCliente) {
        return [];
    }

    $stmt = $conn->prepare("
        SELECT
            a.titulo,
            DATE(a.data_inicio) AS data,
            TIME(a.data_inicio) AS hora_inicio,
            TIME(DATE_ADD(a.data_inicio, INTERVAL a.duracao_min MINUTE)) AS hora_fim
        FROM aula a
        INNER JOIN inscricao_aula ia ON ia.id_aula = a.id
        WHERE ia.id_cliente = ?
        AND a.data_inicio >= NOW()
   AND ia.id_estado IN (13,16)

        ORDER BY a.data_inicio ASC
        LIMIT 3
    ");

    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $res = $stmt->get_result();

    return $res->fetch_all(MYSQLI_ASSOC);
}

function participacaoAtividades()
{
    global $conn;

    $idUtilizador = $_SESSION["id"] ?? 0;
    if (!$idUtilizador) {
        return ["flag" => false];
    }

    // BUSCAR CODIGO DO CLIENTE
    $stmt = $conn->prepare("
        SELECT codigo 
        FROM cliente 
        WHERE id_utilizador = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $idUtilizador);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $idCliente = $row["codigo"] ?? 0;
    if (!$idCliente) {
        return ["flag" => true, "consultas" => 0, "aulas" => 0];
    }

    // CONSULTAS REALIZADAS
    $stmt = $conn->prepare("
        SELECT COUNT(*) total
        FROM consulta
        WHERE id_cliente = ?
          AND data_hora < NOW()
          AND id_estado = 16
    ");
    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $consultas = $stmt->get_result()->fetch_assoc()["total"] ?? 0;
    $stmt->close();

   // AULAS REALIZADAS
$stmt = $conn->prepare("
    SELECT COUNT(*) total
    FROM inscricao_aula ia
    WHERE ia.id_cliente = ?
      AND ia.created_at < NOW()
      AND ia.id_estado = 16
");


    $stmt->bind_param("i", $idCliente);
    $stmt->execute();
    $aulas = $stmt->get_result()->fetch_assoc()["total"] ?? 0;
    $stmt->close();

    return [
        "flag" => true,
        "consultas" => (int)$consultas,
        "aulas" => (int)$aulas
    ];
}



}
?>
