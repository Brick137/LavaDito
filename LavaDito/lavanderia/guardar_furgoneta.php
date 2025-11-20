<?php
require_once '../session.php';
require_role('lavanderia');
require_once '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['furgoneta_id']);
    $placa = trim(strtoupper($_POST['placa']));
    $modelo = trim($_POST['modelo']);
    $capacidad = floatval($_POST['capacidad']);
    $estado = $_POST['estado'];

    if ($id > 0) {
        // Actualizar
        $stmt = $conexion->prepare("UPDATE furgonetas SET placa=?, modelo=?, capacidad=?, estado=? WHERE furgoneta_id=?");
        $stmt->bind_param("ssdsi", $placa, $modelo, $capacidad, $estado, $id);
    } else {
        // Crear nueva
        $stmt = $conexion->prepare("INSERT INTO furgonetas (placa, modelo, capacidad, estado) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $placa, $modelo, $capacidad, $estado);
    }

    if ($stmt->execute()) {
        header("Location: ver_furgoneta.php?msg=guardado");
    } else {
        echo "Error: " . $conexion->error;
    }
    $stmt->close();
}
?>