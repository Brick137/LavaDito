<?php
require_once '../session.php';
require_role('lavanderia');
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ver_pedidos.php");
    exit;
}

$pedido_id = intval($_POST['pedido_id']);
$conductor_id = intval($_POST['conductor_id'] ?? 0);
$furgoneta_id = intval($_POST['furgoneta_id'] ?? 0);

// Validaciones básicas
if ($conductor_id == 0 || $furgoneta_id == 0) {
    die("Error: Debes seleccionar un conductor y una furgoneta.");
}

$conexion->begin_transaction();

try {
    // 1. AVERIGUAR ESTADO ACTUAL
    $check = $conexion->prepare("SELECT estado FROM pedidos WHERE pedido_id = ?");
    $check->bind_param("i", $pedido_id);
    $check->execute();
    $res = $check->get_result()->fetch_assoc();
    $estado_actual = $res['estado'];
    $check->close();

    // 2. DECIDIR EL NUEVO ESTADO (CORREGIDO)
    $nuevo_estado = '';
    
    if ($estado_actual == 'pendiente') {
        // FASE 1: El admin asigna para RECOLECCIÓN
        $nuevo_estado = 'aceptado'; 
    } 
    
    elseif ($estado_actual == 'terminado' || $estado_actual == 'preparando') {
        // FASE 2: El admin asigna para ENTREGA
        // Usamos el nombre largo para que coincida con el seguimiento
        $nuevo_estado = 'en_ruta_entrega'; 
    } else {
        // Si se reasigna por error, mantenemos el estado que tenía
        $nuevo_estado = $estado_actual; 
    }

    // 3. CREAR LA RUTA
    $stmt = $conexion->prepare("INSERT INTO rutas (pedido_id, furgoneta_id, conductor_id, fecha_hora_salida, estado) VALUES (?, ?, ?, NOW(), 'Asignado')");
    $stmt->bind_param("iii", $pedido_id, $furgoneta_id, $conductor_id);
    $stmt->execute();
    $stmt->close();

    // 4. OCUPAR RECURSOS
    $conexion->query("UPDATE conductores SET estado = 'Asignado' WHERE conductor_id = $conductor_id");
    $conexion->query("UPDATE furgonetas SET estado = 'Asignado' WHERE furgoneta_id = $furgoneta_id");

    // 5. ACTUALIZAR EL PEDIDO
    $stmt4 = $conexion->prepare("UPDATE pedidos SET estado = ? WHERE pedido_id = ?");
    $stmt4->bind_param("si", $nuevo_estado, $pedido_id);
    $stmt4->execute();
    $stmt4->close();

    $conexion->commit();
    header("Location: pedido_detalle.php?pedido=" . $pedido_id);
    exit;

} catch (Exception $e) {
    $conexion->rollback();
    echo "Error crítico al asignar: " . $e->getMessage();
    exit;
}
?>