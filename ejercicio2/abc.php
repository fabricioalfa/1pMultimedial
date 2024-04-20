<?php
// Incluir archivo de conexión a la base de datos
include 'conexion.php';

// Función para obtener todas las personas
function obtenerPersonas() {
    global $conexion;
    $query = "SELECT * FROM Persona";
    $result = mysqli_query($conexion, $query);
    $personas = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $personas[$row['id_persona']] = $row['nombre'] . " " . $row['apellido'];
        }
    }
    return $personas;
}

// Función para obtener todas las cuentas bancarias
function obtenerCuentasBancarias() {
    global $conexion;
    $query = "SELECT * FROM CuentaBancaria";
    $result = mysqli_query($conexion, $query);
    $cuentas = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cuentas[$row['id_cuenta']] = $row['numero_cuenta'];
        }
    }
    return $cuentas;
}

// Función para agregar una nueva persona
function agregarPersona($nombre, $apellido, $edad, $rol, $direccion) {
    global $conexion;
    $query = "INSERT INTO Persona (nombre, apellido, edad, rol, direccion) VALUES ('$nombre', '$apellido', $edad, '$rol', '$direccion')";
    if (mysqli_query($conexion, $query)) {
        echo "Persona agregada correctamente.";
    } else {
        echo "Error al agregar persona: " . mysqli_error($conexion);
    }
}

// Función para asociar una persona a un tipo de cuenta bancaria
function asociarPersonaTipoCuenta($id_persona, $tipo_cuenta, $numero_cuenta, $saldo) {
    global $conexion;
    // Verificar que los valores no sean nulos antes de construir la consulta SQL
    if ($numero_cuenta !== null && $saldo !== null) {
        $query = "INSERT INTO CuentaBancaria (id_persona, tipo_cuenta, numero_cuenta, saldo) VALUES ($id_persona, '$tipo_cuenta', '$numero_cuenta', $saldo)";
        if (mysqli_query($conexion, $query)) {
            echo "Persona asociada al tipo de cuenta bancaria correctamente.";
        } else {
            echo "Error al asociar persona al tipo de cuenta bancaria: " . mysqli_error($conexion);
        }
    } else {
        echo "Error: El número de cuenta o el saldo no pueden ser nulos.";
    }
}

// Función para modificar una persona
function modificarPersona($id_persona, $nombre, $apellido, $edad, $rol, $direccion) {
    global $conexion;
    $query = "UPDATE Persona SET nombre='$nombre', apellido='$apellido', edad=$edad, rol='$rol', direccion='$direccion' WHERE id_persona=$id_persona";
    if (mysqli_query($conexion, $query)) {
        echo "Persona modificada correctamente.";
    } else {
        echo "Error al modificar persona: " . mysqli_error($conexion);
    }
}

// Función para eliminar una persona
function eliminarPersona($id_persona) {
    global $conexion;
    // Antes de eliminar la persona, también eliminamos todas sus asociaciones con cuentas bancarias
    $queryEliminarAsociaciones = "DELETE FROM CuentaBancaria WHERE id_persona=$id_persona";
    mysqli_query($conexion, $queryEliminarAsociaciones);
    
    $query = "DELETE FROM Persona WHERE id_persona=$id_persona";
    if (mysqli_query($conexion, $query)) {
        echo "Persona eliminada correctamente.";
    } else {
        echo "Error al eliminar persona: " . mysqli_error($conexion);
    }
}

// Función para modificar una cuenta bancaria
function modificarCuentaBancaria($id_cuenta, $numero_cuenta, $saldo) {
    global $conexion;
    $query = "UPDATE CuentaBancaria SET numero_cuenta='$numero_cuenta', saldo=$saldo WHERE id_cuenta=$id_cuenta";
    if (mysqli_query($conexion, $query)) {
        echo "Cuenta bancaria modificada correctamente.";
    } else {
        echo "Error al modificar cuenta bancaria: " . mysqli_error($conexion);
    }
}

// Función para eliminar una cuenta bancaria
function eliminarCuentaBancaria($id_cuenta) {
    global $conexion;
    $query = "DELETE FROM CuentaBancaria WHERE id_cuenta=$id_cuenta";
    if (mysqli_query($conexion, $query)) {
        echo "Cuenta bancaria eliminada correctamente.";
    } else {
        echo "Error al eliminar cuenta bancaria: " . mysqli_error($conexion);
    }
}

// Procesar las solicitudes según el método HTTP
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Si se envió el formulario de agregar persona
    if (isset($_POST["agregar_persona"])) {
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $edad = $_POST["edad"];
        $rol = $_POST["rol"];
        $direccion = $_POST["direccion"];
        agregarPersona($nombre, $apellido, $edad, $rol, $direccion);
    }
    // Si se envió el formulario de asociar persona a tipo de cuenta bancaria
    if (isset($_POST["asociar_persona_tipo_cuenta"])) {
        $id_persona = $_POST["id_persona"];
        $tipo_cuenta = $_POST["tipo_cuenta"];
        $numero_cuenta = $_POST["numero_cuenta"];
        $saldo = $_POST["saldo"];
        asociarPersonaTipoCuenta($id_persona, $tipo_cuenta, $numero_cuenta, $saldo);
    }
    // Si se envió el formulario de modificar persona
    if (isset($_POST["modificar_persona"])) {
        $id_persona = $_POST["id_persona"];
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $edad = $_POST["edad"];
        $rol = $_POST["rol"];
        $direccion = $_POST["direccion"];
        modificarPersona($id_persona, $nombre, $apellido, $edad, $rol, $direccion);
    }
    // Si se envió el formulario de eliminar persona
    if (isset($_POST["eliminar_persona"])) {
        $id_persona = $_POST["id_persona"];
        eliminarPersona($id_persona);
    }
    // Si se envió el formulario de modificar cuenta bancaria
    if (isset($_POST["modificar_cuenta"])) {
        $id_cuenta = $_POST["id_cuenta"];
        $numero_cuenta = $_POST["numero_cuenta"];
        $saldo = $_POST["saldo"];
        modificarCuentaBancaria($id_cuenta, $numero_cuenta, $saldo);
    }
    // Si se envió el formulario de eliminar cuenta bancaria
    if (isset($_POST["eliminar_cuenta"])) {
        $id_cuenta = $_POST["id_cuenta"];
        eliminarCuentaBancaria($id_cuenta);
    }
}

