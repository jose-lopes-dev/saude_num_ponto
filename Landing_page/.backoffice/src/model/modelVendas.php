<?php
require_once 'connection.php';

class Vendas
{
    // IDs fixos dos serviços
    private const PACK_MEDIO = 2;
    private const PACK_PRO   = 3;
    private const PACK_DUO   = 4;
    private const PACK_LAR   = 5;

    private const INDIV = [6,7,8];            
    private const GRUPO = [9,10,11,12,13];    

    public function packsTrimestre(string $endYm) {
        global $conn;

        $endYm = substr($endYm, 0, 7);
        $dtEnd = DateTime::createFromFormat('Y-m', $endYm) ?: new DateTime();

        $m1 = (clone $dtEnd)->modify('-2 months')->format('Y-m');
        $m2 = (clone $dtEnd)->modify('-1 months')->format('Y-m');
        $m3 = $dtEnd->format('Y-m');
        $meses = [$m1, $m2, $m3];

        $ini = "$m1-01";
        $fim = (clone $dtEnd)->modify('first day of next month')->format('Y-m-01');

        $sql = "
        SELECT DATE_FORMAT(data_venda,'%Y-%m') AS ym,
               SUM(CASE WHEN id_servico = 2 THEN 1 ELSE 0 END) AS medio,
               SUM(CASE WHEN id_servico = 3 THEN 1 ELSE 0 END) AS pro,
               SUM(CASE WHEN id_servico = 4 THEN 1 ELSE 0 END) AS duo,
               SUM(CASE WHEN id_servico = 5 THEN 1 ELSE 0 END) AS lar,
               SUM(CASE WHEN id_servico = 2 THEN valor ELSE 0 END) AS medio_v,
               SUM(CASE WHEN id_servico = 3 THEN valor ELSE 0 END) AS pro_v,
               SUM(CASE WHEN id_servico = 4 THEN valor ELSE 0 END) AS duo_v,
               SUM(CASE WHEN id_servico = 5 THEN valor ELSE 0 END) AS lar_v
        FROM venda
        WHERE data_venda >= '{$conn->real_escape_string($ini)}'
          AND data_venda <  '{$conn->real_escape_string($fim)}'
        GROUP BY ym";

        $res = $conn->query($sql);
        $map = [];

        if ($res) {
            while ($r = $res->fetch_assoc()) {
                $map[$r['ym']] = [
                    'medio'   => (int)($r['medio'] ?? 0),
                    'pro'     => (int)($r['pro'] ?? 0),
                    'duo'     => (int)($r['duo'] ?? 0),
                    'lar'     => (int)($r['lar'] ?? 0),
                    'medio_v' => (float)($r['medio_v'] ?? 0),
                    'pro_v'   => (float)($r['pro_v'] ?? 0),
                    'duo_v'   => (float)($r['duo_v'] ?? 0),
                    'lar_v'   => (float)($r['lar_v'] ?? 0),
                ];
            }
        }

        $medio=[]; $pro=[]; $duo=[]; $lar=[];
        $medioV=[]; $proV=[]; $duoV=[]; $larV=[];
        foreach ($meses as $ym) {
            $row = $map[$ym] ?? ['medio'=>0,'pro'=>0,'duo'=>0,'lar'=>0,'medio_v'=>0,'pro_v'=>0,'duo_v'=>0,'lar_v'=>0];
            $medio[]  = $row['medio'];   $pro[]  = $row['pro'];   $duo[]  = $row['duo'];  $lar[]  = $row['lar'];
            $medioV[] = $row['medio_v']; $proV[] = $row['pro_v']; $duoV[] = $row['duo_v'];$larV[] = $row['lar_v'];
        }

        return json_encode([
            'labels'      => $meses,
            'series'      => ['medio'=>$medio,'pro'=>$pro,'duo'=>$duo,'lar'=>$lar],
            'seriesValor' => ['medio'=>$medioV,'pro'=>$proV,'duo'=>$duoV,'lar'=>$larV],
        ]);
    }

