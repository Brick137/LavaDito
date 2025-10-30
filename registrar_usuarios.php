<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Datos del formulario
    $nombre    = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $email     = $_POST['email'];
    $telefono  = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $usuario   = $_POST['usuario'];
    $clave     = $_POST['clave'];

    // 1️⃣ Verificar que el usuario no exista
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo "El nombre de usuario ya existe. Por favor elige otro.";
        exit();
    }
    $stmt->close();

    // 2️⃣ Insertar datos en la tabla clientes
    $stmt = $conexion->prepare("INSERT INTO clientes (nombre, apellidos, telefono, email, direccion) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $apellidos, $telefono, $email, $direccion);
    if (!$stmt->execute()) {
        echo "Error al registrar cliente: " . $stmt->error;
        exit();
    }

    // Obtener el cliente_id recién creado
    $cliente_id = $conexion->insert_id;
    $stmt->close();

    // 3️⃣ Insertar datos en la tabla usuarios
    $stmt = $conexion->prepare("INSERT INTO usuarios (cliente_id, usuario, clave) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $cliente_id, $usuario, $clave); // texto plano
    if ($stmt->execute()) {
        header("Location: inicio_sesion.html"); // redirigir al login
        exit();
    } else {
        echo "Error al registrar usuario: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();

} else {
    echo "Acceso no permitido";
}
?>

