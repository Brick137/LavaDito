<?php

    include 'conexion.php';

    if($_SERVER ['REQUEST_METHOD'] === 'POST') {

        $pedido= mysqli_real_escape_string($conexion, $_POST['pedido_id']);
        $furgoneta= mysqli_real_escape_string($conexion, $_POST['furgoneta_id']);
        $conductor= mysqli_real_escape_string($conexion, $_POST['conductor_id']);
        $fecha_hora_salida= mysqli_real_escape_string($conexion, $_POST['fecha_hora_salida']);
        $fecha_hora_entrega= mysqli_real_escape_string($conexion, $_POST['fecha_hora_entrega']);
        $estado= mysqli_real_escape_string($conexion, $_POST['estado']);

        $sql = "INSERT INTO rutas (pedido_id, furgoneta_id, conductor_id, fecha_hora_salida, fecha_hora_entrega, estado)
                VALUES ('$pedido', '$furgoneta', '$conductor', '$fecha_hora_salida', '$fecha_hora_entrega', '$estado')";

        if (mysqli_query($conexion, $sql)) {
            echo"<script>
                alert('Ruta registrada con éxito');
                window.location.href = 'registro_rutas.php';
            </script>";
        } else {
            echo"<script>
                alert('Error de registro');
                window.location.href = 'registro_rutas.php';
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

        <h1>Registrar rutas</h1>

        <form method="POST" action="registro_rutas.php">

        <label for="pedido_id">ID del pedido:</label>
        <input type="text" id="pedido_id" name="pedido_id" required>
        <br>
        <label for="furgoneta_id">ID de la furgoneta:</label>
        <input type="text" id="furgoneta_id" name="furgoneta_id" required>
        <br>
        <label for="conductor_id">ID del conductor:</label>
        <input type="text" id="conductor_id" name="conductor_id" required>
        <br>
        <label for="fecha_hora_salida">Fecha y hora de salida:</label>
        <input type="datetime-local" id="fecha_hora_salida" name="fecha_hora_salida" required>
        <br>
        <label for="fecha_hora_entrega">Fecha y hora de entrega:</label>
        <input type="datetime-local" id="fecha_hora_entrega" name="fecha_hora_entrega" required>
        <br>
        <label for="estado">Estado de la ruta:</label>
        <input type="text" id="estado" name="estado" required>
        <br>
        <button type="submit">Registrar ruta</button>

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