<?php
require_once '../session.php';
require_role('lavanderia');
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ver_pedidos.php");
    exit;
}
$pedido_id = intval($_POST['pedido_id']);
$estado = trim($_POST['estado']);

$stmt = $conexion->prepare("UPDATE pedidos SET estado = ? WHERE pedido_id = ?");
$stmt->bind_param("si", $estado, $pedido_id);
$stmt->execute();
$stmt->close();

if ($conexion->query("SHOW TABLES LIKE 'pedido_estado'")->num_rows) {
    $stmt2 = $conexion->prepare("INSERT INTO pedido_estado (pedido_id, estado, observacion) VALUES (?, ?, ?)");
    $obs = "Actualizado por lavandería";
    $stmt2->bind_param("iss", $pedido_id, $estado, $obs);
    $stmt2->execute();
    $stmt2->close();
}

header("Location: pedido_detalle.php?pedido=" . $pedido_id);
exit;
