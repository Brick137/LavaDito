<?php
require_once '../session.php';
require_role('cliente');
require_once '../conexion.php';

// 1. LÓGICA PARA VACIAR
if (isset($_GET['vaciar'])) {
    $stmt = $conexion->prepare("DELETE FROM carrito WHERE usuario_id = ?");
    $stmt->bind_param("i", $_SESSION['usuario_id']);
    $stmt->execute();
    $stmt->close();
    header("Location: carrito.php");
    exit;
}

// 2. FUNCIÓN DE IMÁGENES (Misma lógica visual)
function obtenerImagenServicio($nombre) {
    $n = mb_strtolower($nombre, 'UTF-8'); 
    if (strpos($n, 'lavado') !== false || strpos($n, 'lavar') !== false) return 'https://img.global.news.samsung.com/pe/wp-content/uploads/2018/05/162-1024x512.jpg';
    if (strpos($n, 'planchado') !== false || strpos($n, 'plancha') !== false) return 'https://blog.trapitos.com.ar/uploads/2018/10/plancado-de-tela-para-funda-de-edredon.jpg';
    if (strpos($n, 'secado') !== false) return 'https://nerguadalajara.com/wp-content/uploads/2025/01/secadoras_de_ropa_de_gas-700x500.jpg';
    if (strpos($n, 'edredon') !== false || strpos($n, 'cama') !== false) return 'https://i5.walmartimages.com/asr/334160c2-49fa-4d87-a7c7-8003a6acffc8.2c369495e8463be5829ad24676d1ca63.jpeg';
    if (strpos($n, 'tintoreria') !== false || strpos($n, 'traje') !== false) return 'https://www.consumoteca.com/wp-content/uploads/Tintorer%C3%ADa-reclamaci%C3%B3n.jpg';
    return 'https://cdn-icons-png.flaticon.com/512/2933/2933116.png'; 
}

// 3. OBTENER CARRITO
$cartRes = $conexion->prepare("SELECT c.id, c.servicio_id, c.cantidad, c.subtotal, s.tipo_servicio, s.precio, s.peso FROM carrito c JOIN servicios s ON c.servicio_id = s.servicio_id WHERE c.usuario_id = ?");
$cartRes->bind_param("i", $_SESSION['usuario_id']);
$cartRes->execute();
$cart = $cartRes->get_result();
$cartRes->close();

