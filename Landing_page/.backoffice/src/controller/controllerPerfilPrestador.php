<?php
require_once __DIR__ . '/../model/ModelPerfilPrestador.php';

session_start();
header("Content-Type: application/json");

if (empty($_SESSION["id"])) {
    echo json_encode(["flag" => false, "msg" => "Não autenticado"]);
    exit;
}

$model = new ModelPerfilPrestador();
$op = intval($_POST["op"] ?? 0);

/* =====================
   OBTER PERFIL
===================== */
if ($op === 1) {
    echo json_encode([
        "flag" => true,
        "dados" => $model->getPerfil($_SESSION["id"])
    ]);
    exit;
}

/* =====================
   GUARDAR PERFIL
===================== */
if ($op === 2) {

    $fotoPath = null;

    if (!empty($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {

        $ext = strtolower(pathinfo($_FILES["foto"]["name"], PATHINFO_EXTENSION));
        $permitidas = ["jpg", "jpeg", "png", "webp"];

        if (!in_array($ext, $permitidas)) {
            echo json_encode(["flag" => false, "msg" => "Formato de imagem inválido"]);
            exit;
        }

        // ✅ PASTA REAL DO TEU PROJETO
        $pasta = __DIR__ . "/../../src/uploads/perfis/";
        if (!is_dir($pasta)) {
            mkdir($pasta, 0777, true);
        }

        $nome = "perfil_" . $_SESSION["id"] . "." . $ext;
        $destino = $pasta . $nome;

        if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $destino)) {
            echo json_encode(["flag" => false, "msg" => "Erro ao guardar imagem"]);
            exit;
        }

        // ✅ CAMINHO QUE VAI PARA A BD
        $fotoPath = "/Projeto_Final_AIO/Landing_page/.backoffice/src/uploads/perfis/" . $nome;
    }

    $res = $model->guardarPerfil($_SESSION["id"], $_POST, $fotoPath);

    if ($res === true) {
        echo json_encode(["flag" => true, "msg" => "Perfil atualizado com sucesso"]);
    } else {
        echo json_encode([
            "flag" => false,
            "msg"  => $res ?: "Erro ao atualizar perfil"
        ]);
    }
    exit;
}

echo json_encode(["flag" => false, "msg" => "Operação inválida"]);
