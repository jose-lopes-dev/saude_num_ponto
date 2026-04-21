<?php
require_once __DIR__ . "/connection.php";

class ChatbotModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    private function resposta($flag, $msg, $extra = [])
    {
        return array_merge(["flag" => $flag, "msg" => $msg], $extra);
    }

    private function fetchLista($sql)
    {
        $res = $this->conn->query($sql);
        if (!$res) return [];

        $rows = [];
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /* -------------------------------------------
       LISTAS DINÂMICAS DO CHATBOT
    ------------------------------------------- */

    public function getObjetivos()
    {
        return $this->fetchLista("SELECT id, nome FROM objetivo ORDER BY id ASC");
    }

    public function getNiveis()
    {
        return $this->fetchLista("SELECT id, nome FROM nivel_atividade ORDER BY id ASC");
    }

    public function getAtividades()
    {
        return $this->fetchLista("SELECT id, nome FROM atividades ORDER BY id ASC");
    }

    public function getTiposCorpo()
    {
        return $this->fetchLista("SELECT id, nome FROM tipo_corpo ORDER BY id ASC");
    }

    public function getHabitosDiarios()
{
    return $this->fetchLista("SELECT id, descricao AS nome FROM habito_diario ORDER BY id ASC");
}


    public function getAreasCorpo()
    {
        return $this->fetchLista("SELECT id, nome FROM area_corpo ORDER BY id ASC");
    }

    public function getTiposDieta()
    {
        return $this->fetchLista("SELECT id, nome FROM tipo_dieta ORDER BY id ASC");
    }

    public function getGeneros()
    {
        return $this->fetchLista("SELECT id, nome FROM genero ORDER BY id ASC");
    }

    public function getCondicoes()
    {
        return $this->fetchLista("SELECT id, nome FROM condicao_saude ORDER BY id ASC");
    }

    /* -------------------------------------------
       GUARDAR PERFIL DO CLIENTE
    ------------------------------------------- */
    public function saveProfile(
        $altura,
        $peso,
        $peso_pretendido,
        $id_objetivo,
        $id_nivel,
        $id_atividades,
        $id_tipo_corpo,
        $id_habito_diario,
        $id_area_corpo,
        $id_tipo_dieta,
        $genero,
        $id_condicao_saude
    ) {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();

        $id = $_SESSION['id'] ?? 0;
        if (!$id) {
            return $this->resposta(false, "Sessão inválida.");
        }

        // Normalizar números
        $altura = str_replace(",", ".", trim($altura));
        $peso = str_replace(",", ".", trim($peso));
        $peso_pretendido = str_replace(",", ".", trim($peso_pretendido));

        if ($altura === "" || $peso === "" || $peso_pretendido === "") {
            return $this->resposta(false, "Valores inválidos.");
        }

        $sql = "
            UPDATE cliente SET
                altura = ?,
                peso = ?,
                peso_pretendido = ?,
                id_objetivo = ?,
                id_nivel = ?,
                id_atividades = ?,
                id_tipo_corpo = ?,
                id_habito_diario = ?,
                id_area_corpo = ?,
                id_tipo_dieta = ?,
                genero = ?,
                id_condicao_saude = ?,
                perfil_completo = 1
            WHERE id_utilizador = ?
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return $this->resposta(false, "Erro BD (prepare): " . $this->conn->error);
        }

        // Tipos correspondem aos 13 parâmetros
        $types = "sssiiiiiiisii";

        if (!$stmt->bind_param(
            $types,
            $altura,
            $peso,
            $peso_pretendido,
            $id_objetivo,
            $id_nivel,
            $id_atividades,
            $id_tipo_corpo,
            $id_habito_diario,
            $id_area_corpo,
            $id_tipo_dieta,
            $genero,
            $id_condicao_saude,
            $id
        )) {
            return $this->resposta(false, "Erro BD (bind): " . $stmt->error);
        }

        if (!$stmt->execute()) {
            return $this->resposta(false, "Erro BD (execute): " . $stmt->error);
        }

        $stmt->close();
        return $this->resposta(true, "Perfil guardado!");
    }
}
