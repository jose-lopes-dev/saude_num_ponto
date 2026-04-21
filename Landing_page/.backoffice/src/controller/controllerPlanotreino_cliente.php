<?php
require_once "../model/modelPlanotreino_cliente.php";

session_start();
header('Content-Type: application/json; charset=utf-8');

$user_id = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;
if ($user_id <= 0) {
  echo json_encode(['flag'=>false,'msg'=>'Sessão inválida']);
  exit;
}

if (!isset($_POST['op'])) {
    echo json_encode(['flag'=>false, 'msg'=>'op em falta']);
    exit;
}

$pl = new PlanoTreinoCliente();

// op 1 - listagem de exercícios (select2)
if($_POST['op'] == 1){

    $q = $_POST['q'] ?? '';
    $grupo = $_POST['grupo'] ?? '';
    $equip = $_POST['equipamento'] ?? '';

    $resp = $pl->listarExercicios($q, $grupo, $equip);
    echo($resp);

// op 2 - criar plano
}else if($_POST['op'] == 2){

    $titulo = $_POST['titulo'] ?? '';

    $resp = $pl->criarPlano($user_id, $titulo);
    echo($resp);

// op 3 - guardar dias/exercícios
}else if($_POST['op'] == 3){

    $plano_id = isset($_POST['plano_id']) ? (int)$_POST['plano_id'] : 0;
    $dias = $_POST['dias'] ?? '{}';

    $resp = $pl->guardarDias($user_id, $plano_id, $dias);
    echo($resp);

// op 4 - listar planos do cliente
}else if($_POST['op'] == 4){

    $resp = $pl->listarPlanos($user_id);
    echo($resp);

// op 5 - detalhe de plano
}else if($_POST['op'] == 5){

    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    $resp = $pl->detalhePlano($user_id, $id);
    echo($resp);

// op 6 - remover plano
}else if($_POST['op'] == 6){

    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    $resp = $pl->removerPlano($user_id, $id);
    echo($resp);

// op 7 - listar ficheiros recebidos do PT
}else if($_POST['op'] == 7){

    $resp = $pl->listarFicheirosPT($user_id);
    echo($resp);

// op 8 - listar planos recebidos do PT
}else if($_POST['op'] == 8){

    $resp = $pl->listarPlanosPT($user_id);
    echo($resp);

// op 9 - detalhe de plano do PT
}else if($_POST['op'] == 9){

    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $resp = $pl->detalhePlanoPT($user_id, $id);
    echo($resp);

}else{
    echo json_encode(['flag'=>false,'msg'=>'Operação inválida.']);
    exit;
}
?>