$total = 0;
$items = []; 
while ($row = $cart->fetch_assoc()) {
    $items[] = $row;
    $total += floatval($row['subtotal']);
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Mi Carrito - LavaDito</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600&display=swap" rel="stylesheet">
    
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .brand-text { font-family: 'Fredoka', sans-serif; font-size: 24px; color: #9ecfff; -webkit-text-stroke: 1px #2c5282; }
        
        .cart-item { border-left: 4px solid transparent; transition: all 0.2s; }
        .cart-item:hover { border-left-color: #0d6efd; background-color: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        
        .img-servicio-carrito {
            width: 70px; height: 70px; object-fit: cover; border-radius: 10px; border: 1px solid #dee2e6;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-light bg-white shadow-sm mb-4 sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="view_user.php">
        <img src="../img/logo-transparente.png" alt="Logo" height="45">
        <span class="brand-text ms-2">LavaDito</span>
    </a>
    
    <div class="d-flex gap-2">
        <a href="mis_pedidos.php" class="btn btn-outline-secondary btn-sm rounded-pill fw-bold d-none d-md-block">
            <i class="bi bi-clock-history"></i> Mis Pedidos
        </a>
        <a href="view_user.php" class="btn btn-outline-primary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left"></i> Seguir comprando
        </a>
    </div>
  </div>
</nav>

<div class="container pb-5">
  <h2 class="mb-4 fw-light">Tu Carrito de Compras</h2>

  <div class="row g-4">
    
    <div class="col-12 col-lg-8">
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 text-primary"><i class="bi bi-basket3 me-2"></i>Servicios seleccionados</h5>
        </div>
        <div class="card-body p-0">
          
          <?php if (count($items) === 0): ?>
            <div class="text-center py-5">
                <i class="bi bi-cart-x display-1 text-muted opacity-25"></i>
                <p class="text-muted fs-5 mt-3">Tu carrito está vacío</p>
                <a href="view_user.php" class="btn btn-primary rounded-pill px-4">Ir al catálogo</a>
            </div>
          <?php else: ?>
            
            <div class="list-group list-group-flush">
              <?php foreach ($items as $it): 
                  $imagenUrl = obtenerImagenServicio($it['tipo_servicio']);
              ?>
                <div class="list-group-item p-3 cart-item">
                  <div class="d-flex align-items-center flex-wrap flex-md-nowrap">
                    
                    <div class="me-3 mb-2 mb-md-0">
                        <img src="<?= $imagenUrl ?>" class="img-servicio-carrito" alt="Servicio">
                    </div>

                    <div class="flex-grow-1 mb-2 mb-md-0">
                        <h6 class="mb-1 fw-bold text-dark"><?= htmlspecialchars($it['tipo_servicio']) ?></h6>
                        <div class="small text-muted">
                            <span class="me-3"><i class="bi bi-tag"></i> $<?= number_format($it['precio'], 2) ?> c/u</span>
                            <span class="badge bg-light text-dark border"><i class="bi bi-weight"></i> <?= htmlspecialchars($it['peso']) ?> kg</span>
                        </div>
                    </div>

                    <div class="me-md-4 mb-2 mb-md-0">
                        <form action="actualizar_carrito.php" method="post" class="d-flex align-items-center">
                            <input type="hidden" name="id" value="<?= intval($it['id']) ?>">
                            
                            <div class="input-group input-group-sm" style="width: 130px;">
                                <span class="input-group-text bg-white text-muted">Cant.</span>
                                <input type="number" name="cantidad" value="<?= intval($it['cantidad']) ?>" min="1" class="form-control text-center fw-bold">
                                <button class="btn btn-outline-secondary" title="Actualizar">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="text-end ms-auto ms-md-0" style="min-width: 90px;">
                        <div class="fw-bold fs-5 text-primary mb-1">$<?= number_format($it['subtotal'], 2) ?></div>
                        <a href="eliminar_carrito.php?id=<?= intval($it['id']) ?>" class="text-danger text-decoration-none small">
                            <i class="bi bi-trash"></i> Eliminar
                        </a>
                    </div>

                  </div>
                </div>
              <?php endforeach; ?>
            </div>

          <?php endif; ?>
        </div>
        
        <?php if (count($items) > 0): ?>
            <div class="card-footer bg-light text-end py-3 border-0">
                <a href="carrito.php?vaciar=1" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('¿Estás seguro de vaciar todo?')">
                    <i class="bi bi-trash3-fill"></i> Vaciar Carrito
                </a>
            </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 90px; z-index: 1;">
        <div class="card-body p-4">
          <h5 class="fw-bold mb-4">Resumen del Pedido</h5>
          
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Subtotal</span>
            <span>$<?= number_format($total, 2) ?></span>
          </div>
          <div class="d-flex justify-content-between mb-4">
            <span class="text-muted">Envío a domicilio</span>
            <span class="text-success fw-bold">GRATIS</span>
          </div>
          <div class="d-flex justify-content-between mb-4 p-3 bg-light rounded-3 border">
            <span class="h5 mb-0">Total a Pagar</span>
            <span class="h4 fw-bold text-primary mb-0">$<?= number_format($total, 2) ?></span>
          </div>

          <?php if (count($items) > 0): ?>
          <form action="confirmar_pedido.php" method="post">
            <h6 class="text-muted mb-3 small text-uppercase fw-bold"><i class="bi bi-geo-alt-fill"></i> Datos de Entrega</h6>
            
            <div class="form-floating mb-2">
              <input type="text" name="cliente_nombre" class="form-control bg-light border-0" id="floatingName" placeholder="Nombre" required>
              <label for="floatingName">Nombre quien recibe</label>
            </div>
            
            <div class="form-floating mb-2">
              <input type="tel" name="telefono" class="form-control bg-light border-0" id="floatingPhone" placeholder="Teléfono" required>
              <label for="floatingPhone">Teléfono</label>
            </div>
            
            <div class="form-floating mb-2">
              <textarea name="direccion" class="form-control bg-light border-0" id="floatingAddress" placeholder="Dirección" style="height: 70px" required></textarea>
              <label for="floatingAddress">Dirección completa</label>
            </div>
            
            <div class="form-floating mb-3">
              <input type="datetime-local" name="fecha_recoleccion" class="form-control bg-light border-0" id="floatingDate" required>
              <label for="floatingDate">Fecha de Recolección</label>
            </div>

            <input type="hidden" name="total" value="<?= htmlspecialchars($total) ?>">
            
            <button class="btn btn-primary w-100 py-3 fs-6 fw-bold rounded-pill shadow hover-scale">
                CONFIRMAR PEDIDO <i class="bi bi-chevron-right"></i>
            </button>
          </form>
          <?php else: ?>
            <div class="alert alert-warning text-center border-0 small">
                Agrega servicios para continuar.
            </div>
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