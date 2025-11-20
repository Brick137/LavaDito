<?php

    include 'conexion.php';

    if($_SERVER ['REQUEST_METHOD'] === 'POST') {

        $placa= mysqli_real_escape_string($conexion, $_POST['placa']);
        $modelo= mysqli_real_escape_string($conexion, $_POST['modelo']);
        $capacidad= mysqli_real_escape_string($conexion, $_POST['capacidad']);
        $estado= mysqli_real_escape_string($conexion, $_POST['estado']);


        $sql = "INSERT INTO furgonetas (placa, modelo, capacidad, estado)
                VALUES ('$placa', '$modelo', '$capacidad', '$estado')";

        if (mysqli_query($conexion, $sql)) {
            echo"<script>
                alert('Furgoneta registrada con éxito');
                window.location.href = 'registro_furgonetas.php';
            </script>";
        } else {
            echo"<script>
                alert('Error de registro');
                window.location.href = 'registro_furgonetas.php';
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

        <h1>Registra furgonetas</h1>

        <form method="POST" action="registro_furgonetas.php">

        <label for="placa">Placa de la furgoneta:</label>
        <input type="text" id="placa" name="placa" required>
        <br>
        <label for="modelo">Modelo de la furgoneta:</label>
        <input type="text" id="modelo" name="modelo" required>
        <br>
        <label for="capacidad">Capacidad:</label>
        <input type="text" id="capacidad" name="capacidad" required>
        <br>
        <label for="estado">Estado:</label>
        <input type="text" id="estado" name="estado" required>
        <br>
        <button type="submit">Registrar furgoneta</button>

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