    public function indivGrupoPorMes(string $ym) {
        global $conn;

        $ym  = substr($ym,0,7);
        $ini = $conn->real_escape_string($ym.'-01');
        $dt  = DateTime::createFromFormat('Y-m',$ym) ?: new DateTime();
        $fim = $conn->real_escape_string($dt->modify('first day of next month')->format('Y-m-01'));

        $idsIndStr = implode(',', self::INDIV);
        $idsGrpStr = implode(',', self::GRUPO);

        $sqlInd = "
            SELECT s.descricao AS servico, COUNT(*) AS qtd, COALESCE(SUM(valor),0) AS eur
            FROM venda
            JOIN servico s ON s.id = id_servico
            WHERE data_venda >= '{$ini}' AND data_venda < '{$fim}'
            AND id_servico IN ($idsIndStr)
            GROUP BY s.descricao ORDER BY s.descricao";

        $sqlGrp = "
            SELECT s.descricao AS servico, COUNT(*) AS qtd, COALESCE(SUM(valor),0) AS eur
            FROM venda
            JOIN servico s ON s.id = id_servico
            WHERE data_venda >= '{$ini}' AND data_venda < '{$fim}'
            AND id_servico IN ($idsGrpStr)
            GROUP BY s.descricao ORDER BY s.descricao";

        $labelsInd=[]; $valsInd=[]; $valsIndEur=[]; $totInd=0; $totIndEur=0.0;
        if ($r=$conn->query($sqlInd)) while($x=$r->fetch_assoc()){
            $labelsInd[]=$x['servico'];
            $valsInd[]=(int)$x['qtd'];
            $valsIndEur[]=(float)$x['eur'];
            $totInd+=(int)$x['qtd'];
            $totIndEur+=(float)$x['eur'];
        }

        $labelsGrp=[]; $valsGrp=[]; $valsGrpEur=[]; $totGrp=0; $totGrpEur=0.0;
        if ($r=$conn->query($sqlGrp)) while($x=$r->fetch_assoc()){
            $labelsGrp[]=$x['servico'];
            $valsGrp[]=(int)$x['qtd'];
            $valsGrpEur[]=(float)$x['eur'];
            $totGrp+=(int)$x['qtd'];
            $totGrpEur+=(float)$x['eur'];
        }

        return json_encode([
            'mes'=>$ym,
            'individuais'=>[
                'labels'=>$labelsInd,
                'values'=>$valsInd,
                'total'=>$totInd,
                'values_eur'=>$valsIndEur,
                'total_eur'=>$totIndEur
            ],
            'grupo'=>[
                'labels'=>$labelsGrp,
                'values'=>$valsGrp,
                'total'=>$totGrp,
                'values_eur'=>$valsGrpEur,
                'total_eur'=>$totGrpEur
            ]
        ]);
    }

    public function consultasResumoMes(string $ym, string $modo = 'tri') {
        global $conn;
        $base = DateTime::createFromFormat('Y-m', substr($ym,0,7)) ?: new DateTime();

        $labels = [];
        if ($modo === 'tri') {
            $q = (int)ceil(((int)$base->format('n')) / 3);
            $mesInicio = ($q - 1) * 3 + 1;
            $start = (clone $base)->setDate((int)$base->format('Y'), $mesInicio, 1);
            for ($i=0; $i<3; $i++) $labels[] = (clone $start)->modify("+{$i} month")->format('Y-m');
            $ini = $labels[0] . '-01';
            $fim = (clone $start)->modify('+3 months')->format('Y-m-01');
        }

        $ini = $conn->real_escape_string($ini);
        $fim = $conn->real_escape_string($fim);

        $idsIndStr = implode(',', self::INDIV);
        $idsGrpStr = implode(',', self::GRUPO);

        $sql = "
        SELECT DATE_FORMAT(data_venda,'%Y-%m') AS ym,
               SUM(CASE WHEN id_servico IN ($idsIndStr) THEN 1 ELSE 0 END) AS ind,
               SUM(CASE WHEN id_servico IN ($idsGrpStr) THEN 1 ELSE 0 END) AS grp
        FROM venda
        WHERE data_venda >= '$ini' AND data_venda < '$fim'
        GROUP BY ym";

        $map = [];
        if ($res = $conn->query($sql))
            while ($r = $res->fetch_assoc())
                $map[$r['ym']] = ['ind'=>(int)$r['ind'], 'grp'=>(int)$r['grp']];

        $rows = [];
        foreach ($labels as $m){
            $ind = $map[$m]['ind'] ?? 0;
            $grp = $map[$m]['grp'] ?? 0;
            $rows[] = ['mes'=>$m, 'individuais'=>$ind, 'grupo'=>$grp, 'total'=>$ind+$grp];
        }

        return json_encode(['modo'=>$modo, 'rows'=>$rows]);
    }

