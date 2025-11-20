<?php
require_once '../session.php';
require_role('conductor');
require_once '../conexion.php';

// Si alguien intenta entrar directo sin enviar datos, lo regresamos
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: view_conductor.php");
    exit;
}

// Recibimos todos los IDs necesarios del formulario
$ruta_id = intval($_POST['ruta_id']);
$pedido_id = intval($_POST['pedido_id']);
$conductor_id = intval($_POST['conductor_id']);
$furgoneta_id = intval($_POST['furgoneta_id']);

// Usamos una transacción para asegurar que TODO se actualice o NADA
$conexion->begin_transaction();

try {
    // 1. Actualizar PEDIDO a 'entregado'
    $stmt1 = $conexion->prepare("UPDATE pedidos SET estado = 'entregado', fecha_entrega = CURDATE() WHERE pedido_id = ?");
    $stmt1->bind_param("i", $pedido_id);
    if (!$stmt1->execute()) throw new Exception("Error al actualizar pedido");
    $stmt1->close();

    // 2. Actualizar RUTA (Poner fecha de entrega y estado Finalizado)
    $stmt2 = $conexion->prepare("UPDATE rutas SET estado = 'Finalizado', fecha_hora_entrega = NOW() WHERE ruta_id = ?");
    $stmt2->bind_param("i", $ruta_id);
    if (!$stmt2->execute()) throw new Exception("Error al cerrar ruta");
    $stmt2->close();

    // 3. LIBERAR CONDUCTOR (Ponerlo en 'Activo' o 'Libre')
    // Nota: Revisa si en tu BD usas 'Activo' o 'Libre'. Aquí uso 'Activo' como ejemplo.
    $stmt3 = $conexion->prepare("UPDATE conductores SET estado = 'Activo' WHERE conductor_id = ?");
    $stmt3->bind_param("i", $conductor_id);
    if (!$stmt3->execute()) throw new Exception("Error al liberar conductor");
    $stmt3->close();

    // 4. LIBERAR FURGONETA (Ponerla en 'Libre')
    $stmt4 = $conexion->prepare("UPDATE furgonetas SET estado = 'Libre' WHERE furgoneta_id = ?");
    $stmt4->bind_param("i", $furgoneta_id);
    if (!$stmt4->execute()) throw new Exception("Error al liberar furgoneta");
    $stmt4->close();

    // 5. (Opcional) Registrar pago como 'Completado' si estaba pendiente
    // Esto asume que el chofer cobró al entregar.
    $stmt5 = $conexion->prepare("UPDATE pagos SET estado = 'Completado', fecha_pago = CURDATE(), metodo = 'Efectivo' WHERE pedido_id = ? AND estado != 'Completado'");
    $stmt5->bind_param("i", $pedido_id);
    $stmt5->execute(); // No lanzamos error si falla esto, es opcional
    $stmt5->close();

    // Si todo salió bien, guardamos cambios
    $conexion->commit();
    
    // Redirigir con mensaje de éxito
    header("Location: view_conductor.php?msg=entrega_exitosa");
    exit;

} catch (Exception $e) {
    // Si algo falló, deshacemos todo
    $conexion->rollback();
    echo "<div style='color:red; padding:20px;'>Error crítico al procesar la entrega: " . $e->getMessage() . "</div>";
    echo "<br><a href='view_conductor.php'>Volver</a>";
    exit;
}
?>