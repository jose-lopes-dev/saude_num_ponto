<?php
require_once __DIR__ . "/connection.php";
require_once __DIR__ . '/../config/square_config.php';

class PlanoSistema {

    private function getCodigoClienteByUtilizador($id_utilizador) {
        global $conn;

        $stmt = $conn->prepare("SELECT codigo FROM cliente WHERE id_utilizador = ?");
        $stmt->bind_param("i", $id_utilizador);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $res ? (int)$res['codigo'] : 0;
    }

    public function criarVendaPlanoPendente($user_id, $id_servico) {
        global $conn;

        $codigo_cliente = $this->getCodigoClienteByUtilizador($user_id);
        if ($codigo_cliente <= 0) {
            return json_encode(['flag'=>false,'msg'=>'Cliente não encontrado para este utilizador']);
        }
        
        $stmt = $conn->prepare("SELECT id, descricao, preco FROM servico WHERE id = ?");
        $stmt->bind_param("i", $id_servico);
        $stmt->execute();
        $res = $stmt->get_result();
        $serv = $res->fetch_assoc();
        $stmt->close();

        if (!$serv) {
            return json_encode(['flag' => false, 'msg' => 'Serviço não existe']);
        }

        $valor = (float)$serv['preco'];

        $id_estado_pendente = 13;
        $metodo = ($valor <= 0) ? 'gratis' : 'cartao';

        $stmt = $conn->prepare("
            INSERT INTO venda (id_cliente, id_servico, valor, data_venda, metodo_pagamento, id_estado, fatura)
            VALUES (?, ?, ?, CURDATE(), ?, ?, '')
        ");
        $stmt->bind_param("iidsi", $codigo_cliente, $id_servico, $valor, $metodo, $id_estado_pendente);
        $ok = $stmt->execute();
        $id_venda = $conn->insert_id;
        $stmt->close();

        if (!$ok) {
            return json_encode(['flag' => false, 'msg' => 'Erro ao criar venda']);
        }

        if ($valor <= 0) {
            return $this->ativarPlanoSemPagamento($codigo_cliente, $id_venda, $id_servico);
        }

        return json_encode([
            'flag' => true,
            'msg' => 'Venda criada (pendente)',
            'id_venda' => $id_venda,
            'plano' => [
                'id_servico' => (int)$serv['id'],
                'descricao' => $serv['descricao'],
                'preco' => $valor
            ]
        ]);
    }

    private function ativarPlanoSemPagamento($codigo_cliente, $id_venda, $id_servico) {
        global $conn;

        $id_estado_pago = 12;
        $stmt = $conn->prepare("UPDATE venda SET id_estado = ? WHERE id = ? AND id_cliente = ?");
        $stmt->bind_param("iii", $id_estado_pago, $id_venda, $codigo_cliente);
        $stmt->execute();
        $stmt->close();

        $this->ativarClientePlano($codigo_cliente, $id_servico);

        return json_encode(['flag' => true, 'msg' => 'Plano ativado (FREE)']);
    }

    public function confirmarPagamentoPlano($userId, $idVenda, $squarePaymentId) {
        global $conn;

        $codigo_cliente = $this->getCodigoClienteByUtilizador($userId);
        if ($codigo_cliente <= 0) {
            return json_encode(['flag'=>false,'msg'=>'Cliente não encontrado para este utilizador']);
        }

        $chk = $conn->prepare("SELECT id FROM pagamento_square WHERE square_payment_id=?");
        $chk->bind_param("s", $squarePaymentId);
        $chk->execute();
        if ($chk->get_result()->num_rows > 0) {
            $chk->close();
            return json_encode(['flag'=>true,'msg'=>'Pagamento já processado']);
        }
        $chk->close();

        $stmt = $conn->prepare("SELECT id_servico, valor FROM venda WHERE id=? AND id_cliente=? LIMIT 1");
        $stmt->bind_param("ii", $idVenda, $codigo_cliente);
        $stmt->execute();
        $venda = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$venda) {
            return json_encode(['flag'=>false,'msg'=>'Venda inválida']);
        }

        $id_servico = (int)$venda['id_servico'];
        $valor      = (float)$venda['valor'];

        $status   = "COMPLETED";
        $currency = "USD"; 
        $ins = $conn->prepare("INSERT INTO pagamento_square (id_venda, square_payment_id, square_status, amount, currency)
                            VALUES (?, ?, ?, ?, ?)");
        $ins->bind_param("issds", $idVenda, $squarePaymentId, $status, $valor, $currency);
        $ins->execute();
        $ins->close();

        $upd = $conn->prepare("UPDATE venda SET id_estado=12 WHERE id=? AND id_cliente=?");
        $upd->bind_param("ii", $idVenda, $codigo_cliente);
        $upd->execute();
        $upd->close();

        $this->ativarClientePlano($codigo_cliente, $id_servico);

        return json_encode(['flag'=>true,'msg'=>'Plano ativado com sucesso']);
    }

    private function ativarClientePlano($codigo_cliente, $id_servico, $ren_auto = 0){
        global $conn;

        $stmt = $conn->prepare("UPDATE cliente_plano SET ativo = 0 WHERE codigo_cliente = ? AND ativo = 1");
        $stmt->bind_param("i", $codigo_cliente);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("
            INSERT INTO cliente_plano (codigo_cliente, id_servico, data_inicio, data_fim, ativo, renovacao_automatica)
            VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH), 1, ?)
        ");
        $stmt->bind_param("iii", $codigo_cliente, $id_servico, $ren_auto);
        $stmt->execute();
        $stmt->close();
    }

