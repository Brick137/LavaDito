<?php
require_once '../session.php';
require_role('lavanderia');
require_once '../conexion.php';

$result = mysqli_query($conexion, "SELECT * FROM furgonetas ORDER BY furgoneta_id ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Furgonetas - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-truck text-secondary"></i> Gestión de Furgonetas</h3>
        <a href="../lavanderia/view_lavanderia.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Panel</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0">Flota de Vehículos</h5>
            <a href="form_furgoneta.php" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nueva Furgoneta
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Placa</th>
                        <th>Modelo</th>
                        <th>Capacidad</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(mysqli_num_rows($result) > 0): while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['furgoneta_id'] ?></td>
                        <td class="fw-bold"><?= htmlspecialchars($row['placa']) ?></td>
                        <td><?= htmlspecialchars($row['modelo']) ?></td>
                        <td><?= htmlspecialchars($row['capacidad']) ?> kg</td>
                        <td>
                            <span class="badge <?= ($row['estado']=='Libre') ? 'bg-success' : 'bg-secondary' ?>">
                                <?= htmlspecialchars($row['estado']) ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="form_furgoneta.php?id=<?= $row['furgoneta_id'] ?>" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="eliminar_furgoneta.php?id=<?= $row['furgoneta_id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar esta furgoneta?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; else: echo "<tr><td colspan='6' class='text-center py-3'>No hay furgonetas registradas</td></tr>"; endif; ?>
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