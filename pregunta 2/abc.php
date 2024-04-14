<?php
// Incluir archivo de conexión a la base de datos
include 'conexion.php';

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
function agregarPersona($nombre, $apellido, $edad, $direccion) {
    global $conexion;
    $query = "INSERT INTO Persona (nombre, apellido, edad, direccion) VALUES ('$nombre', '$apellido', $edad, '$direccion')";
    if (mysqli_query($conexion, $query)) {
        echo "Persona agregada correctamente.";
    } else {
        echo "Error al agregar persona: " . mysqli_error($conexion);
    }
}

// Función para asociar una persona a una cuenta bancaria
function asociarPersonaCuenta($id_persona, $id_cuenta) {
    global $conexion;
    $query = "UPDATE CuentaBancaria SET id_persona = $id_persona WHERE id_cuenta = $id_cuenta";
    if (mysqli_query($conexion, $query)) {
        echo "Persona asociada a la cuenta bancaria correctamente.";
    } else {
        echo "Error al asociar persona a la cuenta bancaria: " . mysqli_error($conexion);
    }
}

// Procesar las solicitudes según el método HTTP
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Si se envió el formulario de agregar persona
    if (isset($_POST["agregar_persona"])) {
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $edad = $_POST["edad"];
        $direccion = $_POST["direccion"];
        agregarPersona($nombre, $apellido, $edad, $direccion);
    }
    // Si se envió el formulario de asociar persona a cuenta bancaria
    if (isset($_POST["asociar_persona_cuenta"])) {
        $id_persona = $_POST["id_persona"];
        $id_cuenta = $_POST["id_cuenta"];
        asociarPersonaCuenta($id_persona, $id_cuenta);
    }
}

// Obtener todas las cuentas bancarias
$cuentas = obtenerCuentasBancarias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asociar Persona a Cuenta Bancaria</title>
    <link rel="stylesheet" href="stylesabc.css">
</head>
<body>
    <h1>Asociar Persona a Cuenta Bancaria</h1>
    <h2>Agregar Persona</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required><br>
        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" required><br>
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" required><br>
        <button type="submit" name="agregar_persona">Agregar Persona</button>
    </form>

    <h2>Asociar Persona a Cuenta Bancaria</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="id_persona">Seleccionar Persona:</label>
        <select id="id_persona" name="id_persona">
            <?php
            // Obtener todas las personas
            $query = "SELECT * FROM Persona";
            $result = mysqli_query($conexion, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['id_persona'] . "'>" . $row['nombre'] . " " . $row['apellido'] . "</option>";
                }
            }
            ?>
        </select><br>
        <label for="id_cuenta">Seleccionar Cuenta Bancaria:</label>
        <select id="id_cuenta" name="id_cuenta">
            <?php foreach ($cuentas as $id_cuenta => $numero_cuenta) {
                echo "<option value='$id_cuenta'>$numero_cuenta</option>";
            } ?>
        </select><br>
        <button type="submit" name="asociar_persona_cuenta">Asociar Persona a Cuenta Bancaria</button>
    </form>
</body>
</html>
