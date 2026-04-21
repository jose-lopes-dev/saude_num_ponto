<?php
require_once __DIR__ . '/../model/modelRendimentos.php';
$rend = new Rendimentos();

if (!isset($_POST['op'])) {
    echo json_encode(["erro" => "Requisição inválida"]);
    exit;
}

if ($_POST['op'] == 'graficoTrimestral') {
    $trimestre = (int)$_POST['trimestre'];
    echo $rend->graficoTrimestral($trimestre);

} else {
    echo json_encode(["erro" => "Operação inválida"]);
}
?>
