<?php
require_once '../session.php';
require_role('cliente');
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: carrito.php");
    exit;
}

// 1. CAPTURAR DATOS (Asegurando que total sea un número)
$cliente_id = $_SESSION['cliente_id'];
$nombre = trim($_POST['cliente_nombre']);
$telefono = trim($_POST['telefono']);
$direccion = trim($_POST['direccion']);
$fecha_recoleccion = $_POST['fecha_recoleccion'] ?? date('Y-m-d H:i:s'); // Fecha por defecto si falta

// CORRECCIÓN CLAVE: Forzamos que sea float. Si no llega, ponemos 0.00
$total = isset($_POST['total']) ? floatval($_POST['total']) : 0.00;

// Validación extra: Si el total es 0, recalculamos desde el carrito por seguridad
if ($total <= 0) {
    $check = $conexion->prepare("SELECT SUM(subtotal) as real_total FROM carrito WHERE usuario_id = ?");
    $check->bind_param("i", $_SESSION['usuario_id']);
    $check->execute();
    $resCheck = $check->get_result()->fetch_assoc();
    $total = floatval($resCheck['real_total'] ?? 0.00);
    $check->close();
}

// Obtener carrito para procesar
$stmtCart = $conexion->prepare("SELECT c.*, s.tipo_servicio, s.precio, s.peso FROM carrito c JOIN servicios s ON c.servicio_id = s.servicio_id WHERE c.usuario_id = ?");
$stmtCart->bind_param("i", $_SESSION['usuario_id']);
$stmtCart->execute();
$cartRes = $stmtCart->get_result();
$stmtCart->close();

if ($cartRes->num_rows === 0) {
    header("Location: carrito.php?error=carrito_vacio");
    exit;
}

$conexion->begin_transaction();

try {
    // 2. ACTUALIZAR CLIENTE
    $up = $conexion->prepare("UPDATE clientes SET nombre = ?, telefono = ?, direccion = ? WHERE cliente_id = ?");
    $up->bind_param("sssi", $nombre, $telefono, $direccion, $cliente_id);
    $up->execute(); 
    $up->close();

    // 3. LÓGICA DE ASIGNACIÓN AUTOMÁTICA
    $cond_res = $conexion->query("SELECT conductor_id FROM conductores WHERE estado = 'Libre' LIMIT 1");
    $furg_res = $conexion->query("SELECT furgoneta_id FROM furgonetas WHERE estado = 'Libre' LIMIT 1");
    
    $auto_conductor = $cond_res->fetch_assoc();
    $auto_furgoneta = $furg_res->fetch_assoc();

    $estado_inicial = ($auto_conductor && $auto_furgoneta) ? 'aceptado' : 'pendiente';

    // 4. INSERTAR PEDIDO (Aquí estaba el posible error)
    // Aseguramos el orden: cliente_id, fecha_entrega (recoleccion), estado, total
    $ins = $conexion->prepare("INSERT INTO pedidos (cliente_id, fecha_pedido, fecha_entrega, estado, total) VALUES (?, CURDATE(), ?, ?, ?)");
    
    // 'i' = entero, 's' = string, 's' = string, 'd' = double (decimal)
    $ins->bind_param("issd", $cliente_id, $fecha_recoleccion, $estado_inicial, $total);
    
    if (!$ins->execute()) {
        throw new Exception("Error al insertar pedido: " . $ins->error);
    }
    $pedido_id = $conexion->insert_id;
    $ins->close();

    // 5. CREAR RUTA SI SE ASIGNÓ AUTOMÁTICAMENTE
    if ($estado_inicial == 'aceptado') {
        $cid = $auto_conductor['conductor_id'];
        $fid = $auto_furgoneta['furgoneta_id'];
        
        $ruta = $conexion->prepare("INSERT INTO rutas (pedido_id, furgoneta_id, conductor_id, fecha_hora_salida, estado) VALUES (?, ?, ?, NOW(), 'Asignado')");
        $ruta->bind_param("iii", $pedido_id, $fid, $cid);
        $ruta->execute(); $ruta->close();

        $conexion->query("UPDATE conductores SET estado = 'Ocupado' WHERE conductor_id = $cid");
        $conexion->query("UPDATE furgonetas SET estado = 'Ocupado' WHERE furgoneta_id = $fid");
    }

    // 6. MOVER ITEMS A DETALLE DE SERVICIOS
    $insSrv = $conexion->prepare("INSERT INTO servicios (pedido_id, tipo_servicio, peso, precio) VALUES (?, ?, ?, ?)");
    foreach ($cartRes as $item) {
        for ($q=0; $q < intval($item['cantidad']); $q++){
            $peso = floatval($item['peso']);
            $precio = floatval($item['precio']);
            $insSrv->bind_param("isdd", $pedido_id, $item['tipo_servicio'], $peso, $precio);
            $insSrv->execute();
        }
    }
    $insSrv->close();

    // 7. REGISTRAR PAGO
    $insPay = $conexion->prepare("INSERT INTO pagos (pedido_id, monto, fecha_pago, metodo, estado) VALUES (?, ?, NULL, 'Pendiente', 'Pendiente')");
    $insPay->bind_param("id", $pedido_id, $total);
    $insPay->execute(); 
    $insPay->close();

    // 8. VACIAR CARRITO
    $del = $conexion->prepare("DELETE FROM carrito WHERE usuario_id = ?");
    $del->bind_param("i", $_SESSION['usuario_id']);
    $del->execute(); 
    $del->close();

    $conexion->commit();
    header("Location: seguimiento.php?pedido=" . intval($pedido_id));
    exit;

} catch (Exception $e) {
    $conexion->rollback();
    // Mostramos el error en pantalla para depurar si sigue fallando
    echo "<div style='padding:20px; background:red; color:white;'>Error Crítico: " . $e->getMessage() . "</div>";
    echo "<br><a href='carrito.php'>Volver al carrito</a>";
    exit;
}
?>