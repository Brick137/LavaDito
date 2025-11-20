<?php

    include 'conexion.php';

    if($_SERVER ['REQUEST_METHOD'] === 'POST') {

        $nombre= mysqli_real_escape_string($conexion, $_POST['nombre']);
        $apellido= mysqli_real_escape_string($conexion, $_POST['apellido']);
        $telefono= mysqli_real_escape_string($conexion, $_POST['telefono']);
        $licencia= mysqli_real_escape_string($conexion, $_POST['licencia']);
        $estado= mysqli_real_escape_string($conexion, $_POST['estado']);

        $sql = "INSERT INTO conductores (nombre, apellido, telefono, licencia, estado)
                VALUES ('$nombre', '$apellido', '$telefono', '$licencia', '$estado')";
                
        if (mysqli_query($conexion, $sql)) {
            echo"<script>
                alert('Conductor registrado con éxito');
                window.location.href = 'registro_conductores.php';
            </script>";
        } else {
            echo"<script>
                alert('Error de registro');
                window.location.href = 'registro_conductores.php';
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

        <h1>Registra conductores</h1>

        <form method="POST" action="registro_conductores.php">

        <label for="nombre">Nombre del conductor:</label>
        <input type="text" id="nombre" name="nombre" required>
        <br>
        <label for="apellidos">Apellidos del conductor:</label>
        <input type="text" id="apellido" name="apellido" required>
        <br>
        <label for="telefono">Telefono del conductor:</label>
        <input type="text" id="telefono" name="telefono" required>
        <br>
        <label for="licencia">Licencia del conductor:</label>
        <input type="text" id="licencia" name="licencia" required>
        <br>
        <label for="estado">Estado del conductor:</label>
        <input type="text" id="estado" name="estado" required>
        <br>
        <button type="submit">Registrar conductor</button>

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