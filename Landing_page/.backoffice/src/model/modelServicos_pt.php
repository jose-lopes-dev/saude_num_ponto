<?php
require_once 'connection.php';

class PlanosServicosPt {

  public function getListaPlanosSistema(){
    global $conn;
    $html = "";

    $sql = "SELECT id, descricao, preco
            FROM servico
            WHERE descricao LIKE 'PLANO%' OR descricao LIKE 'PACK%'
            ORDER BY id DESC";
    $res = $conn->query($sql);

    while($r = $res->fetch_assoc()){
      $preco = number_format((float)$r['preco'], 2, ',', '.');

      $html .= "<tr>
                  <td>".$r['descricao']."</td>
                  <td>".$preco."€</td>
                </tr>";
    }
    return $html;
  }

  public function getSelectServicosCatalogo(){
    global $conn;
    $html = "<option value=''>Selecione...</option>";

    $sql = "SELECT id, descricao, preco
            FROM servico
            WHERE descricao NOT LIKE 'PLANO%'
              AND descricao NOT LIKE 'PACK%'
              AND UPPER(descricao) NOT LIKE '%AULA DE GRUPO%'
              AND UPPER(descricao) NOT LIKE '%MARKETPLACE%'
            ORDER BY descricao ASC";

    $res = $conn->query($sql);

    while($r = $res->fetch_assoc()){
      $html .= "<option value='{$r['id']}'>
                  {$r['descricao']}
                </option>";
    }
    return $html;
  }

  public function getListaMeusServicos($codigo_rh, $estado = -1){
    global $conn;
    $html = "";

    $estado = intval($estado);
    $filtroEstado = "";
    if ($estado === 0 || $estado === 1) {
      $filtroEstado = " AND ps.ativo = ".$estado." ";
    }

    $sql = "SELECT s.id, s.descricao, s.preco, ps.ativo
            FROM profissional_servico ps
            INNER JOIN servico s ON s.id = ps.id_servico
            WHERE ps.codigo_rh = ".$codigo_rh."
            ".$filtroEstado."
            ORDER BY s.descricao ASC";

    $res = $conn->query($sql);

    while($r = $res->fetch_assoc()){
      $ativo = (int)($r['ativo'] ?? 0);

      $badgeClass = $ativo === 1 ? "bg-success" : "bg-danger";
      $badgeTxt   = $ativo === 1 ? "Ativo" : "Inativo";
      $badge      = "<span class='badge $badgeClass'>$badgeTxt</span>";

      $btnClass = $ativo === 1 ? "btn-outline-danger" : "btn-outline-success";
      $btnTxt   = $ativo === 1 ? "Desativar" : "Ativar";

      $precoCatalogo = number_format((float)$r['preco'], 2, ',', '.');

      $html .= "<tr>
                  <td>".$r['descricao']."</td>
                  <td>".$precoCatalogo."€</td>
                  <td>".$badge."</td>
                  <td class='text-end'>
                    <button class='btn btn-sm ".$btnClass."'
                      onclick='toggleServicoPt(".$r['id'].")'>".$btnTxt."</button>
                  </td>
                </tr>";
    }

    if ($html == "") {
      $html = "
        <tr>
          <td class='text-muted'>Sem registos</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      ";
    }

    return $html;
  }

  public function adicionaServicoAoPt($idProf, $idServico){
    global $conn;

    if ($idProf <= 0 || $idServico <= 0) {
      return ["flag"=>false, "msg"=>"Dados inválidos."];
    }

    $sql = "INSERT INTO profissional_servico (codigo_rh, id_servico, ativo)
            VALUES ($idProf, $idServico, 1)
            ON DUPLICATE KEY UPDATE ativo=1";

    if ($conn->query($sql) === TRUE) {
      return ["flag"=>true, "msg"=>"Serviço adicionado aos seus serviços."];
    }
    return ["flag"=>false, "msg"=>"Erro ao adicionar serviço."];
  }

