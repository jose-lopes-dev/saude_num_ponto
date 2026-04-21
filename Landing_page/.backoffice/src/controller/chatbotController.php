<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . "/../model/ChatbotModel.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["flag" => false, "msg" => "Método inválido"]);
    exit;
}

$model = new ChatbotModel();

/* ============================================================
   LISTAS DINÂMICAS (SEM MAP / SEM SWITCH)
============================================================ */

if (isset($_POST['op']) && $_POST['op'] == 10) {
    echo json_encode(["flag" => true, "data" => $model->getObjetivos()]);
    exit;
}

if (isset($_POST['op']) && $_POST['op'] == 11) {
    echo json_encode(["flag" => true, "data" => $model->getNiveis()]);
    exit;
}

if (isset($_POST['op']) && $_POST['op'] == 12) {
    echo json_encode(["flag" => true, "data" => $model->getAtividades()]);
    exit;
}

if (isset($_POST['op']) && $_POST['op'] == 13) {
    echo json_encode(["flag" => true, "data" => $model->getTiposCorpo()]);
    exit;
}

if (isset($_POST['op']) && $_POST['op'] == 14) {
    echo json_encode(["flag" => true, "data" => $model->getHabitosDiarios()]);
    exit;
}

if (isset($_POST['op']) && $_POST['op'] == 15) {
    echo json_encode(["flag" => true, "data" => $model->getAreasCorpo()]);
    exit;
}

if (isset($_POST['op']) && $_POST['op'] == 16) {
    echo json_encode(["flag" => true, "data" => $model->getTiposDieta()]);
    exit;
}

if (isset($_POST['op']) && $_POST['op'] == 17) {
    echo json_encode(["flag" => true, "data" => $model->getGeneros()]);
    exit;
}

if (isset($_POST['op']) && $_POST['op'] == 99) {
    echo json_encode(["flag" => true, "data" => $model->getCondicoes()]);
    exit;
}

/* ============================================================
   GUARDAR PERFIL (op = 8)
============================================================ */

if (isset($_POST['op']) && $_POST['op'] == 8) {

    $altura             = $_POST['altura'] ?? '';
    $peso               = $_POST['peso'] ?? '';
    $peso_pretendido    = $_POST['peso_pretendido'] ?? '';
    $id_objetivo        = $_POST['id_objetivo'] ?? 0;
    $id_nivel           = $_POST['id_nivel'] ?? 0;
    $id_atividades      = $_POST['id_atividades'] ?? 0;
    $id_tipo_corpo      = $_POST['id_tipo_corpo'] ?? 0;
    $id_habito_diario   = $_POST['id_habito_diario'] ?? 0;
    $id_area_corpo      = $_POST['id_area_corpo'] ?? 0;
    $id_tipo_dieta      = $_POST['id_tipo_dieta'] ?? 0;

    $genero             = $_POST['genero'] ?? '';
    $id_condicao_saude  = $_POST['id_condicao_saude'] ?? null;

    $result = $model->saveProfile(
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
    );

    echo json_encode($result);
    exit;
}

/* ============================================================
   OP INVALIDA
============================================================ */

echo json_encode(["flag" => false, "msg" => "Operação inválida"]);
exit;

?>
