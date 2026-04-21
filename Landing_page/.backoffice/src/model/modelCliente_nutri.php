<?php
require_once 'connection.php';

class ClienteNutri {

   function getStatsClientesNutri($idNutri){
    global $conn;

    $dados = [
        "total_clientes" => 0,
        "consultas_semana" => 0,
        "consultas_futuras" => 0,
        "taxa_conclusao" => 0
    ];

    $sql = "
        SELECT COUNT(DISTINCT co.id_cliente) total
        FROM consulta co
        WHERE co.id_prestador = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idNutri);
    $stmt->execute();
    $dados["total_clientes"] = (int)$stmt->get_result()->fetch_assoc()["total"];
    $stmt->close();

    $sql = "
        SELECT COUNT(*) total
        FROM consulta co
        WHERE co.id_prestador = ?
        AND YEARWEEK(DATE(co.data_hora),1) = YEARWEEK(CURDATE(),1)
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idNutri);
    $stmt->execute();
    $dados["consultas_semana"] = (int)$stmt->get_result()->fetch_assoc()["total"];
    $stmt->close();

    $sql = "
        SELECT COUNT(*) total
        FROM consulta co
        WHERE co.id_prestador = ?
        AND DATE(co.data_hora) >= CURDATE()
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idNutri);
    $stmt->execute();
    $dados["consultas_futuras"] = (int)$stmt->get_result()->fetch_assoc()["total"];
    $stmt->close();

    $sql = "
        SELECT
            SUM(CASE WHEN co.id_estado = 16 THEN 1 ELSE 0 END) concluidas,
            COUNT(*) total
        FROM consulta co
        WHERE co.id_prestador = ?
        AND DATE(co.data_hora) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idNutri);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if($r["total"] > 0){
        $dados["taxa_conclusao"] = round(($r["concluidas"] / $r["total"]) * 100);
    }

    return json_encode(["flag"=>true,"msg"=>"","dados"=>$dados]);
}


  function getListaClientesNutri($idNutri){
    global $conn;

    $sql = "
        SELECT 
            c.codigo,
            c.nome_completo,
            c.contacto,
            u.email,
            o.descricao AS objetivo,
            COUNT(co.id) AS num_consultas,
            MAX(CASE WHEN co.data_hora <= NOW() THEN co.data_hora END) AS ultima_consulta,
            e.descricao AS estado_desc,
            c.id_estado
        FROM consulta co
        INNER JOIN cliente c ON c.codigo = co.id_cliente
        LEFT JOIN utilizador u ON u.id = c.id_utilizador
        LEFT JOIN objetivo o ON o.id = c.id_objetivo
        LEFT JOIN estado e ON e.id = c.id_estado
        WHERE co.id_prestador = ?
        GROUP BY 
            c.codigo, c.nome_completo, c.contacto,
            u.email, o.descricao, e.descricao, c.id_estado
        ORDER BY ultima_consulta DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idNutri);
    $stmt->execute();

    $dados = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return json_encode(["flag"=>true,"msg"=>"","dados"=>$dados]);
}


    function getDadosClienteNutri($idCliente){
        global $conn;

        $flag = true;
        $msg = "";
        $dados = null;

        $sql = "

        SELECT
                c.codigo,
                c.id_utilizador,
                c.nome_completo,
                c.contacto,
                c.data_nascimento,
                c.id_estado,
                c.id_objetivo,
                u.email
            FROM cliente c
            LEFT JOIN utilizador u ON u.id = c.id_utilizador
            WHERE c.codigo = ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
        $res = $stmt->get_result();

        if($res && $res->num_rows > 0){
            $dados = $res->fetch_assoc();
        } else {
            $flag = false;
            $msg = "Cliente não encontrado";
        }

        $stmt->close();

        return json_encode(array("flag"=>$flag, "msg"=>$msg, "dados"=>$dados));
    }

    function guardaEditClienteNutri($idCliente, $nome, $tel, $email, $dataNasc, $estado, $objetivo){
        global $conn;

        $flag = true;
        $msg = "";

        $sqlU = "SELECT id_utilizador FROM cliente WHERE codigo = ?";
        $stmt = $conn->prepare($sqlU);
        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
        $r = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if(!$r){
            return json_encode(array("flag"=>false, "msg"=>"Cliente não encontrado"));
        }

        $idUtilizador = intval($r["id_utilizador"]);

        $sqlC = "
            UPDATE cliente
            SET nome_completo = ?,
                contacto = ?,
                data_nascimento = ?,
                id_estado = ?,
                id_objetivo = ?
            WHERE codigo = ?
        ";
        $stmt = $conn->prepare($sqlC);
        $stmt->bind_param("sssiii", $nome, $tel, $dataNasc, $estado, $objetivo, $idCliente);

        if($stmt->execute()){
            $msg = "Editado com sucesso";
        } else {
            $flag = false;
            $msg = "Erro ao editar cliente";
        }
        $stmt->close();

        if($flag){
            $sqlE = "UPDATE utilizador SET email = ? WHERE id = ?";
            $stmt = $conn->prepare($sqlE);
            $stmt->bind_param("si", $email, $idUtilizador);
            if(!$stmt->execute()){
                $flag = false;
                $msg = "Erro ao editar email";
            }
            $stmt->close();
        }

        return json_encode(array("flag"=>$flag, "msg"=>$msg));
    }

    function alteraEstadoClienteNutri($idCliente, $idEstado){
        global $conn;

        $flag = true;
        $msg = "";

        $sql = "UPDATE cliente SET id_estado = ? WHERE codigo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $idEstado, $idCliente);

        if($stmt->execute()){
            $msg = "Estado alterado com sucesso";
        } else {
            $flag = false;
            $msg = "Erro ao alterar estado";
        }

        $stmt->close();

        return json_encode(array("flag"=>$flag, "msg"=>$msg));
    }
}