    public function ultimasVendas(int $lim, int $off, string $q): string {
        global $conn;

        $sql = "
        SELECT v.id AS id_venda, c.nome AS cliente, s.descricao AS servico,
               DATE_FORMAT(v.data_venda, '%d/%m/%Y') AS data, v.valor, 'Pago' AS estado, v.fatura
        FROM venda v
        LEFT JOIN cliente c ON c.codigo = v.codigo_cliente
        LEFT JOIN servico s ON s.id = v.id_servico
        WHERE (? = '' OR c.nome LIKE CONCAT('%', ?, '%') OR s.descricao LIKE CONCAT('%', ?, '%'))
        ORDER BY v.data_venda DESC
        LIMIT ? OFFSET ?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) return json_encode(['rows'=>[], 'err'=>$conn->error]);

        $stmt->bind_param("sssii", $q, $q, $q, $lim, $off);
        $stmt->execute();

        $rows = [];
        $dirWeb = 'src/uploads/faturas';
        $res = $stmt->get_result();

        while ($r = $res->fetch_assoc()) {
            $tem = !empty($r['fatura']);
            $rows[] = [
                'id_venda'=>(int)$r['id_venda'],
                'cliente'=>$r['cliente'],
                'servico'=>$r['servico'],
                'data'=>$r['data'],
                'valor'=>(float)$r['valor'],
                'estado'=>$r['estado'],
                'has_fatura'=>$tem,
                'fatura_url'=>$tem ? ($dirWeb.'/'.$r['fatura']) : null,
            ];
        }
        $stmt->close();
        return json_encode(['rows'=>$rows], JSON_UNESCAPED_UNICODE);
    }

    public function apagarVenda(int $id): string {
        global $conn;
        $ok = $conn->query("DELETE FROM venda WHERE id=$id");
        return json_encode(['ok'=>(bool)$ok, 'msg'=>$ok ? '' : 'Falha ao remover']);
    }

    public function guardarFatura(int $idVenda, array $ficheiro): string {
        global $conn;

        $dirFs  = __DIR__ . '/../uploads/faturas';
        $dirWeb = 'src/uploads/faturas';
        if (!is_dir($dirFs)) @mkdir($dirFs, 0775, true);

        if ($idVenda <= 0 || empty($ficheiro['tmp_name']))
            return json_encode(['ok'=>false,'msg'=>'Pedido inválido']);

        $ext = strtolower(pathinfo($ficheiro['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['pdf','jpg','jpeg','png']))
            return json_encode(['ok'=>false,'msg'=>'Formato não suportado']);

        $fname = 'venda_' . $idVenda . '_' . date('YmdHis') . '.' . $ext;
        $dest  = $dirFs . '/' . $fname;

        if (!move_uploaded_file($ficheiro['tmp_name'], $dest))
            return json_encode(['ok'=>false,'msg'=>'Falha ao guardar ficheiro']);

        $stmt = $conn->prepare("UPDATE venda SET fatura=? WHERE id=?");
        if (!$stmt) { @unlink($dest); return json_encode(['ok'=>false,'msg'=>$conn->error]); }

        $stmt->bind_param("si", $fname, $idVenda);
        $ok = $stmt->execute();
        $stmt->close();

        if (!$ok) { @unlink($dest); return json_encode(['ok'=>false,'msg'=>'Falha ao atualizar BD']); }

        return json_encode(['ok'=>true, 'msg'=>'Fatura enviada/atualizada.', 'url_web'=>$dirWeb.'/'.$fname]);
    }
}
?>
