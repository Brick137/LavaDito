<?php
    include 'conexion.php';
    
    // IMPORTANTE: Solo mostrar servicios que son del catálogo (no asignados a un pedido específico)
    $query = "SELECT * FROM servicios WHERE pedido_id IS NULL ORDER BY servicio_id ASC";
    $result = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestros Servicios - LavaDito</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/consultas.css">
    
    <style>
        .nav-container { background: #fff; padding: 10px; margin-bottom: 20px; }
        .card-img-top { height: 200px; object-fit: cover; background-color: #eee; } /* Placeholder para imagen */
    </style>
</head>

<body class="bg-light">

    <div class="nav-container shadow-sm">
        <div class="d-flex justify-content-between align-items-center px-4">
            <div class="d-flex align-items-center">
                <img src="img/logo1.png" alt="Logo" height="50" class="me-2">
                <h1 class="h4 m-0">LavaDito</h1>
            </div>
            <nav>
                <ul class="list-unstyled d-flex gap-3 m-0">
                    <li><a href="bienvenida.php" class="text-decoration-none text-dark">Inicio</a></li>
                    <li><a href="carrito.php" class="text-decoration-none text-dark fw-bold">Ver Carrito 🛒</a></li>
                    <li><a href="contacto.php" class="text-decoration-none text-dark">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </div>

    <main class="container py-4">
        <h2 class="text-center mb-4">Elige tus servicios</h2>

        <div class="row">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($mostrar = mysqli_fetch_assoc($result)) {
            ?>
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title m-0 text-center"><?php echo htmlspecialchars($mostrar['tipo_servicio']); ?></h5>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-3 text-center">
                                <h3 class="text-primary fw-bold">$<?php echo number_format($mostrar['precio'], 2); ?></h3>
                                <p class="text-muted">Peso ref: <?php echo htmlspecialchars($mostrar['peso']); ?> kg</p>
                            </div>

                            <form action="agregar_carrito.php" method="POST" class="mt-auto">
                                
                                <input type="hidden" name="id" value="<?php echo $mostrar['servicio_id']; ?>">
                                
                                <div class="d-flex gap-2">
                                    <input type="number" name="cantidad" value="1" min="1" class="form-control" placeholder="Cant." required>
                                    
                                    <button type="submit" class="btn btn-success w-100">Agregar 🛒</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            <?php
                }
            } else {
                echo "<div class='col-12'><div class='alert alert-warning'>No hay servicios disponibles en el catálogo.</div></div>";
            }
            ?>
        </div>
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-1">Castillo Alcantar Diego | Hernández Pérez José Luis</p>
            <p class="mb-0">Limón Jiménez Jorge Alberto | Vázquez López Ismael</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>