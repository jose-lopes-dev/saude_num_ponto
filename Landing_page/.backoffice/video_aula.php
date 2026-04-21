<?php
require_once 'src/auth/auth_aula.php';
require_once 'src/model/modelAula.php';

$mdl = new Aula();

$idAula = (int)($_GET['id'] ?? 0);
$idUser = $_SESSION['id'];
$tipo   = (int)$_SESSION['tipo']; 

if (!$idAula) die('Aula inválida');

$aula = json_decode($mdl->getAulaById($idAula), true);
if (!$aula) die('Aula não encontrada');

// PERMISSÕES:

// PT só entra na sua aula
if ($tipo === 2 && $aula['id_pt'] != $idUser) {
    die('Acesso negado');
}

// Cliente tem de estar inscrito
if ($tipo === 3) {
    $id_cliente = $mdl->getClienteCodigoByUtilizador($idUser);
    if (!$mdl->clienteJaInscrito($id_cliente, $idAula)) {
        die('Não está inscrito nesta aula');
    }
}

$nomeUtilizador = $_SESSION['nome'] ?? $_SESSION['username'] ?? 'Utilizador';
$isPT = ($tipo === 2);
?>

<?php
require_once 'includes/header.php';
if ($isPT) {
    require_once 'includes/sidebar_pt.php';
} else {
    require_once 'includes/sidebar_cliente.php';
}

?>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <h4 class="mb-3"><?= htmlspecialchars($aula['titulo']) ?></h4>
            <div id="jitsi-container">

            </div>

        </div>
    </div>

<?php
$roomName = !empty($aula['sala_nome'])
    ? $aula['sala_nome']
    : 'aula-' . $idAula;
?>

<script>
window.JITSI_CONFIG = {
    room: <?= json_encode($roomName) ?>,
    user: <?= json_encode($nomeUtilizador) ?>,
    isPT: <?= $isPT ? 'true' : 'false' ?>,
    subject: <?= json_encode($aula['titulo']) ?>
};
</script>

<script src="src/js/jitsi.js"></script>

<?php require_once 'includes/footer.php'; ?>

