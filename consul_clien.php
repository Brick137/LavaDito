<?php

    include 'conexion.php';
    $result = mysqli_query($conexion, "SELECT * FROM clientes ORDER BY cliente_id ASC");

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LavaDito</title>
    <link rel="stylesheet" href="consultas.css">
</head>

<body>

    <div class="nav">
        <div class="box">
            <img src="logo1.png">
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

        <h1>Consulta clientes</h1>

       <table class="tabla-estilo">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Dirección</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($mostrar = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                        <td>{$mostrar['cliente_id']}</td>
                        <td>{$mostrar['nombre']}</td>
                        <td>{$mostrar['apellidos']}</td>
                        <td>{$mostrar['telefono']}</td>
                        <td>{$mostrar['email']}</td>
                        <td>{$mostrar['direccion']}</td>
                      </tr>";
                    }
                } else {
                echo "<tr><td colspan='6'>No hay clientes registrados</td></tr>";
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