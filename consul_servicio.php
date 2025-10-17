<?php

    include 'conexion.php';
    $result = mysqli_query($conexion, "SELECT * FROM servicios ORDER BY servicio_id ASC");

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

        <h1>Consulta servicios</h1>

       <table class="tabla-estilo">
        <thead>
            <tr>
                <th>ID de servicio</th>
                <th>ID de pedido</th>
                <th>Tipo de servicio</th>
                <th>Peso</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($mostrar = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$mostrar['servicio_id']}</td>
                        <td>{$mostrar['pedido_id']}</td>
                        <td>{$mostrar['tipo_servicio']}</td>
                        <td>{$mostrar['peso']}</td>
                        <td>{$mostrar['precio']}</td>
                      </tr>";
                    }
                } else {
                echo "<tr><td colspan='6'>No hay servicios registrados</td></tr>";
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