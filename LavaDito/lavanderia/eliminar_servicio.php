<?php
require_once '../session.php';
require_role('lavanderia');
require_once '../conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Solo borramos si pedido_id es NULL (para no borrar servicios históricos de pedidos viejos)
    $stmt = $conexion->prepare("DELETE FROM servicios WHERE servicio_id = ? AND pedido_id IS NULL");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: admin_servicios.php");
exit;
?>