<?php
// actualizar_carrito.php
require_once '../session.php';
require_role('cliente');
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: carrito.php");
    exit;
}

$id = intval($_POST['id'] ?? 0);
$cantidad = max(1, intval($_POST['cantidad'] ?? 1));
if ($id <= 0) {
    header("Location: carrito.php");
    exit;
}

// obtener precio del servicio
$stmt = $conexion->prepare("SELECT c.id, c.servicio_id, s.precio FROM carrito c JOIN servicios s ON c.servicio_id = s.servicio_id WHERE c.id = ? AND c.usuario_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['usuario_id']);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
$stmt->close();

if (!$row) {
    header("Location: carrito.php");
    exit;
}

$subtotal = $cantidad * floatval($row['precio']);
$u = $conexion->prepare("UPDATE carrito SET cantidad = ?, subtotal = ? WHERE id = ?");
$u->bind_param("idi", $cantidad, $subtotal, $id);
$u->execute();
$u->close();

header("Location: carrito.php");
exit;
