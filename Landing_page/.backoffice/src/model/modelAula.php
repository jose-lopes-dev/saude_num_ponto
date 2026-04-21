<?php
require_once 'connection.php';

class Aula {
    private $db;

    public function __construct() {
        global $conn;
        $this->db = $conn;
    }

    // obter cliente.codigo a partir do utilizador.id
    
    public function getClienteCodigoByUtilizador($id_utilizador) {
        $sql = "
            SELECT codigo
            FROM cliente
            WHERE id_utilizador = ?
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_utilizador);
        $stmt->execute();
        $r = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $r['codigo'] ?? null;
    }

    // Regista nova aula
    public function registarAula(
        $titulo, $descricao, $data_inicio, $duracao_min,
        $limite, $nivel, $preco, $id_pt, $id_estado, $sala_nome
    ) {
        $sql = "
            INSERT INTO aula
            (titulo, descricao, data_inicio, duracao_min, limite_participantes,
             nivel, preco, id_pt, id_estado, created_at, sala_nome)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            'sssiisdiis',
            $titulo, $descricao, $data_inicio, $duracao_min,
            $limite, $nivel, $preco, $id_pt, $id_estado, $sala_nome

        );

        $ok = $stmt->execute();
        $stmt->close();

        return json_encode([
            'flag' => $ok,
            'msg'  => $ok ? 'Aula registada' : 'Erro ao registar aula'
        ]);
    }

    // Edita aula
    public function editarAula(
    $id, $titulo, $descricao, $data_inicio,
    $duracao_min, $limite, $nivel, $preco,
    $id_pt, $id_estado, $sala_nome
) {
    $sql = "
        UPDATE aula SET
            titulo = ?,
            descricao = ?,
            data_inicio = ?,
            duracao_min = ?,
            limite_participantes = ?,
            nivel = ?,
            preco = ?,
            id_pt = ?,
            id_estado = ?,
            sala_nome = ?,
            updated_at = NOW()
        WHERE id = ?
    ";

    $stmt = $this->db->prepare($sql);
    if (!$stmt) {
        return json_encode([
            'flag' => false,
            'msg' => $this->db->error
        ]);
    }

    $stmt->bind_param(
    'sssiisdiisi',
    $titulo,
    $descricao,
    $data_inicio,
    $duracao_min,
    $limite,
    $nivel,        
    $preco,       
    $id_pt,        
    $id_estado,    
    $sala_nome,    
    $id           
);
    $ok = $stmt->execute();
    $stmt->close();

    return json_encode([
        'flag' => $ok,
        'msg'  => $ok ? 'Aula atualizada com sucesso' : 'Erro ao atualizar aula'
    ]);
}


    // Lista (admin) — devolve result set
    public function listarAdmin() {
        $sql = "SELECT a.*, u.username as pt_username, e.descricao as estado_nome
                FROM aula a
                LEFT JOIN utilizador u ON a.id_pt = u.id
                LEFT JOIN estado e ON a.id_estado = e.id
                ORDER BY a.data_inicio DESC";
        return $this->db->query($sql);
    }

     public function listarPublico($id_estado = null, $id_cliente = null) {

        $sql = "
            SELECT 
                a.*,
                COUNT(ia.id) AS inscritos,
                MAX(
                    CASE 
                        WHEN ia.id_cliente = ? AND ia.id_estado = 15 
                        THEN 1 ELSE 0 
                    END
                ) AS ja_inscrito
            FROM aula a
            LEFT JOIN inscricao_aula ia ON ia.id_aula = a.id
        ";

        if ($id_estado !== null) {
            $sql .= " WHERE a.id_estado = " . intval($id_estado);
        }

        $sql .= " GROUP BY a.id ORDER BY a.data_inicio ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_cliente);
        $stmt->execute();

        return $stmt->get_result();
    }

    // Get aula by id (assume JSON output)
    public function getAulaById($id) {
        $stmt = $this->db->prepare("
            SELECT *
            FROM aula
            WHERE id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $r = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return json_encode($r);
    }

    // (verifica limite e duplicados). id_estado default para inscrição = 15 (confirmada) — podes alterar.
    public function inscreverAula($id_aula, $id_cliente) {

        // duplicado
        if ($this->clienteJaInscrito($id_cliente, $id_aula)) {
            return json_encode(['flag'=>false,'msg'=>'Já inscrito']);
        }

        // limite
        $lim = $this->db->query("
            SELECT limite_participantes 
            FROM aula WHERE id = $id_aula
        ")->fetch_assoc();

        $cnt = $this->db->query("
            SELECT COUNT(*) c
            FROM inscricao_aula
            WHERE id_aula = $id_aula AND id_estado = 15
        ")->fetch_assoc()['c'];

        if ($cnt >= $lim['limite_participantes']) {
            return json_encode(['flag'=>false,'msg'=>'Aula lotada']);
        }

        $stmt = $this->db->prepare("
            INSERT INTO inscricao_aula
            (id_aula, id_cliente, id_estado, created_at)
            VALUES (?, ?, 15, NOW())
        ");
        $stmt->bind_param("ii", $id_aula, $id_cliente);
        $ok = $stmt->execute();
        $stmt->close();

        return json_encode([
            'flag'=>$ok,
            'msg'=>$ok ? 'Inscrição efetuada' : 'Erro'
        ]);
    }

    // Cancelar inscrição (por cliente) — actualiza id_estado para cancelado ou elimina dependendo do fluxo
    public function cancelarInscricao($id_aula, $id_cliente, $id_estado_cancelado = null) {
        $id_aula = (int)$id_aula; $id_cliente = (int)$id_cliente;
        if ($id_estado_cancelado) {
            $id_estado_cancelado = (int)$id_estado_cancelado;
            $stmt = $this->db->prepare("UPDATE inscricao_aula SET id_estado = ? WHERE id_aula = ? AND id_cliente = ?");
            $stmt->bind_param('iii', $id_estado_cancelado, $id_aula, $id_cliente);
            $ok = $stmt->execute();
            $stmt->close();
            return json_encode(['flag'=>$ok,'msg'=>$ok ? 'Inscrição cancelada' : 'Erro: '.$this->db->error]);
        } else {
            // remover
            $stmt = $this->db->prepare("DELETE FROM inscricao_aula WHERE id_aula = ? AND id_cliente = ?");
            $stmt->bind_param('ii',$id_aula,$id_cliente);
            $ok = $stmt->execute();
            $stmt->close();
            return json_encode(['flag'=>$ok,'msg'=>$ok ? 'Inscrição removida' : 'Erro: '.$this->db->error]);
        }
    }

    // Lista inscritos de uma aula (admin / PT) — devolve result set
    public function listarInscritos($id_aula) {
    $id_aula = (int)$id_aula;

    $sql = "
        SELECT 
            ia.created_at,
            e.descricao AS estado_nome,
            c.nome_completo AS cliente_nome
        FROM inscricao_aula ia
        INNER JOIN cliente c ON ia.id_cliente = c.codigo
        INNER JOIN utilizador u ON c.id_utilizador = u.id
        LEFT JOIN estado e ON ia.id_estado = e.id
        WHERE ia.id_aula = ?
        ORDER BY ia.created_at ASC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param('i', $id_aula);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

    return $res;
}

    // Remover aula (admin)
    public function removerAula($id) {
        $id = (int)$id;
        $stmt = $this->db->prepare("DELETE FROM aula WHERE id = ?");
        $stmt->bind_param('i',$id);
        $ok = $stmt->execute();
        $stmt->close();
        return json_encode(['flag'=>$ok,'msg'=>$ok ? 'Aula removida' : 'Erro: '.$this->db->error]);
    }

    public function listarEstados() {
        return $this->db->query("
            SELECT id, descricao 
            FROM estado 
            ORDER BY descricao
        ");
    }

    public function listarIDPT() {
    return $this->db->query("
        SELECT u.id, r.nome_completo
        FROM utilizador u
        INNER JOIN rh r ON r.id_utilizador = u.id
        WHERE r.id_funcao = 3   -- PT
        ORDER BY r.nome_completo
    ");
}

 public function listarAulasPT($id_pt) {

    $sql = "
        SELECT 
    a.id,
    a.titulo,
    a.data_inicio,
    a.id_estado,
    COUNT(ia.id) AS inscritos
FROM aula a
LEFT JOIN inscricao_aula ia 
    ON ia.id_aula = a.id
   AND ia.id_estado = 15
WHERE a.id_pt = ?
GROUP BY a.id
ORDER BY a.data_inicio DESC

    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $id_pt);
    $stmt->execute();

    return $stmt->get_result();
}

public function clienteInscrito($id_cliente, $id_aula) {
    $sql = "
        SELECT 1 
        FROM inscricao_aula 
        WHERE id_cliente = ? 
          AND id_aula = ? 
          AND id_estado = 15
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ii", $id_cliente, $id_aula);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

    return $res->num_rows > 0;
}

public function getSalaAula($idAula) {

    $sql = "
        SELECT 
            id,
            sala_nome,
            sala_ativa,
            data_inicio,
            duracao_min,
            id_estado
        FROM aula
        WHERE id = ?
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $idAula);
    $stmt->execute();
    $aula = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$aula) return null;

    return [
        'room'        => $aula['sala_nome'] ?: 'aula-'.$aula['id'],
        'ativa'       => (bool)$aula['sala_ativa'],
        'data_inicio' => $aula['data_inicio'],
        'duracao'     => (int)$aula['duracao_min'],
        'estado'      => (int)$aula['id_estado']
    ];
}

public function getAulaDetalheCliente($id_aula, $id_cliente = null) {

    $sql = "
        SELECT 
            a.*,
            e.descricao AS estado_nome,
            CASE 
                WHEN ia.id IS NOT NULL THEN 1 ELSE 0 
            END AS ja_inscrito
        FROM aula a
        LEFT JOIN estado e ON a.id_estado = e.id
        LEFT JOIN inscricao_aula ia 
            ON ia.id_aula = a.id
           AND ia.id_cliente = ?
           AND ia.id_estado = 15
        WHERE a.id = ?
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ii", $id_cliente, $id_aula);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

public function clienteJaInscrito($id_cliente, $id_aula) {
        $sql = "
            SELECT 1
            FROM inscricao_aula
            WHERE id_cliente = ?
              AND id_aula = ?
              AND id_estado = 15
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $id_cliente, $id_aula);
        $stmt->execute();
        $stmt->store_result();
        $ok = $stmt->num_rows > 0;
        $stmt->close();

        return $ok;
    }

}
?>



