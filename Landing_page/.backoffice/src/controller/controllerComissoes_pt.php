<?php
require_once '../model/modelComissoes_pt.php';

$com = new Comissoes();

if($_POST['op'] == 1){
    echo($com->getListaComissoes($_POST['idPt'], $_POST['estado']));

}else if($_POST['op'] == 2){
    echo($com->marcarPago($_POST['id']));

}else if($_POST['op'] == 3){
    echo($com->getResumoComissoes($_POST['idPt'], $_POST['estado']));
    
}else if($_POST['op'] == 4){
    echo($com->syncComissoes($_POST['idPt']));
}

?>
