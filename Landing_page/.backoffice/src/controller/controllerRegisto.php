<?php
require_once '../model/modelUtilizador.php';
$u = new ModelUtilizador();

if ($_POST['op'] == 'registar') {
    echo $u->registar($_POST['nome'], $_POST['email'], $_POST['password']);
}
?>
