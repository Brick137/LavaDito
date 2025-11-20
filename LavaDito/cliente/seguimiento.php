<?php
require_once '../session.php';
require_role('cliente');
require_once '../conexion.php';

$pedido_id = intval($_GET['pedido'] ?? 0);
if ($pedido_id <= 0) { echo "Pedido inválido."; exit; }

// 1. OBTENER DATOS
$stmt = $conexion->prepare("SELECT p.*, c.nombre as cliente_nombre, c.direccion FROM pedidos p LEFT JOIN clientes c ON p.cliente_id = c.cliente_id WHERE p.pedido_id = ? LIMIT 1");
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$pedido = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$pedido) { echo "Pedido no encontrado."; exit; }

// 2. DEFINIR EL MAPA DE ESTADOS EXACTO
$pasos = [
    'pendiente'             => ['titulo' => 'Solicitud Enviada', 'desc' => 'Hemos recibido tu pedido.', 'icono' => 'bi-cloud-upload'],
    'aceptado'              => ['titulo' => 'Conductor Asignado', 'desc' => 'Un conductor ha aceptado tu solicitud.', 'icono' => 'bi-person-check-fill'],
    'en_camino_recoleccion' => ['titulo' => 'En Camino a Recoger', 'desc' => 'El conductor va por tu ropa.', 'icono' => 'bi-scooter'],
    'recogido'              => ['titulo' => 'Ropa Recogida', 'desc' => 'Tu ropa llegó a la lavandería.', 'icono' => 'bi-box-seam'],
    'en_proceso'            => ['titulo' => 'Lavando', 'desc' => 'Estamos cuidando tus prendas.', 'icono' => 'bi-water'],
    'terminado'             => ['titulo' => 'Listo para Envío', 'desc' => 'Ropa limpia y empacada.', 'icono' => 'bi-stars'],
    'en_ruta_entrega'       => ['titulo' => 'En Camino a Entregar', 'desc' => 'Tu ropa limpia va en camino.', 'icono' => 'bi-truck'],
    'entregado'             => ['titulo' => 'Pedido Entregado', 'desc' => '¡Gracias por tu preferencia!', 'icono' => 'bi-house-heart-fill']
];

// 3. NORMALIZAR ESTADO (El "Traductor" para corregir el error visual)
$estado_db = strtolower($pedido['estado']);
$estado_visual = $estado_db; // Por defecto es igual

// Correcciones manuales de nombres antiguos o confusos
if ($estado_db == 'en_ruta') { $estado_visual = 'en_ruta_entrega'; }
if ($estado_db == 'preparando') { $estado_visual = 'en_proceso'; }

$orden_estados = array_keys($pasos);
$indice_actual = array_search($estado_visual, $orden_estados);

// Si no encuentra el estado, lo mandamos al inicio por seguridad
if ($indice_actual === false) $indice_actual = 0;

// Calcular porcentaje barra
$porcentaje = ($indice_actual / (count($pasos) - 1)) * 100;
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Seguimiento #<?= $pedido_id ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600&display=swap" rel="stylesheet">
<style>
    body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
    .brand-text { font-family: 'Fredoka', sans-serif; font-size: 24px; color: #9ecfff; -webkit-text-stroke: 1px #2c5282; }
    .card-seguimiento { border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .header-pedido { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white; padding: 30px 20px; text-align: center; }
    
    /* TIMELINE */
    .timeline { position: relative; padding: 20px 0; margin-top: 20px; }
    .timeline::before { content: ''; position: absolute; top: 0; bottom: 0; left: 24px; width: 3px; background: #e9ecef; z-index: 1; }
    .timeline-item { position: relative; padding-left: 80px; margin-bottom: 40px; z-index: 2; }
    .timeline-icon { position: absolute; left: 0; top: 0; width: 50px; height: 50px; border-radius: 50%; background: white; border: 3px solid #e9ecef; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #adb5bd; z-index: 3; }
    
    .timeline-item.active .timeline-icon { background: #198754; border-color: #198754; color: white; }
    .timeline-item.current .timeline-icon { background: #0d6efd; border-color: #0d6efd; color: white; animation: pulse 2s infinite; }
    @keyframes pulse { 0% {box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4);} 70% {box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);} 100% {box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);} }
</style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm mb-4">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="view_user.php">
        <img src="../img/logo-transparente.png" height="40"> <span class="brand-text ms-2">LavaDito</span>
    </a>
    <a href="mis_pedidos.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Mis Pedidos</a>
  </div>
</nav>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card card-seguimiento">
                <div class="header-pedido">
                    <h2 class="fw-bold">Pedido #<?= $pedido_id ?></h2>
                    <p class="opacity-75 mb-3 text-uppercase fw-bold"><?= str_replace('_', ' ', $estado_visual) ?></p>
                    <div class="progress bg-white bg-opacity-25" style="height: 8px; border-radius: 10px;">
                        <div class="progress-bar bg-warning" style="width: <?= $porcentaje ?>%"></div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        <?php foreach ($pasos as $key => $info): 
                            $idx = array_search($key, $orden_estados);
                            $clase = ($idx < $indice_actual) ? 'active' : (($idx == $indice_actual) ? 'current' : '');
                        ?>
                        <div class="timeline-item <?= $clase ?>">
                            <div class="timeline-icon">
                                <i class="bi <?= ($clase=='active') ? 'bi-check-lg' : $info['icono'] ?>"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark"><?= $info['titulo'] ?></strong>
                                <small class="text-muted"><?= $info['desc'] ?></small>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="bg-light p-3 rounded border d-flex mt-3">
                        <i class="bi bi-geo-alt-fill text-danger fs-4 me-3"></i>
                        <div><h6 class="mb-0">Dirección</h6><small class="text-muted"><?= htmlspecialchars($pedido['direccion']) ?></small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>