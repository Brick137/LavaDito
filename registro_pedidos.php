<?php

    include 'conexion.php';

    if($_SERVER ['REQUEST_METHOD'] === 'POST') {

        $cliente= mysqli_real_escape_string($conexion, $_POST['cliente_id']);
        $fecha_pedido= mysqli_real_escape_string($conexion, $_POST['fecha_pedido']);
        $fecha_entrega= mysqli_real_escape_string($conexion, $_POST['fecha_entrega']);
        $estado= mysqli_real_escape_string($conexion, $_POST['estado']);


        $sql = "INSERT INTO pedidos (cliente_id, fecha_pedido, fecha_entrega, estado)
                VALUES ('$cliente', '$fecha_pedido', '$fecha_entrega', '$estado')";

        if (mysqli_query($conexion, $sql)) {
            echo"<script>
                alert('Pedido registrado con éxito');
                window.location.href = 'registro_pedidos.php';
            </script>";
        } else {
            echo"<script>
                alert('Error de registro');
                window.location.href = 'registro_pedidos.php';
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

        <h1>Registra pedidos</h1>

        <form method="POST" action="registro_pedidos.php">

        <label for="cliente">ID del cliente:</label>
        <input type="number" id="cliente" name="cliente" required>
        <br>
        <label for="fecha_pedido">Fecha del pedido:</label>
        <input type="date" id="fecha_pedido" name="fecha_pedido" required>
        <br>
        <label for="fecha_entrega">Fecha de entrega:</label>
        <input type="date" id="fecha_entrega" name="fecha_entrega" required>
        <br>
        <label for="estado">Estado del pedido:</label>
        <input type="text" id="estado" name="estado" required>
        <br>
        <button type="submit">Registrar pedido</button>

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