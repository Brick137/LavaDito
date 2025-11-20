<?php
require_once "conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre     = $_POST['nombre'];
    $apellidos  = $_POST['apellidos'];
    $email      = $_POST['email'];
    $telefono   = $_POST['telefono'];
    $direccion  = $_POST['direccion'];
    $usuario    = $_POST['usuario'];
    $clave      = $_POST['clave'];
    $rol        = $_POST['rol'];

    // Iniciar transacción para asegurar que se guarde todo o nada
    $conexion->begin_transaction();

    try {
        // INSERTAR EN CLIENTES (Necesario porque tu tabla usuarios pide cliente_id)
        $sql_cliente = "INSERT INTO clientes (nombre, apellidos, telefono, email, direccion)
                        VALUES (?, ?, ?, ?, ?)";
        $stmt1 = $conexion->prepare($sql_cliente);
        $stmt1->bind_param("sssss", $nombre, $apellidos, $telefono, $email, $direccion);
        
        if (!$stmt1->execute()) {
            throw new Exception("Error al registrar cliente: " . $stmt1->error);
        }
        
        $cliente_id = $stmt1->insert_id;
        $stmt1->close();

        //  INSERTAR EN USUARIOS
        $sql_usuario = "INSERT INTO usuarios (cliente_id, usuario, clave, rol)
                        VALUES (?, ?, ?, ?)";
        $stmt2 = $conexion->prepare($sql_usuario);
        $stmt2->bind_param("isss", $cliente_id, $usuario, $clave, $rol);
        
        if (!$stmt2->execute()) {
            throw new Exception("Error al registrar usuario: " . $stmt2->error);
        }
        $stmt2->close();

        // LÓGICA NUEVA: SI ES CONDUCTOR, INSERTAR EN TABLA CONDUCTORES
        if ($rol === 'conductor') {
            // Como el formulario de registro público no pide Licencia, ponemos "Pendiente"
            // El estado inicial será "Libre" o "Activo"
            $licencia_default = "Pendiente";
            $estado_default = "Libre"; // O 'Activo' según tu preferencia

            $sql_conductor = "INSERT INTO conductores (nombre, apellido, telefono, licencia, estado)
                              VALUES (?, ?, ?, ?, ?)";
            
            $stmt3 = $conexion->prepare($sql_conductor);
            $stmt3->bind_param("sssss", $nombre, $apellidos, $telefono, $licencia_default, $estado_default);
            
            if (!$stmt3->execute()) {
                throw new Exception("Error al registrar en conductores: " . $stmt3->error);
            }
            $stmt3->close();
        }

        // Si todo salió bien, confirmamos los cambios
        $conexion->commit();

        echo "<script>
            alert('Registro exitoso. Ya puedes iniciar sesión.');
            window.location='inicio_sesion.php';
        </script>";

    } catch (Exception $e) {
        // Si algo falló, deshacemos todo
        $conexion->rollback();
        echo "Error en el registro: " . $e->getMessage();
    }
}
?>