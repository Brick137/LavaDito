<?php
require_once '../session.php';
require_role('lavanderia');
require_once '../conexion.php';

// Solo mostramos los servicios del catálogo (pedido_id es NULL)
$servs = $conexion->query("SELECT * FROM servicios WHERE pedido_id IS NULL ORDER BY tipo_servicio ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Servicios - LavaDito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-tags-fill text-info"></i> Catálogo de Servicios</h3>
        <a href="../lavanderia/view_lavanderia.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Panel</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0">Lista de Precios</h5>
            <a href="form_servicio.php" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Nuevo Servicio
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Servicio</th>
                        <th>Precio</th>
                        <th>Peso Ref.</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($s = $servs->fetch_assoc()): ?>
                    <tr>
                        <td><?= $s['servicio_id'] ?></td>
                        <td class="fw-bold"><?= htmlspecialchars($s['tipo_servicio']) ?></td>
                        <td class="text-success fw-bold">$<?= number_format($s['precio'], 2) ?></td>
                        <td><?= htmlspecialchars($s['peso']) ?> kg</td>
                        <td class="text-end">
                            <a href="form_servicio.php?id=<?= $s['servicio_id'] ?>" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <a href="eliminar_servicio.php?id=<?= $s['servicio_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que quieres eliminar este servicio del catálogo?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
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
</body>
</html>