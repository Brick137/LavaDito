<?php

    include 'conexion.php';

    if($_SERVER ['REQUEST_METHOD'] === 'POST') {

        $nombre= mysqli_real_escape_string($conexion, $_POST['nombre']);
        $apellidos= mysqli_real_escape_string($conexion, $_POST['apellidos']);
        $telefono= mysqli_real_escape_string($conexion, $_POST['telefono']);
        $email= mysqli_real_escape_string($conexion, $_POST['email']);
        $direccion= mysqli_real_escape_string($conexion, $_POST['direccion']);

        $sql = "INSERT INTO clientes (nombre, apellidos, telefono, email, direccion)
                VALUES ('$nombre', '$apellidos', '$telefono', '$email', '$direccion')";
                
        if (mysqli_query($conexion, $sql)) {
            echo"<script>
                alert('Cliente registrado con éxito');
                window.location.href = 'registro_clientes.php';
            </script>";
        } else {
            echo"<script>
                alert('Error de registro');
                window.location.href = 'registro_clientes.php';
            </script>";
        }
    }

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LavaDito</title>
    <link rel="stylesheet" href="styles/regsitro.css">
</head>

<body>

      <div class="nav">
        <div class="box">
            <img src="img/logo1.png">
                <h1>LavaDito</h1>
        </div>

        <nav>
            <ul>
                <li><a href="bienvenida.php">Inicio</a></li>
                <li><a href="">Servicios</a></li>
                <li><a href="">Contacto</a></li>
                <li><a href="">Inicio de sesión</a></li>
            </ul>
        </nav>
    </div>

    <main>

        <h1>Registra clientes</h1>

        <form method="POST" action="registro_clientes.php">

        <label for="nombre">Nombre del cliente:</label>
        <input type="text" id="nombre" name="nombre" required>
        <br>
        <label for="apellidos">Apellidos del cliente:</label>
        <input type="text" id="apellidos" name="apellidos" required>
        <br>
        <label for="telefono">Telefono del cliente:</label>
        <input type="text" id="telefono" name="telefono" required>
        <br>
        <label for="email">Email del cliente:</label>
        <input type="text" id="email" name="email" required>
        <br>
        <label for="direccion">Dirección del cliente:</label>
        <input type="text" id="direccion" name="direccion" required>
        <br>
        <button type="submit">Registrar cliente</button>

        </form>

    </main>

    

    <footer>
        <h4>Castillo Alcantar Diego </h4> <br>
        <h4>Hernández Pérez José Luis</h4> <br>
        <h4>Limón Jiménez Jorge Alberto</h4> <br>
        <h4>Vázquez López Ismael</h4>
    </footer>


</body>
</html>