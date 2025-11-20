<?php
// session.php
session_start();

function is_logged() {
    return isset($_SESSION['usuario_id']);
}

function require_login() {
    if (!is_logged()) {
        header("Location: /LavaDito-main/inicio_sesion.html");
        exit;
    }
}

function require_role($role) {
    require_login();
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== $role) {
        http_response_code(403);
        echo "No autorizado.";
        exit;
    }
}
?>
