<?php
session_start();
include 'conexion.php';

$usuario = $_POST['usuario'];
$clave   = $_POST['clave'];

$sql = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();

    // Comparación directa (texto plano)
    if ($clave === $data['clave']) {
        $_SESSION['usuario'] = $data['usuario'];
        header("Location: inicio_sesion.php");
        exit();
    } else {
        echo "Contraseña incorrecta";
    }
} else {
    echo "Usuario no encontrado";
}

$stmt->close();
$conexion->close();
?>
