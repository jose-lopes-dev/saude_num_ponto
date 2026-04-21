<?php
require_once 'connection.php';

class ClientePt {

    // STATS (cards)
    function getStatsClientesPt($idPt){
        global $conn;

        $flag = true;
        $msg = "";
        $dados = array(
            "total_clientes" => 0,
            "consultas_semana" => 0,
            "consultas_futuras" => 0,
            "taxa_conclusao" => 0
        );

        $sqlTotal = "SELECT COUNT(DISTINCT co.id_cliente) AS total
                    FROM consulta co
                    WHERE co.id_prestador = ?";
        $stmt = $conn->prepare($sqlTotal);
        $stmt->bind_param("i", $idPt);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if($res) $dados["total_clientes"] = intval($res["total"]);

        $sqlSemana = "SELECT COUNT(*) AS total
                    FROM consulta co
                    WHERE co.id_prestador = ?
                    AND YEARWEEK(DATE(co.data_hora), 1) = YEARWEEK(CURDATE(), 1)";
        $stmt = $conn->prepare($sqlSemana);
        $stmt->bind_param("i", $idPt);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if($res) $dados["consultas_semana"] = intval($res["total"]);

        $sqlFuturas = "SELECT COUNT(*) AS total
                    FROM consulta co
                    WHERE co.id_prestador = ?
                    AND DATE(co.data_hora) >= CURDATE()";
        $stmt = $conn->prepare($sqlFuturas);
        $stmt->bind_param("i", $idPt);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if($res) $dados["consultas_futuras"] = intval($res["total"]);

        $sqlTaxa = "SELECT
                        SUM(CASE WHEN co.id_estado = 16 THEN 1 ELSE 0 END) AS concluidas,
                        COUNT(*) AS total
                    FROM consulta co
                    WHERE co.id_prestador = ?
                    AND DATE(co.data_hora) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        $stmt = $conn->prepare($sqlTaxa);
        $stmt->bind_param("i", $idPt);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if($res && intval($res["total"]) > 0){
            $dados["taxa_conclusao"] = round((intval($res["concluidas"]) / intval($res["total"])) * 100);
        }

        return json_encode(array("flag"=>$flag, "msg"=>$msg, "dados"=>$dados));
    }

    // LISTA CLIENTES (datatable)
    function getListaClientesPt($idPt){
        global $conn;

        $flag = true;
        $msg  = "";
        $dados = array();

        $sql = "SELECT 
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
                    c.codigo, c.nome_completo, c.contacto, u.email, o.descricao, e.descricao, c.id_estado
                ORDER BY ultima_consulta DESC";

        $stmt = $conn->prepare($sql);
        if(!$stmt){
            $flag = false;
            $msg = "Erro prepare: ".$conn->error;
            return json_encode(array("flag"=>$flag,"msg"=>$msg,"dados"=>$dados));
        }

        $stmt->bind_param("i", $idPt);

        if($stmt->execute()){
            $res = $stmt->get_result();
            while($row = $res->fetch_assoc()){
                $dados[] = $row;
            }
        } else {
            $flag = false;
            $msg = "Erro execute: ".$stmt->error;
        }

        $stmt->close();

        return json_encode(array(
            "flag"  => $flag,
            "msg"   => $msg,
            "dados" => $dados
        ));
    }

    // GET 1 CLIENTE (para modal)
    function getDadosClientePt($idCliente){
        global $conn;

        $flag = true;
        $msg = "";
        $dados = null;

        $sql = "SELECT
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
                WHERE c.codigo = ?";

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

    // UPDATE
    function guardaEditClientePt($idCliente, $nome, $tel, $email, $dataNasc, $estado, $objetivo, $notas){
        global $conn;

        $flag = true;
        $msg = "";

        // ir buscar id_utilizador
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

        // update cliente (contacto, data_nascimento, etc)
        $sqlC = "UPDATE cliente
                SET nome_completo = ?,
                    contacto = ?,
                    data_nascimento = ?,
                    id_estado = ?,
                    id_objetivo = ?
                WHERE codigo = ?";
        $stmt = $conn->prepare($sqlC);
        $stmt->bind_param("sssiii", $nome, $tel, $dataNasc, $estado, $objetivo, $idCliente);

        if($stmt->execute()){
            $msg = "Editado com sucesso";
        } else {
            $flag = false;
            $msg = "Erro ao editar cliente";
        }
        $stmt->close();

        // update email no utilizador
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

    function alteraEstadoClientePt($idCliente, $idEstado){
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
