<?php
require_once '../session.php';
require_role('lavanderia');
require_once '../conexion.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$servicio = ['tipo_servicio' => '', 'precio' => '', 'peso' => '']; // Valores vacíos por defecto
$titulo = "Nuevo Servicio";

// Si hay ID, buscamos los datos para editar
if ($id > 0) {
    $titulo = "Editar Servicio";
    $stmt = $conexion->prepare("SELECT * FROM servicios WHERE servicio_id = ? AND pedido_id IS NULL");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $servicio = $res->fetch_assoc();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?> - LavaDito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><?= $titulo ?></h4>
                </div>
                <div class="card-body p-4">
                    <form action="guardar_servicio.php" method="POST">
                        <input type="hidden" name="servicio_id" value="<?= $id ?>">

                        <div class="mb-3">
                            <label class="form-label">Nombre del Servicio</label>
                            <input type="text" name="tipo_servicio" class="form-control" value="<?= htmlspecialchars($servicio['tipo_servicio']) ?>" required placeholder="Ej: Lavado de Edredón Matrimonial">
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Precio ($)</label>
                                <input type="number" step="0.01" name="precio" class="form-control" value="<?= htmlspecialchars($servicio['precio']) ?>" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Peso Referencia (Kg)</label>
                                <input type="number" step="0.1" name="peso" class="form-control" value="<?= htmlspecialchars($servicio['peso']) ?>" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-success btn-lg">Guardar Cambios</button>
                            <a href="admin_servicios.php" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-12 mb-4">
                <img src="../img/logo-transparente.png" alt="Logo" height="60" style="filter: brightness(0) invert(1);">
                <p class="mt-2 text-secondary">El mejor software para tu lavandería.</p>
            </div>
            <div class="col-md-3 mb-3">
                <h6 class="text-uppercase text-primary mb-3 fw-bold">Desarrollador</h6>
                <p>Castillo Alcantar Diego</p>
            </div>
            <div class="col-md-3 mb-3">
                <h6 class="text-uppercase text-primary mb-3 fw-bold">Desarrollador</h6>
                <p>Hernández Pérez José Luis</p>
            </div>
            <div class="col-md-3 mb-3">
                <h6 class="text-uppercase text-primary mb-3 fw-bold">Desarrollador</h6>
                <p>Limón Jiménez Jorge Alberto</p>
            </div>
            <div class="col-md-3 mb-3">
                <h6 class="text-uppercase text-primary mb-3 fw-bold">Desarrollador</h6>
                <p>Vázquez López Ismael</p>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <div class="text-center text-secondary small">
            &copy; 2025 LavaDito. Todos los derechos reservados.
        </div>
    </div>
</footer>
</body>
</html>