<?php
session_start();

// Si no hay sesión activa, redirige al login
if (!isset($_SESSION['usuario'])) {
    header("Location: inicio_sesion.html");
    exit();
}

// Evitar caché para que no aparezca contenido tras cerrar sesión
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
</head>
<body>
    <h1>Bienvenido, <?php echo $_SESSION['nombre'] . " " . $_SESSION['apellidos']; ?>!</h1>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>