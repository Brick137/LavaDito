<?php
// agregar_carrito.php
require_once '../session.php';
require_role('cliente');
require_once '../conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: view_user.php");
    exit;
}

$id = intval($_POST['id'] ?? 0);
$cantidad = max(1, intval($_POST['cantidad'] ?? 1));

// obtener servicio del catalogo
$stmt = $conexion->prepare("SELECT servicio_id, tipo_servicio, precio, peso FROM servicios WHERE servicio_id = ? AND pedido_id IS NULL LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$item = $res->fetch_assoc();
$stmt->close();

if (!$item) {
    $_SESSION['error'] = "Servicio no encontrado.";
    header("Location: view_user.php");
    exit;
}

// insertar en tabla carrito
$stmt2 = $conexion->prepare("SELECT id, cantidad FROM carrito WHERE usuario_id = ? AND servicio_id = ? LIMIT 1");
$stmt2->bind_param("ii", $_SESSION['usuario_id'], $id);
$stmt2->execute();
$r2 = $stmt2->get_result()->fetch_assoc();
$stmt2->close();

if ($r2) {
    // actualizar cantidad y subtotal
    $newq = intval($r2['cantidad']) + $cantidad;
    $subtotal = $newq * floatval($item['precio']);
    $u = $conexion->prepare("UPDATE carrito SET cantidad = ?, subtotal = ? WHERE id = ?");
    $u->bind_param("idi", $newq, $subtotal, $r2['id']);
    $u->execute();
    $u->close();
} else {
    $subtotal = $cantidad * floatval($item['precio']);
    $i = $conexion->prepare("INSERT INTO carrito (usuario_id, servicio_id, cantidad, subtotal) VALUES (?, ?, ?, ?)");
    $i->bind_param("iiid", $_SESSION['usuario_id'], $id, $cantidad, $subtotal);
    $i->execute();
    $i->close();
}

header("Location: carrito.php");
exit;
