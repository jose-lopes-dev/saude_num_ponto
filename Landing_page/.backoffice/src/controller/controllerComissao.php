<?php
require_once '../model/modelComissao.php';
$com = new ModelComissao();

if ($_POST['op'] == 'getData') {
    echo json_encode([
        "lista" => $com->getListaComissoes(),
        "graficos" => $com->getDadosGraficos()
    ]);
} elseif ($_POST['op'] == 'getSalarios') {
    echo json_encode([
        "listaS" => $com->getListaSalarios()
    ]);
} elseif ($_POST['op'] == 'marcarPago') {
    echo json_encode($com->marcarComoPago($_POST['id']));
} elseif ($_POST['op'] == 'marcarSalarioPago') {
    echo json_encode($com->marcarSalarioComoPago($_POST['id']));
}
?>
