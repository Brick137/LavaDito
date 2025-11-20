<?php

    include 'conexion.php';
    include 'select_func.php';

    if($_SERVER ['REQUEST_METHOD'] === 'POST') {

        $pedido= mysqli_real_escape_string($conexion, $_POST['pedido_id']);
        $monto= mysqli_real_escape_string($conexion, $_POST['monto']);
        $fecha_pago= mysqli_real_escape_string($conexion, $_POST['fecha_pago']);
        $metodo= mysqli_real_escape_string($conexion, $_POST['metodo']);
        $estado= mysqli_real_escape_string($conexion, $_POST['estado']);

        $sql = "INSERT INTO pagos (pedido_id, monto, fecha_pago, metodo, estado)
                VALUES ('$pedido', '$monto', '$fecha_pago', '$metodo', '$estado')";
                
        if (mysqli_query($conexion, $sql)) {
            echo"<script>
                alert('Pago registrado con éxito');
                window.location.href = 'registro_pagos.php';
            </script>";
        } else {
            echo"<script>
                alert('Error de registro');
                window.location.href = 'registro_pagos.php';
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

        <h1>Registrar pagos</h1>

        <form method="POST" action="registro_pagos.php">

        <!--<label for="pedido_id">ID del pedido:</label>
        <input type="text" id="pedido_id" name="pedido_id" required>
        <br>-->

        <?php
            generarSelectPedidos($conexion, 'pedido_id', 'ID del pedido: ', 'Selecciona un pedido');
        ?>


        <label for="monto">Monto:</label>
        <input type="text" id="monto" name="monto" required>
        <br>
        <label for="fecha_pago">Fecha de pago:</label>
        <input type="date" id="fecha_pago" name="fecha_pago" required>
        <br>
        <label for="metodo">Método de pago:</label>
        <input type="text" id="metodo" name="metodo" required>
        <br>
        <label for="estado">Estado del pago:</label>
        <input type="text" id="estado" name="estado" required>
        <br>
        <button type="submit">Registrar pago</button>

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