<?php

    include 'conexion.php';

    if($_SERVER ['REQUEST_METHOD'] === 'POST') {

        $pedido= mysqli_real_escape_string($conexion, $_POST['pedido_id']);
        $tipo_servicio= mysqli_real_escape_string($conexion, $_POST['tipo_servicio']);
        $peso= mysqli_real_escape_string($conexion, $_POST['peso']);
        $precio= mysqli_real_escape_string($conexion, $_POST['precio']);


        $sql = "INSERT INTO servicios (pedido_id, tipo_servicio, peso, precio)
                VALUES ('$pedido', '$tipo_servicio', '$peso', '$precio')";

        if (mysqli_query($conexion, $sql)) {
            echo"<script>
                alert('Servicio registrado con éxito');
                window.location.href = 'registro_servicios.php';
            </script>";
        } else {
            echo"<script>
                alert('Error de registro');
                window.location.href = 'registro_servicios.php';
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

        <h1>Registra servicios</h1>

        <form method="POST" action="registro_servicios.php">

        <label for="pedido">ID del pedido:</label>
        <input type="number" id="pedido" name="pedido_id" required>
        <br>
        <label for="tipo_servicio">Tipo de servicio:</label>
        <input type="text" id="tipo_servicio" name="tipo_servicio" required>
        <br>
        <label for="peso">Peso:</label>
        <input type="text" id="peso" name="peso" required>
        <br>
        <label for="precio">Precio:</label>
        <input type="text" id="precio" name="precio" required>
        <br>
        <button type="submit">Registrar servicio</button>

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