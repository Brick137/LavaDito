<?php
require_once '../session.php';
require_role('lavanderia');
require_once '../conexion.php';

$result = mysqli_query($conexion, "SELECT * FROM rutas ORDER BY ruta_id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Rutas - LavaDito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-map-fill text-danger"></i> Rutas de Entrega</h3>
        <a href="../lavanderia/view_lavanderia.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID Ruta</th>
                        <th>Pedido</th>
                        <th>Furgoneta</th>
                        <th>Conductor</th>
                        <th>Salida</th>
                        <th>Entrega Estimada</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(mysqli_num_rows($result) > 0): while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['ruta_id'] ?></td>
                        <td>
                            <a href="pedido_detalle.php?pedido=<?= $row['pedido_id'] ?>" class="text-decoration-none">
                                #<?= $row['pedido_id'] ?>
                            </a>
                        </td>
                        <td>ID: <?= $row['furgoneta_id'] ?></td>
                        <td>ID: <?= $row['conductor_id'] ?></td>
                        <td><?= $row['fecha_hora_salida'] ? date('d/m/Y H:i', strtotime($row['fecha_hora_salida'])) : '--' ?></td>
                        <td><?= $row['fecha_hora_entrega'] ? date('d/m/Y H:i', strtotime($row['fecha_hora_entrega'])) : '--' ?></td>
                        <td>
                            <span class="badge bg-info text-dark"><?= htmlspecialchars($row['estado']) ?></span>
                        </td>
                    </tr>
                <?php endwhile; else: echo "<tr><td colspan='7' class='text-center py-3'>No hay rutas activas</td></tr>"; endif; ?>
                </tbody>
            </table>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>