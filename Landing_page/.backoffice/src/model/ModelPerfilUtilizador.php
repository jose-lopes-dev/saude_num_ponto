<?php
require_once __DIR__ . '/connection.php';

class ModelPerfilUtilizador {

    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getPerfil($idUtilizador) {

        $sql = "
            SELECT 
                u.id,
                u.email,
                u.foto,
                tu.nome AS tipo_utilizador,
                c.nome_completo,
                c.nif,
                c.contacto
            FROM utilizador u
            INNER JOIN tipo_user tu ON tu.id = u.id_tipo_user
            LEFT JOIN cliente c ON c.id_utilizador = u.id
            WHERE u.id = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $idUtilizador);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function guardarPerfil($idUtilizador, $dados, $fotoPath = null) {

        if ($fotoPath !== null) {
            $stmt = $this->conn->prepare(
                "UPDATE utilizador SET foto = ? WHERE id = ?"
            );
            $stmt->bind_param("si", $fotoPath, $idUtilizador);
            $stmt->execute();
        }

        $stmt = $this->conn->prepare(
            "SELECT 1 FROM cliente WHERE id_utilizador = ? LIMIT 1"
        );
        $stmt->bind_param("i", $idUtilizador);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 1) {
            $stmt = $this->conn->prepare("
                UPDATE cliente
                SET nome_completo = ?, nif = ?, contacto = ?
                WHERE id_utilizador = ?
            ");
            $stmt->bind_param(
                "sssi",
                $dados["nome_completo"],
                $dados["nif"],
                $dados["contacto"],
                $idUtilizador
            );
            $stmt->execute();
        }

        return true;
    }
}
