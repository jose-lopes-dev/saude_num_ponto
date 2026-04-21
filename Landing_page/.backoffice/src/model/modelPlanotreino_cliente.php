<?php
require_once "connection.php";

class PlanoTreinoCliente {

    private function j($arr){
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    private function bindParams($stmt, $types, $params){
        if($types === "" || empty($params)) return true;

        $refs = [];
        $refs[] = $types;
        for($i=0; $i<count($params); $i++){
            $refs[] = &$params[$i];
        }
        return call_user_func_array([$stmt, 'bind_param'], $refs);
    }

    private function getCodigoCliente($id_utilizador) {
        global $conn;

        $stmt = $conn->prepare("SELECT codigo FROM cliente WHERE id_utilizador = ? LIMIT 1");
        $stmt->bind_param("i", $id_utilizador);
        $stmt->execute();
        $res = $stmt->get_result();

        if($res && $res->num_rows > 0){
            $r = $res->fetch_assoc();
            return (int)$r['codigo'];
        }
        return 0;
    }

    public function listarExercicios($q, $grupo, $equip){
        global $conn;

        $q = trim((string)$q);
        $grupo = trim((string)$grupo);
        $equip = trim((string)$equip);

        $sql = "SELECT id_exercicio, nome FROM exercicio WHERE 1=1";
        $types = "";
        $vals  = [];

        if($grupo !== ""){
            $sql .= " AND grupo = ?";
            $types .= "s";
            $vals[] = $grupo;
        }
        if($equip !== ""){
            $sql .= " AND equipamento = ?";
            $types .= "s";
            $vals[] = $equip;
        }
        if($q !== ""){
            $sql .= " AND nome LIKE ?";
            $types .= "s";
            $vals[] = "%".$q."%";
        }

        $sql .= " AND (ativo = 1 OR ativo IS NULL)";

        $sql .= " ORDER BY nome LIMIT 50";

        $stmt = $conn->prepare($sql);
        if(!$stmt){
            return $this->j(['flag'=>false,'msg'=>'Erro SQL']);
        }

        $this->bindParams($stmt, $types, $vals);
        $stmt->execute();
        $res = $stmt->get_result();

        $items = [];
        while($r = $res->fetch_assoc()){
            $items[] = [
                'id_exercicio' => (int)$r['id_exercicio'],
                'nome' => $r['nome']
            ];
        }

        return $this->j(['flag'=>true,'items'=>$items]);
    }

    public function criarPlano($id_utilizador, $titulo){
        global $conn;

        $id_utilizador = (int)$id_utilizador;
        $titulo = trim((string)$titulo);

        if($id_utilizador <= 0){
            return $this->j(['flag'=>false,'msg'=>'Sessão inválida']);
        }
        if(strlen($titulo) < 3){
            return $this->j(['flag'=>false,'msg'=>'Título inválido']);
        }

        $codigo_cliente = $this->getCodigoCliente($id_utilizador);
        if($codigo_cliente <= 0){
            return $this->j(['flag'=>false,'msg'=>'Cliente não encontrado']);
        }

        $stmt = $conn->prepare("
            INSERT INTO plano_pt (codigo_rh, codigo_cliente, titulo, publicado, criado_por)
            VALUES (NULL, ?, ?, 0, 'CLIENTE')
        ");
        if(!$stmt){
            return $this->j(['flag'=>false,'msg'=>'Erro SQL']);
        }

        $stmt->bind_param("is", $codigo_cliente, $titulo);

        if(!$stmt->execute()){
            return $this->j(['flag'=>false,'msg'=>'Erro ao criar plano']);
        }

        return $this->j(['flag'=>true,'id'=>$stmt->insert_id,'msg'=>'Plano criado']);
    }

    public function guardarDias($id_utilizador, $plano_id, $dias_json){
        global $conn;

        $id_utilizador = (int)$id_utilizador;
        $plano_id = (int)$plano_id;

        if($id_utilizador <= 0 || $plano_id <= 0){
            return $this->j(['flag'=>false,'msg'=>'Dados inválidos']);
        }

        $codigo_cliente = $this->getCodigoCliente($id_utilizador);
        if($codigo_cliente <= 0){
            return $this->j(['flag'=>false,'msg'=>'Cliente não encontrado']);
        }

        $stmtChk = $conn->prepare("
            SELECT id
            FROM plano_pt
            WHERE id = ? AND codigo_cliente = ? AND criado_por = 'CLIENTE'
            LIMIT 1
        ");
        $stmtChk->bind_param("ii", $plano_id, $codigo_cliente);
        $stmtChk->execute();
        $resChk = $stmtChk->get_result();
        if(!$resChk || $resChk->num_rows == 0){
            return $this->j(['flag'=>false,'msg'=>'Plano não encontrado']);
        }

        $dias = json_decode($dias_json, true);
        if(!is_array($dias)){
            return $this->j(['flag'=>false,'msg'=>'Formato inválido']);
        }

        $stmtDelEx = $conn->prepare("
            DELETE e
            FROM plano_pt_dia_exercicio e
            INNER JOIN plano_pt_dia d ON d.id = e.plano_dia_id
            WHERE d.plano_id = ?
        ");
        $stmtDelEx->bind_param("i", $plano_id);
        $stmtDelEx->execute();

        $stmtDelDias = $conn->prepare("DELETE FROM plano_pt_dia WHERE plano_id = ?");
        $stmtDelDias->bind_param("i", $plano_id);
        $stmtDelDias->execute();

        $nomesDia = [1=>"Segunda",2=>"Terça",3=>"Quarta",4=>"Quinta",5=>"Sexta",6=>"Sábado",7=>"Domingo"];

        $stmtInsDia = $conn->prepare("
            INSERT INTO plano_pt_dia (plano_id, dia_semana, nome)
            VALUES (?, ?, ?)
        ");
        if(!$stmtInsDia){
            return $this->j(['flag'=>false,'msg'=>'Erro SQL (dia)']);
        }

        $stmtInsEx = $conn->prepare("
            INSERT INTO plano_pt_dia_exercicio
            (plano_dia_id, id_exercicio, ordem, series, reps, descanso_seg, rpe, tempo, observacoes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        if(!$stmtInsEx){
            return $this->j(['flag'=>false,'msg'=>'Erro SQL (exercício)']);
        }

        foreach($dias as $dia => $lista){
            $diaN = (int)$dia;
            if($diaN < 1 || $diaN > 7) continue;
            if(!is_array($lista) || count($lista) == 0) continue;

            $nome = $nomesDia[$diaN];

            $stmtInsDia->bind_param("iis", $plano_id, $diaN, $nome);
            if(!$stmtInsDia->execute()){
                continue;
            }

            $plano_dia_id = (int)$stmtInsDia->insert_id;

            $ordem = 0;
            foreach($lista as $it){
                $ordem++;

                $id_ex = isset($it['id_exercicio']) ? (int)$it['id_exercicio'] : 0;
                $series = isset($it['series']) ? (int)$it['series'] : 3;
                $reps = isset($it['reps']) ? (string)$it['reps'] : '8-12';
                $desc = isset($it['descanso']) ? (int)$it['descanso'] : 0;
                $rpe = isset($it['rpe']) ? (float)$it['rpe'] : 0;
                $tempo = isset($it['tempo']) ? (string)$it['tempo'] : '';
                $obs = isset($it['observacoes']) ? (string)$it['observacoes'] : '';

                if($id_ex <= 0) continue;
                if($series <= 0) $series = 3;
                if(trim($reps) === '') $reps = '8-12';

                $stmtInsEx->bind_param(
                    "iiiisidss",
                    $plano_dia_id, $id_ex, $ordem, $series, $reps, $desc, $rpe, $tempo, $obs
                );
                $stmtInsEx->execute();
            }
        }

        return $this->j(['flag'=>true,'msg'=>'Plano guardado com sucesso']);
    }

    public function listarPlanos($id_utilizador){
        global $conn;

        $id_utilizador = (int)$id_utilizador;
        if($id_utilizador <= 0) return $this->j(['flag'=>false]);

        $codigo_cliente = $this->getCodigoCliente($id_utilizador);
        if($codigo_cliente <= 0) return $this->j(['flag'=>false]);

        $stmt = $conn->prepare("
            SELECT id, titulo, criado_em
            FROM plano_pt
            WHERE codigo_cliente = ? AND criado_por = 'CLIENTE'
            ORDER BY id DESC
        ");
        if(!$stmt){
            return $this->j(['flag'=>false,'msg'=>'Erro SQL']);
        }

        $stmt->bind_param("i", $codigo_cliente);
        $stmt->execute();
        $res = $stmt->get_result();

        if(!$res || $res->num_rows == 0){
            return $this->j(['flag'=>false]);
        }

        $html = "<div class='list-group'>";
        while($r = $res->fetch_assoc()){
            $id = (int)$r['id'];
            $tit = htmlspecialchars($r['titulo']);
            $data = isset($r['criado_em']) ? htmlspecialchars($r['criado_em']) : '';

            $html .= "<div class='list-group-item d-flex justify-content-between align-items-center'>";
            $html .= "<div><div class='fw-semibold'>{$tit}</div><small class='text-muted'>{$data}</small></div>";
            $html .= "<div class='d-flex gap-2'>";
            $html .= "<button class='pt-action-btn pt-action-btn--green' onclick='verPlano({$id})'>
                    <i class='bi bi-eye'></i>
                    </button>";
            $html .= "<button class='pt-action-btn pt-action-btn--red' onclick='removerPlano({$id})'>
                    <i class='bi bi-trash'></i>
                    </button>";
            $html .= "</div>";
            $html .= "</div>";
        }
        $html .= "</div>";

        return $this->j(['flag'=>true,'html'=>$html]);
    }

    public function detalhePlano($id_utilizador, $plano_id){
        global $conn;

        $id_utilizador = (int)$id_utilizador;
        $plano_id = (int)$plano_id;

        if($id_utilizador <= 0 || $plano_id <= 0){
            return $this->j(['flag'=>false,'msg'=>'Dados inválidos']);
        }

        $codigo_cliente = $this->getCodigoCliente($id_utilizador);
        if($codigo_cliente <= 0){
            return $this->j(['flag'=>false,'msg'=>'Cliente não encontrado']);
        }

        $stmt = $conn->prepare("
            SELECT titulo
            FROM plano_pt
            WHERE id = ? AND codigo_cliente = ? AND criado_por = 'CLIENTE'
            LIMIT 1
        ");
        $stmt->bind_param("ii", $plano_id, $codigo_cliente);
        $stmt->execute();
        $res = $stmt->get_result();

        if(!$res || $res->num_rows == 0){
            return $this->j(['flag'=>false,'msg'=>'Plano não encontrado']);
        }

        $row = $res->fetch_assoc();
        $titulo = $row['titulo'];

        $stmt2 = $conn->prepare("
            SELECT d.dia_semana, d.nome AS nome_dia,
                   e.nome AS exercicio,
                   x.ordem, x.series, x.reps, x.descanso_seg, x.rpe, x.tempo, x.observacoes
            FROM plano_pt_dia d
            INNER JOIN plano_pt_dia_exercicio x ON x.plano_dia_id = d.id
            INNER JOIN exercicio e ON e.id_exercicio = x.id_exercicio
            WHERE d.plano_id = ?
            ORDER BY d.dia_semana ASC, x.ordem ASC
        ");
        $stmt2->bind_param("i", $plano_id);
        $stmt2->execute();
        $res2 = $stmt2->get_result();

        $html = "";
        $diaAtual = -1;

        while($r = $res2->fetch_assoc()){
            $dia = (int)$r['dia_semana'];

            if($dia !== $diaAtual){
                if($diaAtual !== -1) $html .= "</ul>";
                $diaAtual = $dia;

                $nd = htmlspecialchars($r['nome_dia']);
                $html .= "<h6 class='fw-bold mt-3'>{$nd}</h6>";
                $html .= "<ul class='list-group'>";
            }

            $ex = htmlspecialchars($r['exercicio']);
            $ser = (int)$r['series'];
            $reps = htmlspecialchars($r['reps']);
            $desc = (int)$r['descanso_seg'];

            $extra = "";
            $rpe = (float)$r['rpe'];
            $tempo = trim((string)$r['tempo']);
            $obs = trim((string)$r['observacoes']);

            if($rpe > 0) $extra .= " • RPE ".htmlspecialchars((string)$rpe);
            if($tempo !== "") $extra .= " • Tempo ".htmlspecialchars($tempo);
            if($obs !== "") $extra .= "<br><small class='text-muted'>".htmlspecialchars($obs)."</small>";

            $html .= "<li class='list-group-item'>";
            $html .= "<div class='fw-semibold'>{$ex}</div>";
            $html .= "<small class='text-muted'>{$ser} séries • {$reps} reps • {$desc}s descanso{$extra}</small>";
            $html .= "</li>";
        }

        if($diaAtual !== -1) $html .= "</ul>";
        if($html === "") $html = "<p class='text-muted'>Sem exercícios.</p>";

        return $this->j(['flag'=>true,'titulo'=>$titulo,'html'=>$html]);
    }

    public function removerPlano($id_utilizador, $plano_id){
        global $conn;

        $id_utilizador = (int)$id_utilizador;
        $plano_id = (int)$plano_id;

        if($id_utilizador <= 0 || $plano_id <= 0){
            return $this->j(['flag'=>false,'msg'=>'Dados inválidos']);
        }

        $codigo_cliente = $this->getCodigoCliente($id_utilizador);
        if($codigo_cliente <= 0){
            return $this->j(['flag'=>false,'msg'=>'Cliente não encontrado']);
        }

        $stmtDelEx = $conn->prepare("
            DELETE e
            FROM plano_pt_dia_exercicio e
            INNER JOIN plano_pt_dia d ON d.id = e.plano_dia_id
            WHERE d.plano_id = ?
        ");
        $stmtDelEx->bind_param("i", $plano_id);
        $stmtDelEx->execute();

        $stmtDelDias = $conn->prepare("DELETE FROM plano_pt_dia WHERE plano_id = ?");
        $stmtDelDias->bind_param("i", $plano_id);
        $stmtDelDias->execute();

        $stmt = $conn->prepare("
            DELETE FROM plano_pt
            WHERE id = ? AND codigo_cliente = ? AND criado_por = 'CLIENTE'
        ");
        $stmt->bind_param("ii", $plano_id, $codigo_cliente);

        if($stmt->execute() && $stmt->affected_rows > 0){
            return $this->j(['flag'=>true,'msg'=>'Plano removido']);
        }

        return $this->j(['flag'=>false,'msg'=>'Não foi possível remover']);
    }

    public function listarFicheirosPT($id_utilizador){
        global $conn;

        $id_utilizador = (int)$id_utilizador;
        if($id_utilizador <= 0){
            return $this->j(['flag'=>false,'msg'=>'Sessão inválida']);
        }

        $codigo_cliente = $this->getCodigoCliente($id_utilizador);
        if($codigo_cliente <= 0){
            return $this->j(['flag'=>false,'msg'=>'Cliente não encontrado']);
        }

        $stmt = $conn->prepare("
            SELECT nome_original, nome_ficheiro, caminho, data_envio
            FROM plano_pt_ficheiros
            WHERE cliente_id = ?
            ORDER BY data_envio DESC
        ");
        $stmt->bind_param("i", $codigo_cliente);
        $stmt->execute();
        $res = $stmt->get_result();

        if(!$res || $res->num_rows == 0){
            return $this->j(['flag'=>false]);
        }

        $html = "<div class='list-group'>";
        while($r = $res->fetch_assoc()){
            $orig = htmlspecialchars($r['nome_original']);
            $link = htmlspecialchars($r['caminho']."/".$r['nome_ficheiro']);
            $data = htmlspecialchars($r['data_envio']);

            $html .= "<div class='list-group-item d-flex justify-content-between align-items-center'>";
            $html .= "<div><div class='fw-semibold'>{$orig}</div><small class='text-muted'>{$data}</small></div>";
            $html .= "<a class='btn btn-sm btn-success' target='_blank' href='{$link}'>Ver</a>";
            $html .= "</div>";
        }
        $html .= "</div>";

        return $this->j(['flag'=>true,'html'=>$html]);
    }

    public function listarPlanosPT($id_utilizador){
        global $conn;

        $id_utilizador = (int)$id_utilizador;
        if($id_utilizador <= 0){
            return $this->j(['flag'=>false,'msg'=>'Sessão inválida']);
        }

        $codigo_cliente = $this->getCodigoCliente($id_utilizador);
        if($codigo_cliente <= 0){
            return $this->j(['flag'=>false,'msg'=>'Cliente não encontrado']);
        }

        $stmt = $conn->prepare("
            SELECT id, titulo, criado_em
            FROM plano_pt
            WHERE codigo_cliente = ?
            AND criado_por = 'PT'
            AND publicado = 1
            ORDER BY id DESC
        ");
        if(!$stmt){
            return $this->j(['flag'=>false,'msg'=>'Erro SQL']);
        }

        $stmt->bind_param("i", $codigo_cliente);
        $stmt->execute();
        $res = $stmt->get_result();

        if(!$res || $res->num_rows == 0){
            return $this->j(['flag'=>false]);
        }

        $html = "<div class='list-group'>";
        while($r = $res->fetch_assoc()){
            $id = (int)$r['id'];
            $tit = htmlspecialchars($r['titulo']);
            $data = isset($r['criado_em']) ? htmlspecialchars($r['criado_em']) : '';

            $html .= "<div class='list-group-item d-flex justify-content-between align-items-center'>";
            $html .= "<div><div class='fw-semibold'>{$tit}</div><small class='text-muted'>{$data}</small></div>";
            $html .= "<button class='pt-action-btn pt-action-btn--green' onclick='verPlanoPT({$id})'>
                    <i class='bi bi-eye'></i>
                    </button>";
            $html .= "</div>";
        }
        $html .= "</div>";

        return $this->j(['flag'=>true,'html'=>$html]);
    }
    public function detalhePlanoPT($id_utilizador, $plano_id){
        global $conn;

        $id_utilizador = (int)$id_utilizador;
        $plano_id = (int)$plano_id;

        if($id_utilizador <= 0 || $plano_id <= 0){
            return $this->j(['flag'=>false,'msg'=>'Dados inválidos']);
        }

        $codigo_cliente = $this->getCodigoCliente($id_utilizador);
        if($codigo_cliente <= 0){
            return $this->j(['flag'=>false,'msg'=>'Cliente não encontrado']);
        }

        $stmt = $conn->prepare("
            SELECT titulo
            FROM plano_pt
            WHERE id = ? AND codigo_cliente = ? AND criado_por = 'PT' AND publicado = 1
            LIMIT 1
        ");
        $stmt->bind_param("ii", $plano_id, $codigo_cliente);
        $stmt->execute();
        $res = $stmt->get_result();

        if(!$res || $res->num_rows == 0){
            return $this->j(['flag'=>false,'msg'=>'Plano não encontrado']);
        }

        $row = $res->fetch_assoc();
        $titulo = $row['titulo'];

        $stmt2 = $conn->prepare("
            SELECT d.dia_semana, d.nome AS nome_dia,
                e.nome AS exercicio,
                x.ordem, x.series, x.reps, x.descanso_seg, x.rpe, x.tempo, x.observacoes
            FROM plano_pt_dia d
            INNER JOIN plano_pt_dia_exercicio x ON x.plano_dia_id = d.id
            INNER JOIN exercicio e ON e.id_exercicio = x.id_exercicio
            WHERE d.plano_id = ?
            ORDER BY d.dia_semana ASC, x.ordem ASC
        ");
        $stmt2->bind_param("i", $plano_id);
        $stmt2->execute();
        $res2 = $stmt2->get_result();

        $html = "";
        $diaAtual = -1;

        while($r = $res2->fetch_assoc()){
            $dia = (int)$r['dia_semana'];

            if($dia !== $diaAtual){
                if($diaAtual !== -1) $html .= "</ul>";
                $diaAtual = $dia;

                $nd = htmlspecialchars($r['nome_dia']);
                $html .= "<h6 class='fw-bold mt-3'>{$nd}</h6>";
                $html .= "<ul class='list-group'>";
            }

            $ex = htmlspecialchars($r['exercicio']);
            $ser = (int)$r['series'];
            $reps = htmlspecialchars($r['reps']);
            $desc = (int)$r['descanso_seg'];

            $html .= "<li class='list-group-item'>";
            $html .= "<div class='fw-semibold'>{$ex}</div>";
            $html .= "<small class='text-muted'>{$ser} séries • {$reps} reps • {$desc}s descanso</small>";
            $html .= "</li>";
        }

        if($diaAtual !== -1) $html .= "</ul>";
        if($html === "") $html = "<p class='text-muted'>Sem exercícios.</p>";

        return $this->j(['flag'=>true,'titulo'=>$titulo,'html'=>$html]);
    }
}
?>