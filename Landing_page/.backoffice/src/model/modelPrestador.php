<?php
require_once 'connection.php';

class Prestador {

   function registaPrestador($username, $email, $password, $nome_completo, $nif, $contacto, $id_funcao, $qualificacao, $experiencia_anos, $id_tipo_contrato, $id_estado, $link, $recibo, $id_tipo_user)
{
    global $conn;
    $flag = true;
    $msg = "";

   // Gerar senha temporária aleatória 
    $password_temp = strtoupper(substr(md5(time() . rand()), 0, 8)); // Ex: "A7B9C3D1"
    $pw_hash = password_hash($password_temp, PASSWORD_BCRYPT);

    
    $sqlUser = "INSERT INTO utilizador (username, email, password, id_tipo_user, foto, data_registo) 
            VALUES ('$username', '$email', '$pw_hash', '$id_tipo_user', NULL, NOW())";

    if ($conn->query($sqlUser) !== TRUE) {
        $flag = false;
        $msg = "Erro ao criar conta de utilizador: " . $conn->error;
        return json_encode(["flag" => $flag, "msg" => $msg]);
    }

    $id_utilizador = $conn->insert_id;

    
    $resp = $this->uploads($_FILES);
    $resp = json_decode($resp, true);

    if ($resp['flag']) {
        $contratoPath = $resp['target'];
        $sqlRh = "INSERT INTO rh (id_utilizador, nome_completo, nif, contacto, id_funcao, qualificacao, experiencia_anos, id_tipo_contrato, id_estado, contrato, recibo, data_contratacao)
                  VALUES ('$id_utilizador', '$nome_completo', '$nif', '$contacto', '$id_funcao', '$qualificacao', '$experiencia_anos', '$id_tipo_contrato', '$id_estado', '$contratoPath', '$recibo', NOW())";
    } else {
        $sqlRh = "INSERT INTO rh (id_utilizador, nome_completo, nif, contacto, id_funcao, qualificacao, experiencia_anos, id_tipo_contrato, id_estado, recibo, data_contratacao)
                  VALUES ('$id_utilizador', '$nome_completo', '$nif', '$contacto', '$id_funcao', '$qualificacao', '$experiencia_anos', '$id_tipo_contrato', '$id_estado', '$recibo', NOW())";
    }

    if ($conn->query($sqlRh) !== TRUE) {
        $flag = false;
        $msg = "Erro ao registar dados profissionais: " . $conn->error;
    } else {
        $msg = "Prestador registado com sucesso!";

        // Enviar email de acesso com senha temporária
        require_once __DIR__ . '/../config/mail_config.php';
        $emailResp = enviarEmailAcesso($email, $nome_completo, $username, $password_temp, $id_tipo_user);

        if (!$emailResp['flag']) {
            $msg .= " (mas houve um problema ao enviar o email)";
        }
    }

    return json_encode(["flag" => $flag, "msg" => $msg]);
}




    function getListaPrestadores() {
        global $conn;
        $msg = "";

        $sql = "SELECT rh.*, funcao.descricao AS funcaoPrestador, tipo_contrato.descricao AS tipoContrato, estado.descricao AS estadoPrestador 
                FROM rh 
                JOIN funcao ON rh.id_funcao = funcao.id 
                JOIN tipo_contrato ON rh.id_tipo_contrato = tipo_contrato.id 
                JOIN estado ON rh.id_estado = estado.id";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            $msg .= "<tr>
                        <td>".$row['nome_completo']."</td>
                        <td>".$row['nif']."</td>
                        <td>".$row['funcaoPrestador']."</td>
                        <td>".$row['tipoContrato']."</td>
                        <td>".$row['estadoPrestador']."</td>
                        <td><a href='".$row['contrato']."' target='_blank' class='btn btn-info btn-sm'>Ver</a></td>
                       <td>
  ".(!empty($row['recibo']) 
    ? "<a href='".$row['recibo']."' target='_blank' class='btn btn-info btn-sm me-1'>Ver</a>
       <button class='btn btn-warning btn-sm' onclick='abrirModalRecibo(".$row['codigo'].")'>Atualizar</button>"
    : "<button class='btn btn-secondary btn-sm' onclick='abrirModalRecibo(".$row['codigo'].")'>Upload</button>")."
</td>

                       <td>
    <button class='btn btn-success btn-sm' onclick='getDadosPrestador(".$row['codigo'].")'>
        <i class='ri-pencil-line'></i>
    </button>
</td>
<td>
    <button class='btn btn-danger btn-sm' onclick='removerPrestador(".$row['codigo'].")'>
        <i class='ri-delete-bin-line'></i>
    </button>
</td>

