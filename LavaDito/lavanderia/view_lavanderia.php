<?php
require_once '../session.php';
require_role('lavanderia');
require_once '../conexion.php';


// 1. Pedidos pendientes
$sql_pedidos = "SELECT COUNT(*) as total FROM pedidos WHERE estado = 'pendiente'";
$res_pedidos = $conexion->query($sql_pedidos);
$total_pedidos = $res_pedidos ? $res_pedidos->fetch_assoc()['total'] : 0;

// 2. Total Clientes
$sql_clientes = "SELECT COUNT(*) as total FROM clientes";
$res_clientes = $conexion->query($sql_clientes);
$total_clientes = $res_clientes ? $res_clientes->fetch_assoc()['total'] : 0;

// 3. Conductores Activos (Asumiendo que tienes estados como 'Activo' o 'Libre')
$sql_conductores = "SELECT COUNT(*) as total FROM conductores"; 
$res_conductores = $conexion->query($sql_conductores);
$total_conductores = $res_conductores ? $res_conductores->fetch_assoc()['total'] : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin - LavaDito</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600&display=swap" rel="stylesheet">
    
    <style>
        body { background-color: #f8f9fa; }
        .brand-text { font-family: 'Fredoka', sans-serif; color: #9ecfff; -webkit-text-stroke: 1px #2c5282; font-size: 24px; }
        .card-dashboard { transition: transform 0.2s; border: none; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-decoration: none; color: inherit; }
        .card-dashboard:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .icon-box { font-size: 2.5rem; margin-bottom: 10px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="../img/logo-transparente.png" alt="Logo" height="40">
        <span class="brand-text ms-2">LavaDito Admin</span>
    </a>
    <div class="d-flex align-items-center">
        <span class="text-white me-3">Hola, Admin</span>
        <a href="../logout.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
    </div>
  </div>
</nav>

<div class="container">
    <h2 class="mb-4 fw-bold text-secondary">Panel de Control</h2>
    
    <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-4">
            <a href="ver_pedidos.php" class="card card-dashboard h-100 bg-primary text-white">
                <div class="card-body text-center p-4">
                    <div class="icon-box"><i class="bi bi-list-check"></i></div>
                    <h3>Pedidos</h3>
                    <p class="mb-0 fs-5">Pendientes: <strong><?= $total_pedidos ?></strong></p>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-6 col-lg-4">
            <a href="admin_servicios.php" class="card card-dashboard h-100 bg-white text-dark">
                <div class="card-body text-center p-4">
                    <div class="icon-box text-info"><i class="bi bi-tags-fill"></i></div>
                    <h5>Catálogo Servicios</h5>
                    <p class="text-muted small">Precios y Productos</p>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-6 col-lg-4">
            <a href="ver_clientes.php" class="card card-dashboard h-100 bg-white text-dark">
                <div class="card-body text-center p-4">
                    <div class="icon-box text-success"><i class="bi bi-people-fill"></i></div>
                    <h5>Clientes</h5>
                    <p class="text-muted small">Total: <?= $total_clientes ?></p>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-3">
            <a href="ver_conductores.php" class="card card-dashboard h-100 bg-white text-dark">
                <div class="card-body text-center p-4">
                    <div class="icon-box text-warning"><i class="bi bi-person-vcard"></i></div>
                    <h6>Conductores</h6>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-4 col-lg-3">
            <a href="ver_furgoneta.php" class="card card-dashboard h-100 bg-white text-dark">
                <div class="card-body text-center p-4">
                    <div class="icon-box text-secondary"><i class="bi bi-truck"></i></div>
                    <h6>Furgonetas</h6>
                </div>
            </a>
        </div>
        
        <div class="col-6 col-md-4 col-lg-3">
            <a href="ver_pagos.php" class="card card-dashboard h-100 bg-white text-dark">
                <div class="card-body text-center p-4">
                    <div class="icon-box text-success"><i class="bi bi-cash-stack"></i></div>
                    <h6>Pagos</h6>
                </div>
            </a>
        </div>
        
        <div class="col-6 col-md-4 col-lg-3">
            <a href="ver_rutas.php" class="card card-dashboard h-100 bg-white text-dark">
                <div class="card-body text-center p-4">
                    <div class="icon-box text-danger"><i class="bi bi-map-fill"></i></div>
                    <h6>Rutas</h6>
                </div>
            </a>
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