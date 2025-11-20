<?php
require_once '../session.php';
require_role('conductor');
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conductor_id = intval($_POST['conductor_id']);
    $licencia = trim($_POST['licencia']);

    // Verificamos que el ID sea válido y la licencia no esté vacía
    if (!empty($licencia) && $conductor_id > 0) {
        
        // Preparamos la actualización
        $stmt = $conexion->prepare("UPDATE conductores SET licencia = ? WHERE conductor_id = ?");
        $stmt->bind_param("si", $licencia, $conductor_id);
        
        if ($stmt->execute()) {
            // Si sale bien, regresamos con mensaje de éxito
            header("Location: view_conductor.php?msg=licencia_ok");
        } else {
            echo "Error al actualizar: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Si estaba vacío, regresamos con error
        header("Location: view_conductor.php?error=vacio");
    }
} else {
    // Si intentan entrar directo sin enviar datos, los regresamos
    header("Location: view_conductor.php");
}
?>