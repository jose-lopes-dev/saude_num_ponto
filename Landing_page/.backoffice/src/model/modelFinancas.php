<?php
require_once 'connection.php';

class ModelFinancas {

    function getEmprestimos(){
        global $conn;
        $arr = [];
        $sql = "SELECT id, mes, valor_prestacao, juros, amortizacao, saldo_devedor, pago, DATE_FORMAT(data_prevista,'%Y-%m-%d') as data_prevista 
                FROM emprestimo 
                ORDER BY data_prevista ASC";
        $res = $conn->query($sql);
        if($res){
            while($row = $res->fetch_assoc()){
                $row['valor_prestacao'] = (float)$row['valor_prestacao'];
                $row['juros'] = (float)$row['juros'];
                $row['amortizacao'] = (float)$row['amortizacao'];
                $row['saldo_devedor'] = (float)$row['saldo_devedor'];
                $row['pago'] = (int)$row['pago'];
                $arr[] = $row;
            }
        }
        return $arr;
    }

    function getKPIs(){
        global $conn;
        $k = [
            'valor_inicial' => 0,
            'total_pago' => 0,
            'proxima_valor' => 0,
            'proxima_data' => null,
            'saldo_devedor' => 0,
            'total_juros' => 0
        ];

        // KPI: Valor inicial = soma da amortização
        $res = $conn->query("SELECT SUM(amortizacao) AS soma_amort FROM emprestimo");
        if($res){
            $r = $res->fetch_assoc();
           $k['valor_inicial'] = number_format($r['soma_amort'], 0, '', '');          
        }

        // KPI: Total juros pagos = soma apenas dos juros das prestações pagas
        $res = $conn->query("SELECT SUM(juros) as total_juros_pagos FROM emprestimo WHERE pago=1");
        if ($res) {
        $k['total_juros_pagos'] = (float)($res->fetch_assoc()['total_juros_pagos'] ?? 0);
        }

        // KPI: Total pago = soma amortizações
        $res = $conn->query("SELECT SUM(amortizacao) as total_pago FROM emprestimo WHERE pago=1");
        if($res){
            $k['total_pago'] = (float)$res->fetch_assoc()['total_pago'];
        }

        // KPI: Próxima prestação (primeira com pago=0)
        $res = $conn->query("SELECT valor_prestacao, DATE_FORMAT(data_prevista,'%Y-%m-%d') as data_prevista FROM emprestimo WHERE pago=0 ORDER BY data_prevista ASC LIMIT 1");
        if($res && $res->num_rows > 0){
            $row = $res->fetch_assoc();
            $k['proxima_valor'] = (float)$row['valor_prestacao'];
            $k['proxima_data'] = $row['data_prevista'];
        }

        // KPI: Capital em Divida = saldo_devedor do último não pago
        $res = $conn->query("SELECT saldo_devedor FROM emprestimo WHERE pago=0 ORDER BY data_prevista ASC LIMIT 1");
        if($res && $res->num_rows > 0){
            $k['saldo_devedor'] = (float)$res->fetch_assoc()['saldo_devedor'];
        }

        return $k;
    }

    private function getSaldoInicial(){
        global $conn;
        $res = $conn->query("SELECT saldo_devedor FROM emprestimo ORDER BY data_prevista ASC LIMIT 1");
        if($res && $res->num_rows > 0){
            return (float)$res->fetch_assoc()['saldo_devedor'];
        }
        return 0;
    }

    function marcarPago($id){
        global $conn;
        $id = intval($id);
        if($id <= 0) return ['flag' => false, 'msg' => 'ID inválido'];
        $sql = "UPDATE emprestimo SET pago=1, data_pagamento=CURDATE() WHERE id=$id";
        if($conn->query($sql)){
            return ['flag'=>true, 'msg'=>'Prestação marcada como paga!'];
        }
        return ['flag'=>false, 'msg'=>'Erro BD: '.$conn->error];
    }
}
