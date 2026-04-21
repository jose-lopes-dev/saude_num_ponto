<?php
require_once __DIR__ . '/auth.php';

$tipo = (int)$_SESSION['tipo'];

// PT (2) e Cliente (3) podem entrar na aula
if (!in_array($tipo, [2, 3], true)) {
    header("Location: /Projeto_Final_AIO/Landing_page/.backoffice/login.html");
    exit;
}
