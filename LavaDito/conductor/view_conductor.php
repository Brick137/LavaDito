<?php
require_once '../session.php';
require_role('conductor');
require_once '../conexion.php';

// Intentamos obtener el usuario de varias variables de sesión por seguridad
$usuario_actual = $_SESSION['usuario_name'] ?? $_SESSION['usuario'] ?? $_SESSION['nombre'] ?? '';

if (empty($usuario_actual)) {
    die("<div class='alert alert-danger m-4'>Error: Sesión no válida o usuario no identificado.</div>");
}

// 2. OBTENER DATOS DEL CONDUCTOR (Importante: Pedimos 'licencia')
$stmt_c = $conexion->prepare("SELECT conductor_id, nombre, apellido, licencia FROM conductores WHERE nombre = ? LIMIT 1");
$stmt_c->bind_param("s", $usuario_actual);
$stmt_c->execute();
$cond_data = $stmt_c->get_result()->fetch_assoc();
$stmt_c->close();

if (!$cond_data) {
    die("<div class='container py-5'><h3>Error de Perfil</h3><p>El usuario conectado ('$usuario_actual') no tiene un perfil en la tabla 'conductores'. Por favor, contacta al administrador.</p><a href='../logout.php' class='btn btn-primary'>Salir</a></div>");
}

$conductor_id = $cond_data['conductor_id'];
$nombre_completo = $cond_data['nombre'] . " " . $cond_data['apellido'];

// 3. OBTENER RUTAS ACTIVAS
// Filtramos por conductor y rutas que estén 'Asignado' (no finalizadas)
$sql = "SELECT r.ruta_id, r.pedido_id, r.furgoneta_id, r.estado as estado_ruta,
               p.total, p.estado as estado_pedido,
               c.nombre as cliente_nom, c.apellidos as cliente_ape, c.direccion, c.telefono,
               f.placa, f.modelo
        FROM rutas r
        JOIN pedidos p ON r.pedido_id = p.pedido_id
        JOIN clientes c ON p.cliente_id = c.cliente_id
        JOIN furgonetas f ON r.furgoneta_id = f.furgoneta_id
        WHERE r.conductor_id = ? AND r.estado = 'Asignado'
        ORDER BY r.fecha_hora_salida ASC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $conductor_id);
