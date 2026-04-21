<?php

require_once 'connection.php';

class DashboardPT{

    // 1) KPIs topo da dashboard
    function getResumoDashboard($idPt){

        global $conn;
        $row = array(
            "consultas_hoje"      => 0,
            "consultas_semana"    => 0,
            "receita_mes"         => 0,
            "novos_clientes_mes"  => 0
        );

        // CONSULTAS HOJE
        $sql = "SELECT COUNT(*) AS total
                FROM consulta
                WHERE id_prestador = ".$idPt."
                AND DATE(data_hora) = CURDATE()";

        $result = $conn->query($sql);
        if($result && $result->num_rows > 0){
            $aux = $result->fetch_assoc();
            $row['consultas_hoje'] = (int)$aux['total'];
        }

        // CONSULTAS ESTA SEMANA (segunda a domingo)
        $sql = "SELECT COUNT(*) AS total 
                FROM consulta 
                WHERE id_prestador = ".$idPt."
                AND YEARWEEK(data_hora, 1) = YEARWEEK(CURDATE(), 1)";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
                $aux = $result->fetch_assoc();
                $row['consultas_semana'] = (int)$aux['total'];
        }

        // RECEITA DO MÊS (comissões PAGAS ao PT)
        $sql = "SELECT IFNULL(SUM(total_pagar), 0) AS total
                FROM comissao
                WHERE codigo_rh = ".$idPt."
                AND YEAR(data_prevista) = YEAR(CURDATE())
                AND MONTH(data_prevista) = MONTH(CURDATE())
                AND id_estado = 12";   

        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $aux = $result->fetch_assoc();
            $row['receita_mes'] = (float)$aux['total'];
        }

        // NOVOS CLIENTES DO MÊS (clientes diferentes que o PT atendeu no mês)
        $sql = "SELECT COUNT(DISTINCT id_cliente) AS total
                FROM consulta
                WHERE id_prestador = ".$idPt."
                AND YEAR(data_hora) = YEAR(CURDATE())
                AND MONTH(data_hora) = MONTH(CURDATE())";
        $result = $conn->query($sql);
        if($result && $result->num_rows > 0){
            $aux = $result->fetch_assoc();
            $row['novos_clientes_mes'] = (int)$aux['total'];
        }

        return json_encode($row);
    }


    // 2) Gráfico consultas por dia da semana
    function getConsultasSemana($idPt){

        global $conn;

        $labels = array("Seg","Ter","Qua","Qui","Sex","Sáb","Dom");
        $data   = array(0,0,0,0,0,0,0);

        $sql = "SELECT DAYOFWEEK(data_hora) AS dia, COUNT(*) AS total
                FROM consulta
                WHERE id_prestador = ".$idPt."
                AND YEARWEEK(data_hora, 1) = YEARWEEK(CURDATE(), 1)
                GROUP BY DAYOFWEEK(data_hora)";
        $result = $conn->query($sql);

        if($result && $result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $dia = (int)$row['dia']; 
                if ($dia == 1){
                    $pos = 6;          
                } else {
                    $pos = $dia - 2; 
                }
                if ($pos >= 0 && $pos <= 6){
                    $data[$pos] = (int)$row['total'];
                }
            }
        }

        $resp = array(
            "labels" => $labels,
            "data"   => $data,
            "label_periodo" => "Semana atual"
        );

        return json_encode($resp);
    }


    // 3) Gráfico consultas por estado
    function getConsultasPorEstado($idPt){

        global $conn;

        $labels = array();
        $data   = array();

        // consultas por ESTADO no mês atual
        $sql = "SELECT e.descricao AS estado, COUNT(*) AS total
                FROM consulta c
                INNER JOIN estado e ON e.id = c.id_estado
                WHERE c.id_prestador = ".$idPt."
                AND YEAR(c.data_hora) = YEAR(CURDATE())
                AND MONTH(c.data_hora) = MONTH(CURDATE())
                GROUP BY e.descricao";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $labels[] = $row['estado'];          
                $data[]   = (int)$row['total'];
            }
        }

        $out = array(
            'labels' => $labels,
            'data'   => $data
        );

        return json_encode($out);
    }

    // 4) Tabela próximas consultas
    function getProximasConsultas($idPt){

        global $conn;
        $lista = array();

        $sql = "SELECT
                    DATE_FORMAT(c.data_hora, '%Y-%m-%d') AS data,
                    DATE_FORMAT(c.data_hora, '%H:%i')    AS hora,
                    cli.nome_completo                    AS cliente,
                    s.descricao                          AS servico,
                    e.descricao                          AS estado
                FROM consulta c
                INNER JOIN cliente cli ON cli.codigo = c.id_cliente
                LEFT JOIN servico s    ON s.id      = c.id_servico
                LEFT JOIN estado e     ON e.id      = c.id_estado
                WHERE c.id_prestador = ".$idPt."
                AND c.data_hora >= NOW()
                ORDER BY c.data_hora ASC
                LIMIT 10";

        $result = $conn->query($sql);

        if($result && $result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $lista[] = array(
                    "data"    => $row['data'],
                    "hora"    => $row['hora'],
                    "cliente" => $row['cliente'],
                    "servico" => $row['servico'],
                    "estado"  => $row['estado']
                );
            }
        }

        return json_encode($lista);
    }
}

?>
