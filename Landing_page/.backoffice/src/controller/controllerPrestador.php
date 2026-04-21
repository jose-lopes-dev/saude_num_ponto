<?php
require_once '../model/modelPrestador.php';
$rh = new Prestador();

if ($_POST['op'] == 1) {
    $resp = $rh->registaPrestador(
        $_POST['username'],
        $_POST['email'],
        null, // não enviamos senha
        $_POST['nome_completo'],
        $_POST['nif'],
        $_POST['contacto'],
        $_POST['id_funcao'],
        $_POST['qualificacao'],
        $_POST['experiencia_anos'],
        $_POST['id_tipo_contrato'],
        $_POST['id_estado'],
        $_FILES,               
        $_POST['recibo'],   
        $_POST['id_tipo_user']                 
    );

    echo $resp;
}
 else if ($_POST['op'] == 2) {
    echo $rh->getListaPrestadores();

} else if ($_POST['op'] == 3) {
    echo $rh->removerPrestador($_POST['codigo']);

} else if ($_POST['op'] == 4) {
    echo $rh->getDadosPrestador($_POST['codigo']);

} else if ($_POST['op'] == 5) {
    $resp = $rh->guardaEditPrestador(
        $_POST['codigo'],
        $_POST['nome'],
        $_POST['nif'],
        $_POST['funcao'],
        $_POST['tipo'],
        $_POST['email'],
        $_POST['estado'],
        $_FILES,              
        $_POST['codigoOld'],
        $_POST['recibo']   
    );
    echo $resp;

} else if ($_POST['op'] == 6) {
    echo $rh->getFuncao();

} else if ($_POST['op'] == 7) {
    echo $rh->getTipo();

} else if ($_POST['op'] == 8) {
    echo $rh->getEstado();

} else if ($_POST['op'] == 9) {
    echo $rh->getListaImpostos();
    
}else if ($_POST['op'] == 'uploadRecibo') {
    $codigo = intval($_POST['codigo']);
    $resp = $rh->uploadRecibo($codigo, $_FILES);
    echo json_encode($resp);

}else if ($_POST['op'] == 'uploadDMR') {
    $id = intval($_POST['id']);
    $resp = $rh->uploadDMR($id, $_FILES);
    echo json_encode($resp);

}else if ($_POST['op'] == 'uploadDRI') {
    $id = intval($_POST['id']);
    $resp = $rh->uploadDRI($id, $_FILES);
    echo json_encode($resp);
}

?>
