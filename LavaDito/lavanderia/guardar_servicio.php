<?php
require_once '../session.php';
require_role('lavanderia');
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['servicio_id']);
    $nombre = trim($_POST['tipo_servicio']);
    $precio = floatval($_POST['precio']);
    $peso = floatval($_POST['peso']);

    if ($id > 0) {
        // EDITAR EXISTENTE
        $stmt = $conexion->prepare("UPDATE servicios SET tipo_servicio = ?, precio = ?, peso = ? WHERE servicio_id = ?");
        $stmt->bind_param("sddi", $nombre, $precio, $peso, $id);
    } else {
        // CREAR NUEVO (Importante: pedido_id debe ser NULL para que sea catálogo)
        $stmt = $conexion->prepare("INSERT INTO servicios (pedido_id, tipo_servicio, precio, peso) VALUES (NULL, ?, ?, ?)");
        $stmt->bind_param("sdd", $nombre, $precio, $peso);
    }

    if ($stmt->execute()) {
        header("Location: admin_servicios.php?msg=guardado");
    } else {
        echo "Error al guardar: " . $conexion->error;
    }
    $stmt->close();
}
?>