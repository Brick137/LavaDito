<?php
require_once "conexion.php";
session_start();

// Verifica envío del formulario
if (!isset($_POST['usuario']) || !isset($_POST['clave'])) {
    header("Location: inicio_sesion.html");
    exit();
}

$usuario = $_POST['usuario'];
$clave   = $_POST['clave'];

// Consulta del usuario
$sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' LIMIT 1";
$result = $conexion->query($sql);

if ($result->num_rows === 0) {
    echo "<script>alert('Usuario no encontrado'); history.back();</script>";
    exit();
}

$user = $result->fetch_assoc();


if ($clave !== $user['clave']) {
    echo "<script>alert('Contraseña incorrecta'); history.back();</script>";
    exit();
}

// ---------------
// VARIABLES DE SESIÓN CORRECTAS
// ---------------
$_SESSION['usuario_id']  = $user['usuario_id'];
$_SESSION['cliente_id']  = $user['cliente_id'];  // 🔥 IMPORTANTE
$_SESSION['usuario_name'] = $user['usuario'];    // 🔥 IMPORTANTE
$_SESSION['rol']         = $user['rol'];

// Redirección por rol
if ($user['rol'] === "cliente") {
    header("Location: cliente/view_user.php");
    exit();
}
if ($user['rol'] === "lavanderia") {
    header("Location: lavanderia/view_lavanderia.php");
    exit();
}
if ($user['rol'] === "conductor") {
    header("Location: conductor/view_conductor.php");
    exit();
}


echo "Rol no reconocido";
