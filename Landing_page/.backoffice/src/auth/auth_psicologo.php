<?php
require_once __DIR__ . '/auth.php';

if ((int)$_SESSION['tipo'] !== 5) {
    header("Location: /Projeto_Final_AIO/Landing_page/.backoffice/login.html");
    exit;
}
