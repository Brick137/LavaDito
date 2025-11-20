<?php
// eliminar_carrito.php
require_once '../session.php';
require_role('cliente');
require_once '../conexion.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: carrito.php");
    exit;
}

$stmt = $conexion->prepare("DELETE FROM carrito WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['usuario_id']);
$stmt->execute();
$stmt->close();

header("Location: carrito.php");
exit;
