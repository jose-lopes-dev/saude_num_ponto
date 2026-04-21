<?php
require_once "../model/modelProduto.php";

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

$prod = new Produto();

$op = (int)$_POST['op'];

if($op == 1){

    $resp = $prod->registarProduto(
        $_POST['nome'] ?? '',
        $_POST['preco'] ?? 0,
        $_POST['descricao'] ?? '',
        $_POST['stock'] ?? 0,
        $_POST['id_parceiro'] ?? 0,
        $_POST['id_estado'] ?? 0
    );
    echo($resp);

}else if($op == 2){

    $resp = $prod->getListaProdutosAdmin();
    echo($resp);

}else if($op == 3){

    $resp = $prod->removerProduto($_POST['id'] ?? 0);
    echo($resp);

}else if($op == 4){

    $resp = $prod->getDadosProduto($_POST['id'] ?? 0);
    echo($resp);

}else if($op == 5){

    $resp = $prod->guardaEditProduto(
        $_POST['id'] ?? 0,
        $_POST['nome'] ?? '',
        $_POST['preco'] ?? 0,
        $_POST['descricao'] ?? '',
        $_POST['stock'] ?? 0,
        $_POST['id_parceiro'] ?? 0,
        $_POST['id_estado'] ?? 0
    );
    echo($resp);

}else if($op == 6){

    $resp = $prod->getSelectParceiros();
    echo($resp);

}else if($op == 7){

    $resp = $prod->getSelectEstados();
    echo($resp);

}else if($op == 8){

    $resp = $prod->getListaProdutosMarketplace();
    echo($resp);

}else if($op == 9){

    $resp = $prod->processarPagamentoSquare(
        $_POST['nonce'] ?? '',
        $_POST['items'] ?? '[]',
        $user_id,
        $_POST['subtotal'] ?? null,
        $_POST['desconto'] ?? null,
        $_POST['iva'] ?? null,
        $_POST['amount'] ?? null,
        $_POST['nome'] ?? '',
        $_POST['email'] ?? '',
        $_POST['nif'] ?? '',
        $_POST['telefone'] ?? '',
        (int)($_POST['alt_dados'] ?? 0),
        (int)($_POST['termos'] ?? 0),
        $_POST['cupao_codigo'] ?? ''
    );
    echo($resp);

}else if($op == 10){

    $resp = $prod->getParceirosMarketplace();
    echo($resp);

}else if($op == 11){

    $resp = $prod->validarCupao($_POST['codigo'] ?? '');
    echo($resp);

}else if($op == 12){

    $resp = $prod->getDadosComprador($user_id);
    echo($resp);

}else{
    echo json_encode(array("flag"=>false,"msg"=>"Operação inválida"));

}