<?php
require_once '../session.php';
require_role('cliente');
require_once '../conexion.php';

$cliente_id = $_SESSION['cliente_id'];

// 1. FUNCIÓN PARA ELEGIR LA IMAGEN SEGÚN EL NOMBRE (Sin tocar BD)
function obtenerImagenServicio($nombre) {
    $n = mb_strtolower($nombre, 'UTF-8'); 
    
    // Lavado -> Lavadora
    if (strpos($n, 'lavado') !== false || strpos($n, 'lavar') !== false) {
        return 'https://img.global.news.samsung.com/pe/wp-content/uploads/2018/05/162-1024x512.jpg';
    }
    // Planchado -> Plancha
    if (strpos($n, 'planchado') !== false || strpos($n, 'plancha') !== false) {
        return 'https://blog.trapitos.com.ar/uploads/2018/10/plancado-de-tela-para-funda-de-edredon.jpg';
    }
    // Secado -> Sol / Secadora
    if (strpos($n, 'secado') !== false) {
        return 'https://nerguadalajara.com/wp-content/uploads/2025/01/secadoras_de_ropa_de_gas-700x500.jpg';
    }
    // Edredón / Cama -> Cama
    if (strpos($n, 'Edredón') !== false || strpos($n, 'Edredón') !== false) {
        return 'https://i5.walmartimages.com/asr/334160c2-49fa-4d87-a7c7-8003a6acffc8.2c369495e8463be5829ad24676d1ca63.jpeg?odnHeight=612&odnWidth=612&odnBg=FFFFFF';
    }
    // Trajes / Tintorería -> Traje
    if (strpos($n, 'tintoreria') !== false || strpos($n, 'traje') !== false) {
        return 'https://www.consumoteca.com/wp-content/uploads/Tintorer%C3%ADa-reclamaci%C3%B3n.jpg';
    }
    
    // Imagen por defecto (Canasta de ropa)
    return 'https://cdn-icons-png.flaticon.com/512/2933/2933116.png'; 
}

// Obtener nombre del cliente para el saludo
$stmt = $conexion->prepare("SELECT nombre, apellidos FROM clientes WHERE cliente_id = ?");
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$res = $stmt->get_result();
$cliente = $res->fetch_assoc();
$stmt->close();

// Catálogo: servicios disponibles
$servs = $conexion->query("SELECT servicio_id, tipo_servicio, precio, peso FROM servicios WHERE pedido_id IS NULL ORDER BY tipo_servicio ASC");
?>

<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Cliente - LavaDito</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
    
    .navbar-brand { font-family: 'Fredoka', sans-serif; font-size: 26px; display: flex; align-items: center; gap: 10px; }
    .brand-text { color: #9ecfff; -webkit-text-stroke: 1px #2c5282; }
    
    
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        border-radius: 15px;
        overflow: hidden; 
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    
    .card-img-wrapper {
        background-color: #eef2f3; 
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .card-img-top {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain; 
        filter: drop-shadow(0 5px 5px rgba(0,0,0,0.1));
    }
</style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
  <div class="container">
    <a class="navbar-brand" href="#">
        <img src="../img/clientito-removebg-preview.png" alt="Logo" height="55"> 
        <span class="brand-text">LavaDito</span>
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCliente">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCliente">
        <div class="ms-auto d-flex align-items-center gap-2 mt-3 mt-lg-0 flex-wrap">
            <span class="text-white d-none d-lg-block me-2">Hola, <?= htmlspecialchars($cliente['nombre'] ?? $_SESSION['usuario_name']) ?></span>
            
            <a href="mis_pedidos.php" class="btn btn-outline-light btn-sm fw-bold">
                <i class="bi bi-clock-history"></i> Mis Pedidos
            </a>

            <a href="carrito.php" class="btn btn-light btn-sm fw-bold text-primary">
                <i class="bi bi-cart-fill"></i> Ver Carrito
            </a>
            
            <a class="btn btn-outline-light btn-sm" href="../logout.php">Salir</a>
        </div>
    </div>
  </div>
</nav>

<div class="container py-4">
  <div class="text-center mb-5">
    <h2 class="fw-bold text-dark">Nuestros Servicios</h2>
    <p class="text-muted">Selecciona los servicios que deseas agregar a tu pedido</p>
  </div>

  <div class="row g-4">
    <?php while ($row = $servs->fetch_assoc()): 
        // AQUI LLAMAMOS A LA FUNCIÓN PARA LA IMAGEN
        $imagenUrl = obtenerImagenServicio($row['tipo_servicio']);
    ?>
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
            
            <div class="card-img-wrapper">
                <img src="<?= $imagenUrl ?>" class="card-img-top" alt="<?= htmlspecialchars($row['tipo_servicio']) ?>">
            </div>

            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title fw-bold text-primary mb-0"><?= htmlspecialchars($row['tipo_servicio']) ?></h5>
                    <h4 class="fw-bold text-dark mb-0">$<?= number_format($row['precio'], 2) ?></h4>
                </div>
                
                <div class="mb-3">
                    <span class="badge bg-info text-dark">
                        <i class="bi bi-basket"></i> Peso ref: <?= htmlspecialchars($row['peso']) ?> kg
                    </span>
                </div>
                
                <p class="card-text small text-muted mb-4">
                    Servicio profesional de lavado y cuidado para tus prendas.
                </p>
                
                <div class="mt-auto">
                    <form action="agregar_carrito.php" method="post" class="row g-2">
                        <input type="hidden" name="id" value="<?= intval($row['servicio_id']) ?>">
                        
                        <div class="col-4">
                            <input type="number" name="cantidad" value="1" min="1" class="form-control text-center fw-bold">
                        </div>
                        <div class="col-8">
                            <button class="btn btn-success w-100 fw-bold shadow-sm">
                                Agregar <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
      </div>
    <?php endwhile; ?>
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