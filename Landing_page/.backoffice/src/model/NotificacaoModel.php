<?php
require_once __DIR__ . "/connection.php";

class NotificacaoModel {

    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function criar($idUtilizador, $titulo, $texto) {

        $stmt = $this->conn->prepare("
            INSERT INTO notificacao
                (id_utilizador, titulo, texto, lida, criada_em)
            VALUES
                (?, ?, ?, 0, NOW())
        ");

        $stmt->bind_param("iss", $idUtilizador, $titulo, $texto);
        return $stmt->execute();
    }
}
