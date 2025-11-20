<?php
require_once '../session.php';
require_role('cliente');
require_once '../conexion.php';

$cliente_id = $_SESSION['cliente_id'];

// Consultar todos los pedidos de este cliente
// Ordenados del más reciente al más antiguo
$query = "SELECT pedido_id, fecha_pedido, fecha_entrega, estado, total 
          FROM pedidos 
          WHERE cliente_id = ? 
          ORDER BY fecha_pedido DESC, pedido_id DESC";

$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Mis Pedidos - LavaDito</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600&display=swap" rel="stylesheet">

    <style>
        .brand-text { font-family: 'Fredoka', sans-serif; color: #9ecfff; -webkit-text-stroke: 1px #2c5282; font-size: 24px; }
        .card-pedido { transition: transform 0.2s; border-left: 5px solid transparent; }
        .card-pedido:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        
        .borde-pendiente { border-left-color: #ffc107 !important; } /* Amarillo */
        .borde-preparando { border-left-color: #0dcaf0 !important; } /* Azul Claro */
        .borde-en_ruta { border-left-color: #0d6efd !important; } /* Azul */
        .borde-entregado { border-left-color: #198754 !important; } /* Verde */
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-light bg-white shadow-sm mb-4 sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="view_user.php">
        <img src="../img/logo-transparente.png" alt="Logo" height="45">
        <span class="brand-text ms-2">LavaDito</span>
    </a>
    <a href="view_user.php" class="btn btn-outline-secondary btn-sm rounded-pill">
        <i class="bi bi-arrow-left"></i> Volver al Inicio
    </a>
  </div>
</nav>

<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-light">Historial de Pedidos</h2>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <div class="row g-3">
            <?php while ($row = $result->fetch_assoc()): 
                $clase_borde = 'borde-pendiente';
                if ($row['estado'] == 'preparando' || $row['estado'] == 'aceptado') $clase_borde = 'borde-preparando';
                if ($row['estado'] == 'en_ruta') $clase_borde = 'borde-en_ruta';
                if ($row['estado'] == 'entregado') $clase_borde = 'borde-entregado';
            ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card card-pedido shadow-sm h-100 <?= $clase_borde ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title fw-bold mb-0">Pedido #<?= $row['pedido_id'] ?></h5>
                                    <small class="text-muted"><?= date('d/m/Y', strtotime($row['fecha_pedido'])) ?></small>
                                </div>
                                <span class="badge bg-light text-dark border text-uppercase small">
                                    <?= htmlspecialchars($row['estado']) ?>
                                </span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-end">
                                <div>
                                    <small class="text-muted d-block">Total:</small>
                                    <span class="fs-5 fw-bold text-success">$<?= number_format($row['total'], 2) ?></span>
                                </div>
                                
                                <a href="seguimiento.php?pedido=<?= $row['pedido_id'] ?>" class="btn btn-primary btn-sm rounded-pill px-3">
                                    Ver Seguimiento <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="bi bi-basket display-1 text-muted opacity-25"></i>
            <h4 class="text-muted mt-3">Aún no tienes pedidos</h4>
            <p class="text-muted">Cuando hagas tu primer pedido, aparecerá aquí.</p>
            <a href="view_user.php" class="btn btn-primary rounded-pill mt-2">¡Hacer un pedido ahora!</a>
        </div>
    <?php endif; ?>

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