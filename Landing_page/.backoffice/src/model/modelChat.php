<?php
require_once 'connection.php';

class ModelChat {

  private $db;

  public function __construct(){
    global $conn;
    $this->db = $conn;
  }

public function conversas(int $uid){
  if($uid <= 0) return [];

  $sql = "
    SELECT
      u.id,
      u.username AS nome,
      SUM(
        CASE
          WHEN c.id_destinatario = ? AND c.lida = 0 THEN 1
          ELSE 0
        END
      ) AS nao_lidas,
      MAX(c.data_envio) AS ultima
    FROM chat c
    JOIN utilizador u
      ON (
        (c.id_remetente = ? AND u.id = c.id_destinatario)
        OR
        (c.id_destinatario = ? AND u.id = c.id_remetente)
      )
    WHERE c.id_remetente = ? OR c.id_destinatario = ?
    GROUP BY u.id, u.username
    ORDER BY ultima DESC
  ";

  $st = $this->db->prepare($sql);
  $st->bind_param("iiiii", $uid, $uid, $uid, $uid, $uid);
  $st->execute();

  return $st->get_result()->fetch_all(MYSQLI_ASSOC);
}



  public function mensagens(int $u, int $o, int $after){
    if($u <= 0 || $o <= 0) return [];

    $sql = "
      SELECT
        id,
        id_remetente,
        id_destinatario,
        mensagem,
        data_envio
      FROM chat
      WHERE id > ?
        AND (
          (id_remetente = ? AND id_destinatario = ?)
          OR
          (id_remetente = ? AND id_destinatario = ?)
        )
      ORDER BY id ASC
    ";

    $st = $this->db->prepare($sql);
    $st->bind_param("iiiii", $after, $u, $o, $o, $u);
    $st->execute();

    return $st->get_result()->fetch_all(MYSQLI_ASSOC);
  }

public function enviar(int $u, int $o, string $m){
  if($u <= 0 || $o <= 0 || $m === ''){
    return false;
  }

  $st = $this->db->prepare("
    INSERT INTO chat (id_remetente,id_destinatario,mensagem)
    VALUES (?,?,?)
  ");
  $st->bind_param("iis",$u,$o,$m);
  $st->execute();

  return $st->affected_rows > 0;
}


  public function marcarLidas(int $u, int $o){
    if($u <= 0 || $o <= 0) return;

    $sql = "
      UPDATE chat
      SET lida = 1
      WHERE id_destinatario = ?
        AND id_remetente = ?
        AND lida = 0
    ";

    $st = $this->db->prepare($sql);
    $st->bind_param("ii", $u, $o);
    $st->execute();
  }

  public function searchUsers(string $q){
    $like = "%$q%";

    $sql = "
      SELECT
        id,
        username AS text
      FROM utilizador
      WHERE username LIKE ?
      ORDER BY username
      LIMIT 20
    ";

    $st = $this->db->prepare($sql);
    $st->bind_param("s", $like);
    $st->execute();

    return $st->get_result()->fetch_all(MYSQLI_ASSOC);
  }
}
