<?php
require_once __DIR__ . '/src/auth/auth.php';
require_once __DIR__ . '/src/model/modelAula.php';


$mdl = new Aula();

$idAula = intval($_GET['id'] ?? 0);
$idUser = $_SESSION['id'];
$tipo   = (int)$_SESSION['tipo'];

$aula = json_decode($mdl->getAulaById($idAula), true);

if (!$aula) die("Aula não encontrada");

/* PERMISSÕES */
if ($tipo === 2 && $aula['id_pt'] != $idUser) {
    die("Acesso negado");
}

if ($tipo === 3) {

    // FIX: cliente.codigo
    $id_cliente = $mdl->getClienteCodigoByUtilizador($idUser);

    if (!$mdl->clienteJaInscrito($id_cliente, $idAula)) {
        die("Não está inscrito");
    }
}

$nome_utilizador = $_SESSION['nome'] ?? 'PT';

?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($aula['titulo']) ?></title>
    <script src="https://meet.jit.si/external_api.js"></script>

    <style>
html, body {
    width: 100%;
    height: 100%;
    margin: 0;
}

#jitsi-container {
    width: 100vw;
    height: 100vh;
}
</style>

</head>

<body style="margin:0;">

<div id="jitsi-container"></div>

<script>
const JITSI_ROOM   = <?= json_encode("aula-$idAula") ?>;
const JITSI_USER   = <?= json_encode($nome_utilizador) ?>;
const JITSI_IS_PT  = <?= $tipo === 2 ? 'true' : 'false' ?>;
const JITSI_TITLE  = <?= json_encode($aula['titulo']) ?>;
</script>



<script src="src/js/aula_jitsi.js"></script>
</body>
</html>
