<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache, must-revalidate");

require_once "../model/connection.php";
require_once "../model/modelLogin.php";

$log = new Login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["flag" => false, "msg" => "Método inválido."]);
    exit;
}

if (!isset($_POST["op"])) {
    echo json_encode(["flag" => false, "msg" => "Operação inválida."]);
    exit;
}

$op = intval($_POST["op"]);

switch ($op) {

    // 1 - REGISTAR UTILIZADOR
case 1:
    $res = $log->registaUser(
        $_POST['username'] ?? '',
        $_POST['nome'] ?? '',
        $_POST['telefone'] ?? '',
        $_POST['email'] ?? '',
        $_POST['dataNascimento'] ?? '',
        $_POST['nif'] ?? '',
        $_POST['password'] ?? ''
    );

    echo json_encode($res);
    break;


    // 2 - LOGIN
    case 2:
        echo $log->login(
            $_POST['username'] ?? '',
            $_POST['password'] ?? ''
        );
        break;

    // 3 - LOGOUT
    case 3:
        echo $log->logout();
        break;

    // 6 - RECUPERAR PASSWORD
case 6:
    $email = $_POST['email'] ?? '';
    $novaPassword = $_POST['novaPassword'] ?? '';

    if (!$email || !$novaPassword) {
        echo json_encode(["flag" => false, "msg" => "Dados inválidos"]);
        exit;
    }

    $res = $log->recuperarPassword($email, $novaPassword);
    echo json_encode($res);
    break;



    // 7 - COMPLETAR PERFIL (página antiga)
    case 7:
        echo $log->completarPerfil(
            $_POST['idade'] ?? '',
            $_POST['peso'] ?? '',
            $_POST['altura'] ?? '',
            $_POST['objetivo'] ?? ''
        );
        break;

    default:
        echo json_encode(["flag" => false, "msg" => "Operação inválida."]);
}