$stmt->execute();
$rutas = $stmt->get_result();
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>LavaDito Driver</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600&display=swap" rel="stylesheet">
    
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        
        
        .brand-text { font-family: 'Fredoka', sans-serif; font-size: 26px; color: #9ecfff; -webkit-text-stroke: 1px #2c5282; margin-left: 8px; }
        .navbar-brand { display: flex; align-items: center; }
        
        
        .card-ruta { border-left: 6px solid #0d6efd; border-radius: 15px; transition: transform 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .card-ruta:hover { transform: translateY(-2px); box-shadow: 0 10px 15px rgba(0,0,0,0.1); }
        

        .btn-mapa { background-color: #e8f5fe; color: #0d6efd; border: 1px solid ; text-decoration: none; display: block; padding: 12px; border-radius: 10px; transition: all 0.2s; }
        .btn-mapa:hover { background-color: #d0ebff; color: #0a58ca; text-decoration: none; }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<nav class="navbar navbar-dark bg-dark shadow-sm mb-3">
  <div class="container">
    <a class="navbar-brand" href="#">
        <img src="../img/logo-transparente.png" alt="Logo" height="45">
        <span class="brand-text">LavaDito Driver</span>
    </a>
    <a href="../logout.php" class="btn btn-outline-light btn-sm">Salir</a>
  </div>
</nav>

<div class="container pb-5">
    
    <div class="card border-0 shadow-sm mb-4 bg-white">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
            <div class="d-flex align-items-center mb-3 mb-md-0">
                <div class="bg-light p-3 rounded-circle me-3">
                    <i class="bi bi-person-badge-fill fs-1 text-primary"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold"><?= htmlspecialchars($nombre_completo) ?></h4>
                    <small class="text-muted">Licencia: <span class="badge bg-secondary"><?= htmlspecialchars($cond_data['licencia'] ?? 'Pendiente') ?></span></small>
                </div>
            </div>
            
            <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#formLicencia">
                <i class="bi bi-pencil-square"></i> Actualizar Licencia
            </button>
        </div>
        
        <div class="collapse" id="formLicencia">
            <div class="card-footer bg-light border-top-0">
                <form action="actualizar_licencia.php" method="POST" class="row g-2 align-items-end">
                    <input type="hidden" name="conductor_id" value="<?= $conductor_id ?>">
                    <div class="col-md-8">
                        <label class="form-label small fw-bold text-muted">Número de Licencia</label>
                        <input type="text" name="licencia" class="form-control" required placeholder="Ej: A-12345678" value="<?= htmlspecialchars($cond_data['licencia']) ?>">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary w-100 fw-bold">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <h5 class="mb-3 text-secondary fw-bold"><i class="bi bi-list-task"></i> Rutas Asignadas</h5>

    <?php if ($rutas->num_rows > 0): ?>
        <div class="row g-4">
            <?php while ($r = $rutas->fetch_assoc()): 
                // Generar link de Google Maps
                $mapa_link = "https://www.google.com/maps/search/?api=1&query=" . urlencode($r['direccion']);
                $estado_p = $r['estado_pedido'];
                
                // Color de borde según tipo de tarea
                $color_borde = '#0d6efd'; 
                if($estado_p == 'aceptado' || $estado_p == 'en_camino_recoleccion') $color_borde = '#ffc107'; // Amarillo (Recolección)
                if($estado_p == 'terminado' || $estado_p == 'en_ruta_entrega' || $estado_p == 'en_ruta') $color_borde = '#198754'; // Verde (Entrega)
            ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card card-ruta h-100" style="border-left-color: <?= $color_borde ?>;">
                        <div class="card-body d-flex flex-column">
                            
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="badge bg-primary rounded-pill">Pedido #<?= $r['pedido_id'] ?></span>
                                <span class="badge bg-light text-dark border fw-bold text-uppercase"><?= str_replace('_', ' ', $estado_p) ?></span>
                            </div>
                            
                            <h5 class="card-title fw-bold mb-1"><?= htmlspecialchars($r['cliente_nom'] . ' ' . $r['cliente_ape']) ?></h5>
                            
                            <div class="mb-3 mt-2">
                                <a href="<?= $mapa_link ?>" target="_blank" class="btn-mapa shadow-sm">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-geo-alt-fill fs-3 me-3 text-danger"></i>
                                        <div class="w-100 overflow-hidden">
                                            <div class="fw-bold text-dark lh-sm text-truncate"><?= htmlspecialchars($r['direccion']) ?></div>
                                            <small class="text-primary fw-bold">Toca para navegar <i class="bi bi-box-arrow-up-right"></i></small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="alert alert-light border py-2 px-3 mb-3 small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span><i class="bi bi-telephone"></i> Tel:</span>
                                    <strong><a href="tel:<?= htmlspecialchars($r['telefono']) ?>" class="text-decoration-none"><?= htmlspecialchars($r['telefono']) ?></a></strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span><i class="bi bi-cash"></i> Total:</span>
                                    <span class="fw-bold text-success">$<?= number_format($r['total'], 2) ?></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span><i class="bi bi-truck"></i> Auto:</span>
                                    <strong><?= htmlspecialchars($r['modelo']) ?></strong>
                                </div>
                            </div>

                            <div class="mt-auto">
                                <form action="procesar_ruta.php" method="POST">
                                    <input type="hidden" name="ruta_id" value="<?= $r['ruta_id'] ?>">
                                    <input type="hidden" name="pedido_id" value="<?= $r['pedido_id'] ?>">
                                    <input type="hidden" name="conductor_id" value="<?= $conductor_id ?>">
                                    <input type="hidden" name="furgoneta_id" value="<?= $r['furgoneta_id'] ?>">
                                    <input type="hidden" name="estado_actual" value="<?= $estado_p ?>">

                                    <?php if ($estado_p == 'aceptado'): ?>
                                        <button type="submit" class="btn btn-primary w-100 shadow-sm py-3 fw-bold">
                                            <i class="bi bi-scooter me-2"></i> INICIAR RECOLECCIÓN
                                        </button>

                                    <?php elseif ($estado_p == 'en_camino_recoleccion'): ?>
                                        <div class="alert alert-warning py-1 text-center small mb-2">Estás en camino al cliente</div>
                                        <button type="submit" class="btn btn-warning w-100 shadow-sm py-3 fw-bold" onclick="return confirm('¿Ya tienes la ropa contigo?');">
                                            <i class="bi bi-box-seam me-2"></i> CONFIRMAR RECOGIDA
                                        </button>

                                    <?php elseif ($estado_p == 'terminado' || $estado_p == 'en_proceso'): ?>
                                        <div class="alert alert-info py-1 text-center small mb-2">¡Ropa lista para entregar!</div>
                                        <button type="submit" class="btn btn-info text-white w-100 shadow-sm py-3 fw-bold">
                                            <i class="bi bi-truck me-2"></i> INICIAR RUTA ENTREGA
                                        </button>

                                    <?php elseif ($estado_p == 'en_ruta_entrega' || $estado_p == 'en_ruta'): ?>
                                        <div class="alert alert-success py-1 text-center small mb-2">Llevando ropa limpia</div>
                                        <button type="submit" class="btn btn-success w-100 shadow-sm py-3 fw-bold" onclick="return confirm('¿Entregaste el pedido al cliente?');">
                                            <i class="bi bi-check-circle-fill me-2"></i> FINALIZAR ENTREGA
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </div>
                            
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5 my-5">
            <div class="mb-3 text-muted opacity-25">
                <i class="bi bi-emoji-smile display-1"></i>
            </div>
            <h3 class="text-muted fw-light">¡Todo listo por ahora!</h3>
            <p class="text-muted">No tienes rutas asignadas en este momento.</p>
            <a href="view_conductor.php" class="btn btn-outline-primary rounded-pill px-4 mt-2">
                <i class="bi bi-arrow-clockwise"></i> Actualizar
            </a>
        </div>
    <?php endif; ?>
</div>

<footer class="bg-dark text-white py-5 mt-auto">
    <div class="container">
        <div class="row text-center">
            <div class="col-12 mb-3">
                <img src="../img/logo-transparente.png" alt="Logo" height="50" style="filter: brightness(0) invert(1);">
                <p class="mt-2 text-secondary small">Tu herramienta de trabajo.</p>
            </div>
        </div>
        <div class="text-center text-secondary small border-top border-secondary pt-3">
            &copy; 2025 LavaDito Driver.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>