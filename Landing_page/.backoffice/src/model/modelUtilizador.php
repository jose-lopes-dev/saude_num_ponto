<?php
require_once 'connection.php';

class ModelUtilizador {

    // Registar novo cliente
    function registar($nome, $email, $password) {
        global $conn;

        // Verifica se já existe
        $stmt = $conn->prepare("SELECT id FROM utilizador WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            return json_encode(["flag"=>false, "msg"=>"Email já registado!"]);
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $tipo = 'cliente';
        $estado = 'ativo';

        $stmt = $conn->prepare("INSERT INTO utilizador (nome, email, password, tipo, estado) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nome, $email, $hash, $tipo, $estado);
        if ($stmt->execute()) {
            return json_encode(["flag"=>true, "msg"=>"Registo efetuado com sucesso!"]);
        } else {
            return json_encode(["flag"=>false, "msg"=>"Erro ao registar: ".$conn->error]);
        }
    }

    // Login para qualquer tipo
    function login($email, $password) {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM utilizador WHERE email=? AND estado='ativo'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows == 0) {
            return json_encode(["flag"=>false, "msg"=>"Email não encontrado ou conta inativa."]);
        }

        $user = $res->fetch_assoc();
        if (!password_verify($password, $user['password'])) {
            return json_encode(["flag"=>false, "msg"=>"Password incorreta."]);
        }

        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nome'] = $user['nome'];
        $_SESSION['user_tipo'] = $user['tipo'];

        return json_encode([
            "flag"=>true,
            "msg"=>"Login efetuado com sucesso!",
            "tipo"=>$user['tipo']
        ]);
    }
}
?>
