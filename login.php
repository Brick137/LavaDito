<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = $_POST['usuario'];
    $clave   = $_POST['clave'];

    // Buscar usuario y cliente relacionado
    $stmt = $conexion->prepare("
        SELECT u.usuario_id, u.usuario, u.clave, c.nombre, c.apellidos 
        FROM usuarios u
        INNER JOIN clientes c ON u.cliente_id = c.cliente_id
        WHERE u.usuario = ?
    ");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $data = $resultado->fetch_assoc();

        if ($clave === $data['clave']) { // texto plano
            $_SESSION['usuario'] = $data['usuario'];
            $_SESSION['nombre']  = $data['nombre'];
            $_SESSION['apellidos'] = $data['apellidos'];
            header("Location: home_usuario.php");
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado";
    }

    $stmt->close();
    $conexion->close();

} else {
    echo "Acceso no permitido";
}
?>