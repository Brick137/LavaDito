<?php

function generarSelectPedidos($conexion, $name, $label, $valor_defecto = '') {
    echo "<label for='$name'>$label:</label>";
    echo "<select id='$name' name='$name' required>";

    // Opción por defecto
    if ($valor_defecto !== '') {
        echo "<option value=''>$valor_defecto</option>";
    }

    $sql = "SELECT pedido_id FROM pedidos";
    $result = mysqli_query($conexion, $sql);

    if (!$result) {
        echo "<option>Error al cargar datos</option>";
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['pedido_id'] . "'>" . $row['pedido_id'] . "</option>";
        }
    }

    echo "</select><br>";
}

function generarSelectFurgonetas($conexion, $name, $label, $valor_defecto = '') {
    echo "<label for='$name'>$label:</label>";
    echo "<select id='$name' name='$name' required>";

    // Opción por defecto
    if ($valor_defecto !== '') {
        echo "<option value=''>$valor_defecto</option>";
    }

    $sql = "SELECT furgoneta_id FROM furgonetas";
    $result = mysqli_query($conexion, $sql);

    if (!$result) {
        echo "<option>Error al cargar datos</option>";
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['furgoneta_id'] . "'>" . $row['furgoneta_id'] . "</option>";
        }
    }

    echo "</select><br>";
}

function generarSelectConductores($conexion, $name, $label, $valor_defecto = '') {
    echo "<label for='$name'>$label:</label>";
    echo "<select id='$name' name='$name' required>";

    // Opción por defecto
    if ($valor_defecto !== '') {
        echo "<option value=''>$valor_defecto</option>";
    }

    $sql = "SELECT conductor_id FROM conductores";
    $result = mysqli_query($conexion, $sql);

    if (!$result) {
        echo "<option>Error al cargar datos</option>";
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['conductor_id'] . "'>" . $row['conductor_id']  ."</option>";
        }
    }

    echo "</select><br>";
}

?>