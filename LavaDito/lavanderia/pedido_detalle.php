<?php
require_once '../session.php';
require_role('lavanderia');
require_once '../conexion.php';

$pedido_id = intval($_GET['pedido'] ?? 0);
if ($pedido_id <= 0) { echo "Pedido inválido"; exit; }

// 1. OBTENER DATOS DEL PEDIDO Y CLIENTE
$stmt = $conexion->prepare("SELECT p.*, c.nombre, c.apellidos, c.direccion, c.telefono FROM pedidos p LEFT JOIN clientes c ON p.cliente_id = c.cliente_id WHERE p.pedido_id = ? LIMIT 1");
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();
$stmt->close();

// 2. OBTENER SERVICIOS
$servs = $conexion->prepare("SELECT servicio_id, tipo_servicio, precio, peso FROM servicios WHERE pedido_id = ?");
$servs->bind_param("i", $pedido_id);
$servs->execute();
$serv_res = $servs->get_result();
$servs->close();

// 3. OBTENER DATOS DE LA RUTA ACTIVA (Si hay chofer asignado)
$ruta_info = null;
$r_stmt = $conexion->prepare("SELECT r.*, c.nombre as ch_nom, c.apellido as ch_ape, f.placa, f.modelo 
                              FROM rutas r 
                              LEFT JOIN conductores c ON r.conductor_id = c.conductor_id 
                              LEFT JOIN furgonetas f ON r.furgoneta_id = f.furgoneta_id 
                              WHERE r.pedido_id = ? AND r.estado != 'Finalizado' LIMIT 1");
$r_stmt->bind_param("i", $pedido_id);
$r_stmt->execute();
$ruta_info = $r_stmt->get_result()->fetch_assoc();
$r_stmt->close();

// Listas para los selectores
$conductores = $conexion->query("SELECT conductor_id, nombre, apellido FROM conductores WHERE estado = 'Activo' OR estado = 'Libre'");
$furgonetas = $conexion->query("SELECT furgoneta_id, placa, modelo FROM furgonetas WHERE estado = 'Libre' OR estado IS NULL");
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Detalle Pedido #<?= $pedido_id ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-box-seam-fill text-primary"></i> Pedido #<?= $pedido_id ?></h3>
        <a href="ver_pedidos.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
    </div>

    <div class="alert alert-info shadow-sm border-0 mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-info-circle-fill fs-3 me-3"></i>
            <div>
                <strong>Estado Actual:</strong> <span class="text-uppercase badge bg-primary"><?= str_replace('_', ' ', $pedido['estado']) ?></span>
                <?php if ($ruta_info): ?>
                    <div class="mt-1 small">
                        <i class="bi bi-truck"></i> En ruta con: <strong><?= htmlspecialchars($ruta_info['ch_nom'] . ' ' . $ruta_info['ch_ape']) ?></strong>
                        (<?= htmlspecialchars($ruta_info['modelo']) ?>)
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-12 col-lg-8">
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-person-circle text-success"></i> Datos del Cliente
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Cliente</label>
                            <div class="fw-bold"><?= htmlspecialchars($pedido['nombre'] . ' ' . $pedido['apellidos']) ?></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Teléfono</label>
                            <div><a href="tel:<?= htmlspecialchars($pedido['telefono']) ?>" class="text-decoration-none"><?= htmlspecialchars($pedido['telefono']) ?></a></div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">Dirección de Recolección/Entrega</label>
                            <div class="p-2 bg-light rounded border"><?= htmlspecialchars($pedido['direccion']) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-basket3-fill text-info"></i> Servicios Solicitados
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Servicio</th>
                                <th>Peso Est.</th>
                                <th class="text-end">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($s = $serv_res->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($s['tipo_servicio']) ?></td>
                                <td><?= htmlspecialchars($s['peso']) ?> kg</td>
                                <td class="text-end">$<?= number_format($s['precio'],2) ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                        <tfoot class="table-group-divider">
                            <tr>
                                <td colspan="2" class="text-end fw-bold">TOTAL:</td>
                                <td class="text-end fw-bold text-success fs-5">$<?= number_format($pedido['total'],2) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Gestión de Estado</h6>
                    
                    <form action="actualizar_estado.php" method="post">
                        <input type="hidden" name="pedido_id" value="<?= $pedido_id ?>">
                        <label class="form-label small text-muted">Cambiar estado manualmente:</label>
                        <div class="input-group mb-3">
                            <select name="estado" class="form-select">
                                <option value="pendiente" <?= $pedido['estado']=='pendiente'?'selected':'' ?>>Pendiente</option>
                                <option value="aceptado" <?= $pedido['estado']=='aceptado'?'selected':'' ?>>Aceptado</option>
                                <option value="recogido" <?= $pedido['estado']=='recogido'?'selected':'' ?>>Recogido (En Planta)</option>
                                <option value="en_proceso" <?= $pedido['estado']=='en_proceso'?'selected':'' ?>>En Proceso (Lavando)</option>
                                <option value="terminado" <?= $pedido['estado']=='terminado'?'selected':'' ?>>Terminado (Listo)</option>
                                <option value="en_ruta" <?= $pedido['estado']=='en_ruta'?'selected':'' ?>>En Ruta Entrega</option>
                                <option value="entregado" <?= $pedido['estado']=='entregado'?'selected':'' ?>>Entregado Final</option>
                            </select>
                            <button class="btn btn-primary"><i class="bi bi-check-lg"></i></button>
                        </div>
                    </form>
                    
                    <?php if($pedido['estado'] == 'recogido'): ?>
                        <div class="alert alert-warning small">
                            <i class="bi bi-exclamation-circle"></i> El chofer ya trajo la ropa. Cambia a <strong>"En Proceso"</strong> para iniciar lavado.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-white">
                <div class="card-header bg-dark text-white">
                    <i class="bi bi-truck"></i> Asignar Transporte
                </div>
                <div class="card-body">
                    <?php if ($ruta_info): ?>
                        <div class="alert alert-success border-success small mb-0">
                            <strong>¡Ruta Activa!</strong><br>
                            Este pedido ya tiene un chofer asignado en este momento.
                        </div>
                    <?php else: ?>
                        <p class="small text-muted">Asigna un conductor para Recolección o Entrega.</p>
                        
                        <form action="asignar_conductor.php" method="post">
                            <input type="hidden" name="pedido_id" value="<?= $pedido_id ?>">
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Conductor</label>
                                <select name="conductor_id" class="form-select" required>
                                    <option value="">-- Seleccione Chofer --</option>
                                    <?php while ($c = $conductores->fetch_assoc()): ?>
                                        <option value="<?= $c['conductor_id'] ?>">
                                            <?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Furgoneta</label>
                                <select name="furgoneta_id" class="form-select" required>
                                    <option value="">-- Seleccione Vehículo --</option>
                                    <?php while ($f = $furgonetas->fetch_assoc()): ?>
                                        <option value="<?= $f['furgoneta_id'] ?>">
                                            <?= htmlspecialchars($f['placa'] . ' - ' . $f['modelo']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-success py-2">
                                    <i class="bi bi-save"></i> Asignar Ruta
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>