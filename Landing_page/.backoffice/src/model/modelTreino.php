<?php
// assets/model/modelTreino.php
require_once 'connection.php';

class Treino {
    private $conn;

    public function __construct() {
        global $conn; // padrão que usas noutros modelos
        $this->conn = $conn;
    }

    // Registar treino (util para backoffice)
    public function registaTreino($titulo, $descricao, $duracao_min, $nivel, $grupo, $video_url, $thumbnail, $ativo=1) {
        $titulo = $this->conn->real_escape_string($titulo);
        $descricao = $this->conn->real_escape_string($descricao);
        $video_url = $this->conn->real_escape_string($video_url);
        $thumbnail = $this->conn->real_escape_string($thumbnail);
        $sql = "INSERT INTO treinos (titulo, descricao, duracao_min, nivel, grupo_muscular, video_url, thumbnail, ativo)
                VALUES ('$titulo', '$descricao', ".intval($duracao_min).", '$nivel', '$grupo', '$video_url', '$thumbnail', ".intval($ativo).")";
        if ($this->conn->query($sql)) {
            return json_encode(['flag'=>true, 'msg'=>'Treino registado com sucesso']);
        } else {
            return json_encode(['flag'=>false, 'msg'=>'Erro: '.$this->conn->error]);
        }
    }

    // Lista treinos — retorna HTML (padrão dos teus controllers)
    public function getListaTreinos() {
        $msg = "";
        $sql = "SELECT * FROM treinos WHERE ativo = 1 ORDER BY id DESC";
        $res = $this->conn->query($sql);
        if ($res && $res->num_rows > 0) {
            while($row = $res->fetch_assoc()) {
                $thumb = !empty($row['thumbnail']) ? $row['thumbnail'] : 'assets/images/placeholder.png';
                $titulo = htmlspecialchars($row['titulo'], ENT_QUOTES);
                $descricao = htmlspecialchars($row['descricao'], ENT_QUOTES);
                $msg .= "<div class='col-sm-6 col-md-4'>
                    <div class='card treino-card' data-id='{$row['id']}'>
                        <img src='{$thumb}' class='card-img-top treino-thumb' alt='{$titulo}'>
                        <div class='card-body treino-body'>
                            <h5 class='card-title'>{$titulo}</h5>
                            <p class='card-text text-truncate'>{$descricao}</p>
                            <div class='d-flex justify-content-between align-items-center'>
                                <small class='text-muted'>{$row['nivel']} • {$row['duracao_min']} min</small>
                                <button class='btn btn-sm btn-outline-primary btn-ver' onclick=\"abrirModalTreino({$row['id']})\">Ver</button>
                            </div>
                        </div>
                    </div>
                </div>";
            }
        } else {
            $msg = "<div class='col-12'><div class='alert alert-info mb-0'>Sem treinos disponíveis.</div></div>";
        }
        return $msg;
    }

    // Get treino por id (json)
    public function getTreinoById($id) {
        $id = intval($id);
        $sql = "SELECT * FROM treinos WHERE id = $id";
        $res = $this->conn->query($sql);
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            return json_encode($row);
        }
        return json_encode(null);
    }
}