  public function toggleServicoPt($idProf, $idServico){
    global $conn;

    $sql = "UPDATE profissional_servico
            SET ativo = IF(ativo=1, 0, 1)
            WHERE codigo_rh=$idProf AND id_servico=$idServico";

    if ($conn->query($sql) === TRUE) {
      return ["flag"=>true, "msg"=>"Estado atualizado."];
    }
    return ["flag"=>false, "msg"=>"Erro ao atualizar estado."];
  }

  public function getSelectTiposAulaGrupo(){
    global $conn;
    $html = "<option value=''>Selecione...</option>";

    $sql = "SELECT id, nome 
            FROM tipo_aula_grupo
            ORDER BY nome ASC";
    $res = $conn->query($sql);

    while($r = $res->fetch_assoc()){
        $html .= "<option value='".$r['id']."'>".$r['nome']."</option>";
    }

    return $html;
  }

  public function getListaTiposAulaPt($codigo_rh, $estado = -1){
      global $conn;
      $html = "";

      $estado = intval($estado);
      $filtroEstado = "";
      if ($estado === 0 || $estado === 1) {
        $filtroEstado = " AND ptag.ativo = ".$estado." ";
      }

      $sql = "SELECT t.id, t.nome, ptag.ativo
            FROM profissional_tipo_aula_grupo ptag
            INNER JOIN tipo_aula_grupo t ON t.id = ptag.id_tipo_aula_grupo
            WHERE ptag.codigo_rh = ".$codigo_rh."
            ".$filtroEstado."
            ORDER BY t.nome ASC";

      $res = $conn->query($sql);

      while($r = $res->fetch_assoc()){
          $ativo = (int)($r['ativo'] ?? 0);

          $badgeClass = $ativo === 1 ? "bg-success" : "bg-danger";
          $badgeTxt   = $ativo === 1 ? "Ativo" : "Inativo";
          $badge      = "<span class='badge $badgeClass'>$badgeTxt</span>";

          $btnClass = $ativo === 1 ? "btn-outline-danger" : "btn-outline-success";
          $btnTxt   = $ativo === 1 ? "Desativar" : "Ativar";

          $html .= "<tr>
                      <td>".$r['nome']."</td>
                      <td>".$badge."</td>
                      <td class='text-end'>
                        <button class='btn btn-sm ".$btnClass."'
                          onclick='toggleTipoAulaPt(".$r['id'].")'>".$btnTxt."</button>
                      </td>
                    </tr>";   
      }

      if ($html == "") {
        $html = "
          <tr>
            <td class='text-muted'>Sem registos</td>
            <td></td>
            <td></td>
          </tr>
        ";
      }
    return $html;
  }

  public function adicionaTipoAulaAoPt($codigo_rh, $idTipo){
      global $conn;

      if ($codigo_rh == "" || $idTipo == "") {
          return json_encode(["flag"=>false, "msg"=>"Selecione um tipo de aula."]);
      }

      $sql = "INSERT INTO profissional_tipo_aula_grupo (codigo_rh, id_tipo_aula_grupo, ativo)
              VALUES (".$codigo_rh.", ".$idTipo.", 1)
              ON DUPLICATE KEY UPDATE ativo=1";

      if ($conn->query($sql) === TRUE) {
          return json_encode(["flag"=>true, "msg"=>"Tipo de aula adicionado."]);
      }
      return json_encode(["flag"=>false, "msg"=>"Erro ao adicionar tipo de aula."]);
  }

  public function toggleTipoAulaAoPt($codigo_rh, $idTipo){
      global $conn;

      $sql = "UPDATE profissional_tipo_aula_grupo
              SET ativo = IF(ativo=1, 0, 1)
              WHERE codigo_rh = ".$codigo_rh." AND id_tipo_aula_grupo = ".$idTipo;

      if ($conn->query($sql) === TRUE) {
          return json_encode(["flag"=>true, "msg"=>"Estado atualizado."]);
      }
      return json_encode(["flag"=>false, "msg"=>"Erro ao atualizar estado."]);
  }
}
