<?php
require_once '../session.php';
require_role('lavanderia');
require_once '../conexion.php';

$query = "SELECT p.*, c.nombre, c.apellidos 
          FROM pedidos p 
          LEFT JOIN clientes c ON p.cliente_id = c.cliente_id 
          ORDER BY p.fecha_pedido DESC";
$res = $conexion->query($query);
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Gestionar Pedidos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-list-check text-primary"></i> Pedidos</h3>
        <a href="../lavanderia/view_lavanderia.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Panel</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>ID</th><th>Cliente</th><th>Fecha</th><th>Estado</th><th>Total</th><th>Acción</th></tr>
                    </thead>
                    <tbody>
                    <?php while ($r = $res->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= $r['pedido_id'] ?></td>
                        <td><?= htmlspecialchars($r['nombre'] . ' ' . $r['apellidos']) ?></td>
                        <td><?= $r['fecha_pedido'] ?></td>
                        <td>
                            <?php 
                                $badge = 'bg-secondary';
                                if($r['estado'] == 'pendiente') $badge = 'bg-warning text-dark';
                                if($r['estado'] == 'aceptado') $badge = 'bg-info text-dark';
                                if($r['estado'] == 'entregado') $badge = 'bg-success';
                            ?>
                            <span class="badge <?= $badge ?>"><?= htmlspecialchars($r['estado']) ?></span>
                        </td>
                        <td class="fw-bold">$<?= number_format($r['total'], 2) ?></td>
                        <td><a href="pedido_detalle.php?pedido=<?= $r['pedido_id'] ?>" class="btn btn-sm btn-outline-primary">Ver Detalle</a></td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
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