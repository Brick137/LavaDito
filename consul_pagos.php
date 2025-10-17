<?php

    include 'conexion.php';
    $result = mysqli_query($conexion, "SELECT * FROM pagos ORDER BY pago_id ASC");

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta pedidos</title>
    <link rel="stylesheet" href="styles/consultas.css">
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

        <h1>Consulta pagos</h1>

       <table class="tabla-estilo">
        <thead>
            <tr>
                <th>ID del pago</th>
                <th>ID del pedido</th>
                <th>Monto</th>
                <th>Fecha de pago</th>
                <th>Método de pago</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($mostrar = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$mostrar['pago_id']}</td>
                        <td>{$mostrar['pedido_id']}</td>
                        <td>{$mostrar['monto']}</td>
                        <td>{$mostrar['fecha_pago']}</td>
                        <td>{$mostrar['metodo']}</td>
                        <td>{$mostrar['estado']}</td>
                      </tr>";
                    }
                } else {
                echo "<tr><td colspan='6'>No hay pagos registrados</td></tr>";
                  }
            ?>
        </tbody>
    </table>

    </main>

    <footer>
        <h4>Castillo Alcantar Diego </h4> <br>
        <h4>Hernández Pérez José Luis</h4> <br>
        <h4>Limón Jiménez Jorge Alberto</h4> <br>
        <h4>Vázquez López Ismael</h4>
    </footer>

</body>
</html>