// Obtener todas las personas
$personas = obtenerPersonas();

// Obtener todas las cuentas bancarias
$cuentas = obtenerCuentasBancarias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asociar Persona a Tipo de Cuenta Bancaria</title>
    <link rel="stylesheet" href="stylesabc.css">
</head>
<body>
    <h1>Asociar Persona a Tipo de Cuenta Bancaria</h1>
    <h2>Agregar Persona</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required><br>
        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" required><br>
        <label for="rol">Rol:</label>
        <input type="text" id="rol" name="rol" required><br>
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required><br>
        <button type="submit" name="agregar_persona">Agregar Persona</button>
    </form>

    <h2>Asociar Persona a Tipo de Cuenta Bancaria</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="id_persona">Seleccionar Persona:</label>
        <select id="id_persona" name="id_persona">
            <?php foreach ($personas as $id_persona => $nombre_persona) {
                echo "<option value='$id_persona'>$nombre_persona</option>";
            } ?>
        </select><br>
        <label for="tipo_cuenta">Seleccionar Tipo de Cuenta Bancaria:</label>
        <select id="tipo_cuenta" name="tipo_cuenta">
            <option value="Cuenta de Ahorro">Cuenta de Ahorro</option>
            <option value="Cuenta Corriente">Cuenta Corriente</option>
            <option value="Cuenta de Nómina">Cuenta de Nómina</option>
        </select><br>
        <label for="numero_cuenta">Número de Cuenta:</label>
        <input type="text" id="numero_cuenta" name="numero_cuenta" required><br>
        <label for="saldo">Saldo:</label>
        <input type="number" id="saldo" name="saldo" step="0.01" required><br>
        <button type="submit" name="asociar_persona_tipo_cuenta">Asociar Persona a Tipo de Cuenta Bancaria</button>
    </form>

    <h2>Modificar Persona</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="id_persona_modificar">Seleccionar Persona:</label>
        <select id="id_persona_modificar" name="id_persona">
            <?php foreach ($personas as $id_persona => $nombre_persona) {
                echo "<option value='$id_persona'>$nombre_persona</option>";
            } ?>
        </select><br>
        <label for="nombre_modificar">Nuevo Nombre:</label>
        <input type="text" id="nombre_modificar" name="nombre" required><br>
        <label for="apellido_modificar">Nuevo Apellido:</label>
        <input type="text" id="apellido_modificar" name="apellido" required><br>
        <label for="edad_modificar">Nueva Edad:</label>
        <input type="number" id="edad_modificar" name="edad" required><br>
        <label for="rol_modificar">Nuevo Rol:</label>
        <input type="text" id="rol_modificar" name="rol" required><br>
        <label for="direccion_modificar">Nueva Dirección:</label>
        <input type="text" id="direccion_modificar" name="direccion" required><br>
        <button type="submit" name="modificar_persona">Modificar Persona</button>
    </form>

    <h2>Eliminar Persona</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="id_persona_eliminar">Seleccionar Persona:</label>
        <select id="id_persona_eliminar" name="id_persona">
            <?php foreach ($personas as $id_persona => $nombre_persona) {
                echo "<option value='$id_persona'>$nombre_persona</option>";
            } ?>
        </select><br>
        <button type="submit" name="eliminar_persona">Eliminar Persona</button>
    </form>

    <h2>Modificar Cuenta Bancaria</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="id_cuenta_modificar">Seleccionar Cuenta Bancaria:</label>
        <select id="id_cuenta_modificar" name="id_cuenta">
            <?php foreach ($cuentas as $id_cuenta => $numero_cuenta) {
                echo "<option value='$id_cuenta'>$numero_cuenta</option>";
            } ?>
        </select><br>
        <label for="numero_cuenta_modificar">Nuevo Número de Cuenta:</label>
        <input type="text" id="numero_cuenta_modificar" name="numero_cuenta" required><br>
        <label for="saldo_modificar">Nuevo Saldo:</label>
        <input type="number" id="saldo_modificar" name="saldo" step="0.01" required><br>
        <button type="submit" name="modificar_cuenta">Modificar Cuenta Bancaria</button>
    </form>

    <h2>Eliminar Cuenta Bancaria</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="id_cuenta_eliminar">Seleccionar Cuenta Bancaria:</label>
        <select id="id_cuenta_eliminar" name="id_cuenta">
            <?php foreach ($cuentas as $id_cuenta => $numero_cuenta) {
                echo "<option value='$id_cuenta'>$numero_cuenta</option>";
            } ?>
        </select><br>
        <button type="submit" name="eliminar_cuenta">Eliminar Cuenta Bancaria</button>
    </form>
</body>
</html>
