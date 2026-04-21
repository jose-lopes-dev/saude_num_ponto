<?php
require_once 'connection.php';

class Login {

    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    private function resposta($flag, $msg, $extra = []) {
        return json_encode(array_merge(["flag" => $flag, "msg" => $msg], $extra));
    }

    /* ============================
       REGISTAR UTILIZADOR
       Parâmetros: username, nome, telefone, email, dataNascimento, nif, pw
    ============================ */
    public function registaUser($username, $nome, $telefone, $email, $dataNascimento, $nif, $pw) {

        $username = trim(strtolower($username));
        $nome = trim($nome);
        $telefone = trim($telefone);
        $email = trim(strtolower($email));
        $dataNascimento = trim($dataNascimento);
        $nif = trim($nif);
        $pw = trim($pw);

        if (!$username || !$nome || !$telefone || !$email || !$dataNascimento || !$nif || !$pw) {
            return $this->resposta(false, "Preenche todos os campos.");
        }

        if (!preg_match('/^[a-z0-9._-]{3,30}$/', $username)) {
            return $this->resposta(false, "Username inválido (3-30 caracteres, letras/números . _ -).");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->resposta(false, "Email inválido.");
        }

        // verificar username duplicado
        $sqlCheckUser = "SELECT id FROM utilizador WHERE username = ? LIMIT 1";
        $stmtUser = $this->conn->prepare($sqlCheckUser);
        if (!$stmtUser) return $this->resposta(false, "Erro interno (user check).");
        $stmtUser->bind_param("s", $username);
        $stmtUser->execute();
        $stmtUser->store_result();
        if ($stmtUser->num_rows > 0) {
            $stmtUser->close();
            return $this->resposta(false, "Este username já existe. Escolhe outro.");
        }
        $stmtUser->close();

        // verificar email duplicado
        $sqlCheck = "SELECT id FROM utilizador WHERE email = ? LIMIT 1";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        if (!$stmtCheck) return $this->resposta(false, "Erro interno (email check).");
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        $stmtCheck->store_result();
        if ($stmtCheck->num_rows > 0) {
            $stmtCheck->close();
            return $this->resposta(false, "Este email já está registado.");
        }
        $stmtCheck->close();

        $hash = password_hash($pw, PASSWORD_BCRYPT);

        $this->conn->begin_transaction();

        try {
            // inserir utilizador com username real
            $sqlU = "INSERT INTO utilizador (username, email, password, id_tipo_user, data_registo)
                     VALUES (?, ?, ?, 3, NOW())";
            $stmtU = $this->conn->prepare($sqlU);
            if (!$stmtU) throw new Exception("Erro ao preparar inserção do utilizador: " . $this->conn->error);
            $stmtU->bind_param("sss", $username, $email, $hash);
            $stmtU->execute();
            $idUser = $stmtU->insert_id;
            $stmtU->close();

            // verificar se já existe cliente para este utilizador (normalmente não)
            $sqlCheckC = "SELECT 1 FROM cliente WHERE id_utilizador = ? LIMIT 1";
            $stmtCheckC = $this->conn->prepare($sqlCheckC);
            if (!$stmtCheckC) throw new Exception("Erro ao preparar verificação cliente: " . $this->conn->error);
            $stmtCheckC->bind_param("i", $idUser);
            $stmtCheckC->execute();
            $stmtCheckC->store_result();

            if ($stmtCheckC->num_rows === 0) {
                $sqlC = "INSERT INTO cliente (id_utilizador, nome_completo, contacto, nif, data_nascimento, perfil_completo)
                         VALUES (?, ?, ?, ?, ?, 0)";
                $stmtC = $this->conn->prepare($sqlC);
                if (!$stmtC) throw new Exception("Erro ao preparar inserção cliente: " . $this->conn->error);
                $stmtC->bind_param("issss", $idUser, $nome, $telefone, $nif, $dataNascimento);
                $stmtC->execute();
                $stmtC->close();
            }
            $stmtCheckC->close();

            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollback();
            return $this->resposta(false, "Erro ao registar: " . $e->getMessage());
        }

  // auto-login após registo
$_SESSION['user'] = [
    "id" => $idUser,
    "username" => $username,
    "email" => $email,
    "tipo" => 3
];

// decidir para onde vai após registo
return [
    "flag" => true,
    "msg" => "Conta criada com sucesso!",
    "redirect" => "/Projeto_Final_AIO/Landing_page/.backoffice/chatbot.html"
];

    }       

