<?php
require_once '../session.php';
require_role('conductor');
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: view_conductor.php");
    exit;
}

// Recibimos y limpiamos datos
$ruta_id = intval($_POST['ruta_id']);
$pedido_id = intval($_POST['pedido_id']);
$cond_id = intval($_POST['conductor_id']);
$furg_id = intval($_POST['furgoneta_id']);

// LIMPIEZA CRÍTICA: Quitamos espacios y ponemos en minúsculas para evitar errores de comparación
$estado_actual = strtolower(trim($_POST['estado_actual']));

$conexion->begin_transaction();

try {
    $nuevo_estado = '';
    $liberar_recursos = false;
    $cerrar_ruta = false;
    $msg = "";

    // MÁQUINA DE ESTADOS
    switch ($estado_actual) {
        
        // CASO 1: INICIAR VIAJE
        case 'aceptado':
            $nuevo_estado = 'en_camino_recoleccion';
            break;

        // CASO 2: RECOLECCIÓN
        case 'en_camino_recoleccion':
            $nuevo_estado = 'recogido';
            $liberar_recursos = true;     
            $cerrar_ruta = true; 
            $msg = "recoleccion_ok";
            break;

        // CASO 3: SALIDA DE LAVANDERÍA (Re-asignación)
        case 'terminado': 
        case 'en_proceso': // Por si acaso
            $nuevo_estado = 'en_ruta_entrega';
            // Forzamos ocupación
            $conexion->query("UPDATE conductores SET estado = 'Asignado' WHERE conductor_id = $cond_id");
            $conexion->query("UPDATE furgonetas SET estado = 'Asignado' WHERE furgoneta_id = $furg_id");
            break;

        // CASO 4: ENTREGA FINAL
        case 'en_ruta':           // Nombre viejo
        case 'en_ruta_entrega':   // Nombre nuevo
            $nuevo_estado = 'entregado';
            $liberar_recursos = true;
            $cerrar_ruta = true;
            $msg = "entrega_ok";
            
            // Actualizar fecha y pago
            $conexion->query("UPDATE pedidos SET fecha_entrega = CURDATE() WHERE pedido_id = $pedido_id");
            $conexion->query("UPDATE pagos SET estado = 'Completado', fecha_pago = CURDATE() WHERE pedido_id = $pedido_id AND estado != 'Completado'");
            break;

        default:
            // SI LLEGA AQUÍ, ES QUE EL NOMBRE NO COINCIDIÓ
            throw new Exception("Estado desconocido o no válido: [" . $estado_actual . "]");
    }

    // SI ENCONTRÓ UN ESTADO NUEVO, EJECUTAMOS LOS CAMBIOS
    if ($nuevo_estado) {
        // 1. Actualizar Pedido
        $stmt = $conexion->prepare("UPDATE pedidos SET estado = ? WHERE pedido_id = ?");
        $stmt->bind_param("si", $nuevo_estado, $pedido_id);
        if (!$stmt->execute()) throw new Exception("Error al actualizar pedido: " . $stmt->error);
        $stmt->close();

        // 2. Cerrar Ruta
        if ($cerrar_ruta) {
            $conexion->query("UPDATE rutas SET estado = 'Finalizado', fecha_hora_entrega = NOW() WHERE ruta_id = $ruta_id");
        }

        // 3. Liberar Recursos
        if ($liberar_recursos) {
            $conexion->query("UPDATE conductores SET estado = 'Libre' WHERE conductor_id = $cond_id");
            $conexion->query("UPDATE furgonetas SET estado = 'Libre' WHERE furgoneta_id = $furg_id");
        }

        $conexion->commit();
        header("Location: view_conductor.php?msg=" . $msg);
        exit;
    } else {
        throw new Exception("No se determinó un nuevo estado para: " . $estado_actual);
    }

} catch (Exception $e) {
    $conexion->rollback();
    // MODO DEPURACIÓN: MOSTRAR ERROR EN PANTALLA
    echo "<div style='background-color:#ffebee; color:#c62828; padding:20px; font-family:sans-serif; border:1px solid #ef9a9a; margin:20px;'>";
    echo "<h2>⚠️ Error de Proceso</h2>";
    echo "<p><strong>Detalle:</strong> " . $e->getMessage() . "</p>";
    echo "<hr>";
    echo "<p>Datos recibidos:</p>";
    echo "<ul>";
    echo "<li>Pedido ID: $pedido_id</li>";
    echo "<li>Estado Recibido: <strong>[" . htmlspecialchars($estado_actual) . "]</strong></li>";
    echo "</ul>";
    echo "<br><a href='view_conductor.php' style='background:#0d6efd; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Volver e Intentar</a>";
    echo "</div>";
    exit;
}
?>