<?php
require_once '../session.php';
require_role('lavanderia');
require_once '../conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Verificamos que no esté asignada a una ruta activa para evitar errores
    $check = $conexion->query("SELECT * FROM rutas WHERE furgoneta_id = $id AND estado != 'Finalizado'");
    
    if ($check->num_rows > 0) {
        echo "<script>alert('No se puede eliminar: Esta furgoneta está en una ruta activa.'); window.location='ver_furgoneta.php';</script>";
        exit;
    }

    $stmt = $conexion->prepare("DELETE FROM furgonetas WHERE furgoneta_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: ver_furgoneta.php");
exit;
?>