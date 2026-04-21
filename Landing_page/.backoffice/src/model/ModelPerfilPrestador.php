<?php
require_once __DIR__ . '/connection.php';

class ModelPerfilPrestador {

    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    /* =========================
       OBTER PERFIL
    ========================= */
    public function getPerfil($idUtilizador) {

        $stmt = $this->conn->prepare("
            SELECT 
                rh.nome_completo,
                rh.nif,
                rh.contacto,
                rh.qualificacao,
                rh.experiencia_anos,
                f.descricao AS funcao,
                u.foto
            FROM rh
            INNER JOIN utilizador u ON u.id = rh.id_utilizador
            INNER JOIN funcao f ON f.id = rh.id_funcao
            WHERE rh.id_utilizador = ?
            LIMIT 1
        ");

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param("i", $idUtilizador);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /* =========================
       GUARDAR PERFIL
    ========================= */
   public function guardarPerfil($id, $dados, $fotoPath = null) {

    $nome_completo    = $dados["nome_completo"] ?? "";
    $nif              = $dados["nif"] ?? "";
    $contacto         = $dados["contacto"] ?? "";
    $qualificacao     = $dados["qualificacao"] ?? "";
    $experiencia_anos = isset($dados["experiencia_anos"])
        ? intval($dados["experiencia_anos"])
        : 0;

    $stmt = $this->conn->prepare("
        UPDATE rh
        SET nome_completo = ?,
            nif = ?,
            contacto = ?,
            qualificacao = ?,
            experiencia_anos = ?
        WHERE id_utilizador = ?
    ");

    if (!$stmt) {
        return "Erro ao preparar atualização do perfil";
    }

    $stmt->bind_param(
        "ssssii",
        $nome_completo,
        $nif,
        $contacto,
        $qualificacao,
        $experiencia_anos,
        $id
    );

    if (!$stmt->execute()) {
        return "Erro ao atualizar dados do perfil";
    }

    if ($fotoPath !== null) {

        $stmtFoto = $this->conn->prepare("
            UPDATE utilizador
            SET foto = ?
            WHERE id = ?
        ");

        if (!$stmtFoto) {
            return "Erro ao preparar atualização da foto";
        }

        $stmtFoto->bind_param("si", $fotoPath, $id);

        if (!$stmtFoto->execute()) {
            return "Erro ao atualizar foto";
        }
    }

    return true;
}
}