    /* ============================
       LOGIN
    ============================ */
public function login($usernameOrEmail, $pw) {

    if (session_status() === PHP_SESSION_NONE) session_start();

    $usernameOrEmail = trim($usernameOrEmail);
    $pw = trim($pw);

    if (!$usernameOrEmail || !$pw) {
        return $this->resposta(false, "Preenche o email/username e a password.");
    }

    $sql = "SELECT u.id, u.username, u.email, u.password, u.id_tipo_user,
       COALESCE(c.nome_completo, u.username) AS nome,
       COALESCE(c.perfil_completo, 0) AS perfil_completo

            FROM utilizador u
            LEFT JOIN cliente c ON c.id_utilizador = u.id
            WHERE u.email = ? OR u.username = ?
            LIMIT 1";

    $stmt = $this->conn->prepare($sql);
    if (!$stmt) return $this->resposta(false, "Erro interno.");
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    if (!$row) return $this->resposta(false, "Credenciais inválidas.");
    if (!password_verify($pw, $row['password'])) return $this->resposta(false, "Credenciais inválidas.");

    if (session_status() === PHP_SESSION_NONE) session_start();

    $_SESSION['id']    = $row['id'];
    $_SESSION['email'] = $row['email'];
    $_SESSION['tipo']  = $row['id_tipo_user'];
    $_SESSION['nome']  = $row['nome'];

    $sqlR = "SELECT codigo FROM rh WHERE id_utilizador = ? LIMIT 1";
    $stmtR = $this->conn->prepare($sqlR);
    $stmtR->bind_param("i", $row['id']);
    $stmtR->execute();
    $resR = $stmtR->get_result();
    $rh = $resR->fetch_assoc();
    $stmtR->close();

    $_SESSION['rh_codigo'] = $rh['codigo'] ?? 0;

    if ($row['id_tipo_user'] == 3) {
        $sqlC = "SELECT codigo FROM cliente WHERE id_utilizador = ? LIMIT 1";
        $stmtC = $this->conn->prepare($sqlC);
        $stmtC->bind_param("i", $row['id']);
        $stmtC->execute();
        $resC = $stmtC->get_result();
        $cli = $resC->fetch_assoc();
        $stmtC->close();

        $_SESSION['cliente_id'] = $cli['codigo'] ?? 0;
    }

    if ($row['id_tipo_user'] == 3 && intval($row['perfil_completo']) === 0) {
        return $this->resposta(true, "Login efetuado!", [
            "redirect" => "/Projeto_Final_AIO/Landing_page/.backoffice/chatbot.html"
        ]);
    }

    $redirect = "../.backoffice/dashboard_cliente.php";
    if ($row['id_tipo_user'] == 1) $redirect = "../.backoffice/index.php";
    if ($row['id_tipo_user'] == 2) $redirect = "../.backoffice/dashboard_pt.php";
    if ($row['id_tipo_user'] == 4) $redirect = "../.backoffice/dashboard_nutricionista.php";
    if ($row['id_tipo_user'] == 5) $redirect = "../.backoffice/dashboard_psicologo.php";

    return $this->resposta(true, "Login efetuado!", ["redirect" => $redirect]);
}


    /* ============================
       LOGOUT
    ============================ */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        return $this->resposta(true, "Sessão terminada.");
    } 

    /* ============================
       RECUPERAR PASSWORD
    ============================ */
public function recuperarPassword($email, $novaPassword) {

    $email = trim(strtolower($email));
    $novaPassword = trim($novaPassword);

    if (!$email || !$novaPassword) {
        return $this->resposta(false, "Dados inválidos.");
    }

    $sql = "SELECT id FROM utilizador WHERE email = ? LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) return $this->resposta(false, "Erro interno.");

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        return $this->resposta(false, "Email não encontrado.");
    }

    $stmt->close();

    $hash = password_hash($novaPassword, PASSWORD_BCRYPT);

    $sql = "UPDATE utilizador SET password = ? WHERE email = ? LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) return $this->resposta(false, "Erro interno.");

    $stmt->bind_param("ss", $hash, $email);

    if ($stmt->execute()) {
        $stmt->close();
        return $this->resposta(true, "Password atualizada com sucesso!");
    }

    $stmt->close();
    return $this->resposta(false, "Erro ao atualizar password.");
}



    /* ============================
       COMPLETAR PERFIL
    ============================ */
    public function completarPerfil($idade, $peso, $altura, $objetivo) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id = $_SESSION['id'] ?? 0;
        if (!$id) return $this->resposta(false, "Sessão inválida.");

        $idade = trim($idade);
        $peso = trim(str_replace(",", ".", $peso));
        $altura = trim(str_replace(",", ".", $altura));
        $objetivo = trim($objetivo);

        if ($idade === "" || $peso === "" || $altura === "" || $objetivo === "") {
            return $this->resposta(false, "Preenche todos os campos.");
        }

        $sql = "UPDATE cliente SET idade = ?, peso = ?, altura = ?, objetivo = ?, perfil_completo = 1 WHERE id_utilizador = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return $this->resposta(false, "Erro interno.");
        $stmt->bind_param("isssi", $idade, $peso, $altura, $objetivo, $id);

        if (!$stmt->execute()) {
            $stmt->close();
            return $this->resposta(false, "Erro ao atualizar perfil: " . $stmt->error);
        }

        $stmt->close();
        return $this->resposta(true, "Perfil completado com sucesso!");
    }
}
