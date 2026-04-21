<?php
require_once '../model/modelServicos_pt.php';

session_start();

header('Content-Type: application/json; charset=utf-8');

$codigo_rh = isset($_SESSION['rh_codigo']) ? (int)$_SESSION['rh_codigo'] : 0;
if ($codigo_rh <= 0) {
    echo json_encode(['ok'=>false,'msg'=>'Prestador não encontrado']);
    exit;
}

if (!isset($_POST['op'])) {
    echo json_encode(['flag'=>false,'msg'=>'op em falta']);
    exit;
}

$estado = isset($_POST['estado']) ? intval($_POST['estado']) : -1;

$plano = new PlanosServicosPt();

    if ($_POST['op'] == 1) {
        $res = $plano->getListaPlanosSistema();
        echo($res);

    } else if ($_POST['op'] == 2) {
        $res = $plano->getSelectServicosCatalogo();
        echo($res);

    } else if ($_POST['op'] == 3) {
        $estado = -1;
        if (isset($_POST['estado'])) {
            $estado = intval($_POST['estado']);
        }
        $res = $plano->getListaMeusServicos($codigo_rh, $estado);
        echo($res);

    } else if ($_POST['op'] == 4) {

        $res = $plano->adicionaServicoAoPt($codigo_rh, $_POST['idServico']);

        echo json_encode($res);

    } else if ($_POST['op'] == 5) {

        $res = $plano->toggleServicoPt($codigo_rh, $_POST['idServico']);

        echo json_encode($res);

    }else if ($_POST['op'] == 7) {
        $res = $plano->getSelectTiposAulaGrupo();
        echo($res);
    
    } else if ($_POST['op'] == 8) {
        $estado = -1;
        if (isset($_POST['estado'])) {
            $estado = intval($_POST['estado']);
        }
        $res = $plano->getListaTiposAulaPt($codigo_rh, $estado);

        echo($res);

    } else if ($_POST['op'] == 9) {

        $res = $plano->adicionaTipoAulaAoPt($codigo_rh, $_POST['idTipo']);

        echo($res);

    } else if ($_POST['op'] == 10) {
        
        $res = $plano->toggleTipoAulaAoPt($codigo_rh, $_POST['idTipo']);

        echo($res);
    }
?>