    private function squareGetPayment($payment_id) {
        $url = squareBaseUrl() . "/v2/payments/" . urlencode($payment_id);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . SQUARE_ACCESS_TOKEN,
            "Content-Type: application/json"
        ]);

        $resp = curl_exec($ch);
        $err  = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($resp === false) {
            return ['ok' => false, 'msg' => $err ?: 'Falha na ligação'];
        }

        $data = json_decode($resp, true);

        if ($code < 200 || $code >= 300) {
            $msg = 'HTTP ' . $code;
            if (isset($data['errors'][0]['detail'])) $msg .= ' - ' . $data['errors'][0]['detail'];
            return ['ok' => false, 'msg' => $msg, 'raw' => $resp];
        }

        return ['ok' => true, 'data' => $data];
    }

    public function criarPagamentoSquare($nonce, $amountEur) {

        if ($nonce === '' || $amountEur <= 0) {
            return json_encode(['flag'=>false,'msg'=>'Dados inválidos']);
        }

        $EUR_TO_USD = 1.10;

        $amountUsd = (float)$amountEur * $EUR_TO_USD;
        $amountCents = (int) round($amountUsd * 100);

        $body = [
            "source_id" => $nonce,
            "amount_money" => [
                "amount" => $amountCents,
                "currency" => "USD"
            ],
            "idempotency_key" => uniqid("plano_", true)
        ];

        $resp = $this->squarePost("/v2/payments", $body);

        if (!$resp['ok']) {
            return json_encode(['flag'=>false,'msg'=>$resp['msg']]);
        }

        $payment = $resp['data']['payment'] ?? null;

        if (!$payment || ($payment['status'] ?? '') !== 'COMPLETED') {
            return json_encode(['flag'=>false,'msg'=>'Pagamento não concluído']);
        }

        return json_encode([
            'flag' => true,
            'square_payment_id' => $payment['id'],
            'charged_usd' => $amountUsd
        ]);
    }

    private function squarePost($path, $payload) {
        $url = squareBaseUrl() . $path;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . SQUARE_ACCESS_TOKEN,
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $resp = curl_exec($ch);
        $err  = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($resp === false) return ['ok'=>false,'msg'=>$err];

        $data = json_decode($resp, true);

        if ($code < 200 || $code >= 300) {
            return ['ok'=>false,'msg'=>$data['errors'][0]['detail'] ?? 'Erro Square'];
        }

        return ['ok'=>true,'data'=>$data];
    }

    public function getPlanoAtual($user_id){
        global $conn;

        $codigo_cliente = $this->getCodigoClienteByUtilizador($user_id);
        if($codigo_cliente <= 0) return json_encode(['flag'=>false,'msg'=>'Cliente não encontrado']);

        $stmt = $conn->prepare("
            SELECT cp.id_servico, s.descricao, cp.data_inicio, cp.data_fim, cp.renovacao_automatica
            FROM cliente_plano cp
            JOIN servico s ON s.id = cp.id_servico
            WHERE cp.codigo_cliente = ? AND cp.ativo = 1
            LIMIT 1
        ");
        $stmt->bind_param("i", $codigo_cliente);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        $limNutri = (int)$this->getVantagemAtiva($codigo_cliente, "nutri_gratis_mes");
        $useNutri = (int)$this->contarGratisMes($codigo_cliente, 7);

        $limPT = (int)$this->getVantagemAtiva($codigo_cliente, "pt_gratis_mes");
        $usePT = (int)$this->contarGratisMes($codigo_cliente, 8);

        $restNutri = max(0, $limNutri - $useNutri);
        $restPT = max(0, $limPT - $usePT);
        $stmt->close();

        return json_encode(['flag' => true, 'plano' => $row, 'restantes' => ['nutri' => $restNutri,'pt' => $restPT]]);
    }

    public function getVantagemAtiva($codigo_cliente, $chave){
        global $conn;

        $stmt = $conn->prepare("
            SELECT pv.valor
            FROM cliente_plano cp
            JOIN plano_vantagem pv ON pv.id_servico = cp.id_servico
            WHERE cp.codigo_cliente = ? AND cp.ativo = 1 AND pv.chave = ?
            LIMIT 1
        ");
        $stmt->bind_param("is", $codigo_cliente, $chave);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ? (int)$row['valor'] : 0;
    }

    public function setRenovacaoAutomatica($user_id, $val){
        global $conn;

        $codigo_cliente = $this->getCodigoClienteByUtilizador($user_id);
        if($codigo_cliente <= 0) return json_encode(['flag'=>false,'msg'=>'Cliente não encontrado']);

        $val = ($val ? 1 : 0);
        $stmt = $conn->prepare("UPDATE cliente_plano SET renovacao_automatica=? WHERE codigo_cliente=? AND ativo=1");
        $stmt->bind_param("ii", $val, $codigo_cliente);
        $stmt->execute();
        $stmt->close();

        return json_encode(['flag'=>true,'msg'=>'Renovação atualizada']);
    }

    public function contarGratisMes($codigo_cliente, $id_servico){
        global $conn;

        $stmt = $conn->prepare("
            SELECT COUNT(*) AS total
            FROM consulta
            WHERE id_cliente = ?
            AND id_servico = ?
            AND gratuita = 1
            AND id_estado <> 4
            AND MONTH(data_hora) = MONTH(CURDATE())
            AND YEAR(data_hora) = YEAR(CURDATE())
        ");
        $stmt->bind_param("ii", $codigo_cliente, $id_servico);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ? (int)$row['total'] : 0;
    }
    
    public function getPrecoServico($id_servico){
        global $conn;

        $stmt = $conn->prepare("SELECT preco FROM servico WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id_servico);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $row ? (float)$row['preco'] : 0;
    }

    public function limiteGratisMes($codigo_cliente, $chave){
        return (int)$this->getVantagemAtiva($codigo_cliente, $chave);
    }
}
