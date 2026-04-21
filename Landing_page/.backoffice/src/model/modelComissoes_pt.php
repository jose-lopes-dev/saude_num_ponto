<?php
require_once 'connection.php';

class Comissoes {

    function getListaComissoes($idPt, $estado){
        global $conn;
        $msg = "";

        $whereEstado = "";
        if($estado != ""){
            $whereEstado = " AND cc.id_estado = ".$estado." ";
        }

        $sql = "SELECT
                    cc.id,
                    cc.percentagem,
                    cc.valor_pago,
                    cc.valor_comissao,
                    e.descricao as estado,
                    c.data_hora,
                    cl.nome_completo as cliente
                FROM comissao_consulta cc, consulta c, cliente cl, estado e
                WHERE cc.id_consulta = c.id
                AND c.id_cliente = cl.codigo
                AND cc.id_estado = e.id
                AND cc.codigo_rh = ".$idPt."
                ".$whereEstado."
                ORDER BY c.data_hora DESC";

        $result = $conn->query($sql);

        if($result && $result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $data = date("d/m/Y H:i", strtotime($row['data_hora']));
                $badge = ($row['estado'] == "Pago") ? "success" : "warning";

                $acoes = "-";
                if($row['estado'] != "Pago"){
                    $acoes = "<button class='btn btn-sm btn-success' onclick='marcarPago(".$row['id'].")'>Marcar Pago</button>";
                }

                $msg .= "<tr>";
                $msg .= "<td>".$row['cliente']." | ".$data."</td>";
                $msg .= "<td>".number_format($row['valor_pago'],2,",",".")."€</td>";
                $msg .= "<td>".$row['percentagem']."%</td>";
                $msg .= "<td>".number_format($row['valor_comissao'],2,",",".")."€</td>";
                $msg .= "<td><span class='badge bg-".$badge."'>".$row['estado']."</span></td>";
                $msg .= "<td class='text-end'>".$acoes."</td>";
                $msg .= "</tr>";
                }
            } if($msg == "") {
                $msg = "
                    <tr>
                        <td class='text-muted'>Sem registos</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                ";
            }
        return $msg;
    }

    function marcarPago($id){
        global $conn;
        $flag = true;
        $msg = "";

        $sql = "UPDATE comissao_consulta SET id_estado = 12, data_pagamento = NOW() WHERE id = ".$id;

        if ($conn->query($sql) === TRUE) {
            $msg = "Comissão marcada como paga.";
        } else {
            $flag = false;
            $msg = "Erro: " . $conn->error;
        }

        $resp = json_encode(array("flag"=>$flag, "msg"=>$msg));
        $conn->close();
        return($resp);
    }

    function getResumoComissoes($idPt, $estado){
        global $conn;

        $whereEstado = "";
        if($estado != ""){
            $whereEstado = " AND cc.id_estado = ".$estado;
        }

        $sql = "SELECT
                    IFNULL(SUM(cc.valor_pago),0) AS totalBase,
                    IFNULL(SUM(cc.valor_comissao),0) AS totalComissao,
                    IFNULL(SUM(CASE WHEN cc.id_estado = 13 THEN cc.valor_comissao ELSE 0 END),0) AS totalPorPagar
                FROM comissao_consulta cc
                WHERE cc.codigo_rh = ".$idPt."
                ".$whereEstado;

        $result = $conn->query($sql);

        $totalBase = 0;
        $totalComissao = 0;
        $totalPorPagar = 0;

        if($result && $row = $result->fetch_assoc()){
            $totalBase = number_format($row['totalBase'], 2, ",", ".");
            $totalComissao = number_format($row['totalComissao'], 2, ",", ".");
            $totalPorPagar = number_format($row['totalPorPagar'], 2, ",", ".");
        }

        $resp = json_encode(array(
            "totalBase" => $totalBase,
            "totalComissao" => $totalComissao,
            "totalPorPagar" => $totalPorPagar
        ));

        $conn->close();
        return($resp);
    }
    function syncComissoes($idPt){
        global $conn;

        $idPt = intval($idPt);
        $percent = 70;

        $sql = "INSERT INTO comissao_consulta
                    (id_consulta, codigo_rh, percentagem, valor_pago, valor_comissao, id_estado)
                SELECT
                    c.id,
                    c.id_prestador,
                    ".$percent.",
                    c.preco,
                    ROUND(c.preco * (".$percent."/100), 2),
                    13
                FROM consulta c
                WHERE c.id_prestador = ".$idPt."
                AND c.id_estado IN (16) 
                AND NOT EXISTS (
                    SELECT 1 FROM comissao_consulta cc WHERE cc.id_consulta = c.id
                )";

        if($conn->query($sql) === TRUE){
            return json_encode(array("flag"=>true, "msg"=>"Sync OK"));
        }else{
            return json_encode(array("flag"=>false, "msg"=>"Erro Sync: ".$conn->error));
        }
    }
}

?>