                     </tr>";
        }

        return $msg;
    }

    function removerPrestador($codigo) {
        global $conn;
        $flag = true; 
        $msg = "";

        $sql = "DELETE FROM rh WHERE codigo = '$codigo'";
        if ($conn->query($sql) !== TRUE) {
            $flag = false;
            $msg = "Erro: ".$conn->error;
        } else {
            $msg = "Removido com sucesso!";
        }

        return json_encode(["flag" => $flag, "msg" => $msg]);
    }

    function getDadosPrestador($codigo) {
        global $conn;
        $sql = "SELECT * FROM rh WHERE codigo = '$codigo'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return json_encode($row);
    }

    function guardaEditPrestador($codigo, $nome, $nif, $funcao, $tipo, $email, $estado, $link, $codigoOld, $recibo) {
        global $conn;
        $flag = true; $msg = "";

        $resp = $this->uploads($link);
        $resp = json_decode($resp, true);

        if ($resp['flag']) {
            $sql = "UPDATE rh SET codigo='$codigo', nome='$nome', nif='$nif', id_funcao='$funcao', id_tipo_contrato='$tipo', email='$email', id_estado='$estado', contrato='".$resp['target']."', recibo='$recibo'
                    WHERE codigo='$codigoOld'";
        } else {
            $sql = "UPDATE rh SET codigo='$codigo', nome='$nome', nif='$nif', id_funcao='$funcao', id_tipo_contrato='$tipo', email='$email', id_estado='$estado', recibo='$recibo'
                    WHERE codigo='$codigoOld'";
        }

        if ($conn->query($sql) !== TRUE) {
            $flag = false;
            $msg = "Erro: ".$conn->error;
        } else {
            $msg = "Editado com sucesso!";
        }

        return json_encode(["flag" => $flag, "msg" => $msg]);
    }

    function getFuncao() {
        global $conn;
        $msg = "";
        $sql = "SELECT * FROM funcao";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $msg .= "<option value='".$row['id']."'>".$row['descricao']."</option>";
        }
        return $msg;
    }

    function getTipo() {
        global $conn;
        $msg = "";
        $sql = "SELECT * FROM tipo_contrato";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $msg .= "<option value='".$row['id']."'>".$row['descricao']."</option>";
        }
        return $msg;
    }

    function getEstado() {
        global $conn;
        $msg = "";
        $sql = "SELECT * FROM estado";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $msg .= "<option value='".$row['id']."'>".$row['descricao']."</option>";
        }
        return $msg;
    }

    function uploads($file) {
        $uploadDir = "../uploads/";
        $targetFile = "";
        $flag = true; $msg = "";

        if (isset($file['contrato']) && $file['contrato']['error'] == 0) {
    $fileName = time()."_".basename($file['contrato']['name']);
    $targetFile = $uploadDir.$fileName;

    if (move_uploaded_file($file['contrato']['tmp_name'], $targetFile)) {
        $msg = "Ficheiro carregado com sucesso!";
        $targetFile = "src/uploads/".$fileName;
    } else {
        $flag = false;
        $msg = "Erro ao carregar o ficheiro.";
    }
} else {
    $flag = false;
    $msg = "Nenhum ficheiro selecionado.";
}


        return json_encode(["flag"=>$flag, "msg"=>$msg, "target"=>$targetFile]);
    }

    function uploadRecibo($codigo, $file){
    global $conn;
    $uploadDir = "../uploads/recibos/";

    if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    // Buscar recibo antigo para apagar se existir
    $res = $conn->query("SELECT recibo FROM rh WHERE codigo=".$codigo);
    $reciboAntigo = null;
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if (!empty($row['recibo'])) {
            $reciboAntigo = $row['recibo'];
        }
    }

    if(isset($file['recibo']) && $file['recibo']['error'] == 0){
        $fileName = time() . "_" . basename($file['recibo']['name']);
        $targetFile = $uploadDir . $fileName;

        if(move_uploaded_file($file['recibo']['tmp_name'], $targetFile)){

            // Apaga recibo antigo se existir
            if ($reciboAntigo) {
                $oldPath = $uploadDir . basename($reciboAntigo);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Guardar na BD o caminho relativo (igual ao contrato)
            $targetPath = "src/uploads/recibos/".$fileName;
            $sql = "UPDATE rh SET recibo='".$targetPath."' WHERE codigo=".$codigo;
            if($conn->query($sql)){
                // Envia email com o novo recibo
                $this->enviarReciboPorEmail($codigo, $targetFile);
                return ["flag"=>true, "msg"=>"Recibo enviado/atualizado com sucesso!"];
            } else {
                return ["flag"=>false, "msg"=>"Erro ao atualizar BD: ".$conn->error];
            }
        } else {
            return ["flag"=>false, "msg"=>"Erro ao mover o ficheiro para o destino final."];
        }
    }
    return ["flag"=>false, "msg"=>"Nenhum ficheiro enviado."];
}

private function enviarReciboPorEmail($codigo, $ficheiro){
    global $conn;
    $res = $conn->query("SELECT email, nome FROM rh WHERE codigo=".$codigo);
    if($res && $res->num_rows > 0){
        $row = $res->fetch_assoc();
        $to = $row['email'];
        $subject = "Novo Recibo Disponível";
        $message = "Olá ".$row['nome'].",<br><br>O seu recibo está disponível em anexo.<br><br>Atenciosamente,<br>RH";

  
    }
}

