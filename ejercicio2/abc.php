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

// Procesar las solicitudes según el método HTTP
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Si se envió el formulario de agregar persona
    if (isset($_POST["agregar_persona"])) {
        // Verificar si todos los campos del formulario están completos
        if (!empty($_POST["nombre"]) && !empty($_POST["apellido"]) && !empty($_POST["edad"]) && !empty($_POST["rol"]) && !empty($_POST["direccion"])) {
            $nombre = $_POST["nombre"];
            $apellido = $_POST["apellido"];
            $edad = $_POST["edad"];
            $rol = $_POST["rol"];
            $direccion = $_POST["direccion"];
            agregarPersona($nombre, $apellido, $edad, $rol, $direccion);
        } else {
            echo "Error: Todos los campos del formulario deben completarse.";
        }
    }
    // Si se envió el formulario de asociar persona a tipo de cuenta bancaria
    if (isset($_POST["asociar_persona_tipo_cuenta"])) {
        // Verificar si todos los campos del formulario están completos
        if (!empty($_POST["id_persona"]) && !empty($_POST["tipo_cuenta"]) && !empty($_POST["numero_cuenta"]) && !empty($_POST["saldo"])) {
            $id_persona = $_POST["id_persona"];
            $tipo_cuenta = $_POST["tipo_cuenta"];
            $numero_cuenta = $_POST["numero_cuenta"];
            $saldo = $_POST["saldo"];
            asociarPersonaTipoCuenta($id_persona, $tipo_cuenta, $numero_cuenta, $saldo);
        } else {
            echo "Error: Todos los campos del formulario deben completarse.";
        }
    }
}

// Obtener todas las personas
$personas = obtenerPersonas();
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
    <div class="container">
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
            <label for="saldo">Saldo en Bolivianos:</label>
            <input type="number" id="saldo" name="saldo" step="0.01" required><br>
            <button type="submit" name="asociar_persona_tipo_cuenta">Asociar Persona a Tipo de Cuenta Bancaria</button>
        </form>
    </div>
</body>
</html>
