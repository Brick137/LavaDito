<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - LavaDito</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #eef2f3 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 76px; /* Espacio para el navbar fijo */
        }
        
        /* Navbar estilo Glass */
        .navbar {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
        }
        
        /* Estilo de la tarjeta de Login */
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .login-header {
            background-color: #0d6efd;
            color: white;
            padding: 2rem 1rem;
            text-align: center;
        }

        .btn-primary {
            padding: 10px 20px;
            font-weight: bold;
        }

        main {
            flex: 1; /* Empuja el footer hacia abajo */
            display: flex;
            align-items: center; /* Centra verticalmente */
            justify-content: center;
            padding: 40px 20px;
        }
        /* Estilo para el texto del logo idéntico a la imagen */
    .brand-text {
        font-family: 'Fredoka', sans-serif; /* Fuente redondeada */
        font-size: 32px; /* Tamaño grande */
        font-weight: 600; /* Grosor */
        
        /* EL TRUCO PARA EL COLOR Y BORDE */
        color: #9ecfff; /* Color de relleno (Celeste claro) */
        -webkit-text-stroke: 1.5px #2c5282; /* Borde del texto (Azul oscuro) */
        
        margin-left: 10px; /* Separación de la imagen */
        letter-spacing: 1px; /* Espacio entre letras */
        line-height: 1; /* Ajuste de altura */
    }
    
    /* Ajuste para que la imagen y el texto estén alineados perfectamente */
    .navbar-brand {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    </style>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg fixed-top shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="index.html">
                    <img src="img/logo-transparente.png" alt="LavaDito" height="40">
                    <span class="brand-text">LavaDito</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item"><a class="nav-link" href="index.html">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 col-lg-4">
                    
                    <div class="card login-card">
                        <div class="login-header">
                            <i class="fa-solid fa-user-circle fa-3x mb-2"></i>
                            <h4>Bienvenido</h4>
                            <p class="mb-0 opacity-75">Ingresa a tu cuenta</p>
                        </div>

                        <div class="card-body p-4 p-md-5">
                            <form action="login.php" method="POST">
                                
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" required>
                                    <label for="usuario"><i class="fa-solid fa-user me-2 text-muted"></i>Usuario</label>
                                </div>

                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control" id="clave" name="clave" placeholder="Contraseña" required>
                                    <label for="clave"><i class="fa-solid fa-lock me-2 text-muted"></i>Contraseña</label>
                                </div>

                                <div class="d-grid gap-2 mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                        Confirmar Acceso
                                    </button>
                                </div>

                                <div class="text-center border-top pt-3">
                                    <span class="text-muted small">¿No tienes cuenta?</span><br>
                                    <a href="registrar_usuarios.html" class="text-decoration-none fw-bold text-primary">
                                        Registrarse aquí
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container text-center">
            <p class="text-uppercase text-primary fw-bold mb-3 small">Equipo de Desarrollo</p>
            <div class="row justify-content-center small text-secondary">
                <div class="col-md-3 mb-2">Castillo Alcantar Diego</div>
                <div class="col-md-3 mb-2">Hernández Pérez José Luis</div>
                <div class="col-md-3 mb-2">Limón Jiménez Jorge Alberto</div>
                <div class="col-md-3 mb-2">Vázquez López Ismael</div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>