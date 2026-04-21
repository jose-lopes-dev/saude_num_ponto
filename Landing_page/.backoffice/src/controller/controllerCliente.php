<?php
require_once '../model/modelCliente.php';

$model = new ModelClientes();

if (isset($_POST['op']) && $_POST['op'] === 'getDashboard') {
    echo $model->getDashboardClientes();
}
?>