function getListaImpostos() {
    global $conn;
    $msg = "";

    $sql = "SELECT * FROM imposto ORDER BY id DESC";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {

        $msg .= "<tr>
                    <td>".$row['id']."</td>
                    <td>".$row['mes']."</td>

                    <td>".
                        (!empty($row['dmr']) 
                            ? "<a href='".$row['dmr']."' target='_blank' class='btn btn-info btn-sm me-1'>Ver</a>
                               <button class='btn btn-warning btn-sm' onclick='abrirModalDMR(".$row['id'].")'>Atualizar</button>"
                            : "<button class='btn btn-secondary btn-sm' onclick='abrirModalDMR(".$row['id'].")'>Upload</button>"
                        )
                    ."</td>

                    <td>".
                        (!empty($row['dri']) 
                            ? "<a href='".$row['dri']."' target='_blank' class='btn btn-info btn-sm me-1'>Ver</a>
                               <button class='btn btn-warning btn-sm' onclick='abrirModalDRI(".$row['id'].")'>Atualizar</button>"
                            : "<button class='btn btn-secondary btn-sm' onclick='abrirModalDRI(".$row['id'].")'>Upload</button>"
                        )
                    ."</td>

                    <td>".$row['data_criacao']."</td>
                </tr>";
    }

    return $msg;
}

function uploadDMR($id, $file) {
    global $conn;

    // Diretório real do servidor (fora do src)
   $uploadDir = __DIR__ . '/../../uploads/impostos/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    // Buscar o DMR antigo
    $res = $conn->query("SELECT dmr FROM imposto WHERE id = " . intval($id));
    $dmrAntigo = null;
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if (!empty($row['dmr'])) {
            $dmrAntigo = basename($row['dmr']);
        }
    }

    // Atenção: o nome no <input> é "DMR", e o PHP é case-sensitive
    if (isset($file['dmr']) && $file['dmr']['error'] == 0) {
        $fileName = time() . "_" . basename($file['dmr']['name']);
        $targetFile = $uploadDir . $fileName;

        // Faz o upload físico
        if (move_uploaded_file($file['dmr']['tmp_name'], $targetFile)) {

            // Remove o antigo, se existir
            if ($dmrAntigo && file_exists($uploadDir . $dmrAntigo)) {
                unlink($uploadDir . $dmrAntigo);
            }

            // Caminho relativo (para usar no href "Ver")
            $targetPath = "uploads/impostos/" . $fileName;

            $sql = "UPDATE imposto SET dmr = '".$conn->real_escape_string($targetPath)."' WHERE id = " . intval($id);
            if ($conn->query($sql)) {
                return ["flag" => true, "msg" => "DMR enviado/atualizado com sucesso!"];
            } else {
                return ["flag" => false, "msg" => "Erro ao atualizar BD: " . $conn->error];
            }

        } else {
            return ["flag" => false, "msg" => "Erro ao mover o ficheiro para o destino final."];
        }

    } else {
        return ["flag" => false, "msg" => "Nenhum ficheiro enviado ou erro no upload."];
    }

}

function uploadDRI($id, $file) {
    global $conn;

    // Diretório real do servidor (fora do src)
   $uploadDir = __DIR__ . '/../../uploads/impostos/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    // Buscar o DRI antigo
    $res = $conn->query("SELECT dri FROM imposto WHERE id = " . intval($id));
    $driAntigo = null;
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if (!empty($row['dri'])) {
            $driAntigo = basename($row['dri']);
        }
    }

    // Atenção: o nome no <input> é "DRI", e o PHP é case-sensitive
    if (isset($file['dri']) && $file['dri']['error'] == 0) {
        $fileName = time() . "_" . basename($file['dri']['name']);
        $targetFile = $uploadDir . $fileName;

        // Faz o upload físico
        if (move_uploaded_file($file['dri']['tmp_name'], $targetFile)) {

            // Remove o antigo, se existir
            if ($driAntigo && file_exists($uploadDir . $driAntigo)) {
                unlink($uploadDir . $driAntigo);
            }

            // Caminho relativo (para usar no href "Ver")
            $targetPath = "uploads/impostos/" . $fileName;

            $sql = "UPDATE imposto SET dri = '".$conn->real_escape_string($targetPath)."' WHERE id = " . intval($id);
            if ($conn->query($sql)) {
                return ["flag" => true, "msg" => "DRI enviado/atualizado com sucesso!"];
            } else {
                return ["flag" => false, "msg" => "Erro ao atualizar BD: " . $conn->error];
            }

        } else {
            return ["flag" => false, "msg" => "Erro ao mover o ficheiro para o destino final."];
        }

    } else {
        return ["flag" => false, "msg" => "Nenhum ficheiro enviado ou erro no upload."];
    }

}


}

?>
