<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tipos de Cuentas Bancarias</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Tipos de Cuentas Bancarias</h1>
        <?php
        // Incluir archivo de conexión a la base de datos
        include 'conexion.php';

        // Consultar las cuentas bancarias de tres personas
        $query = "SELECT Persona.nombre, CuentaBancaria.numero_cuenta, CuentaBancaria.saldo FROM CuentaBancaria INNER JOIN Persona ON CuentaBancaria.id_persona = Persona.id_persona LIMIT 3";
        $result = mysqli_query($conexion, $query);

        // Mostrar las cuentas bancarias
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='cuenta'>";
                echo "<h2>Cuenta Bancaria de " . $row['nombre'] . "</h2>";
                echo "<p>Número de Cuenta: " . $row['numero_cuenta'] . "</p>";
                echo "<p>Saldo: $" . $row['saldo'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "No se encontraron cuentas bancarias.";
        }

        // Cerrar la conexión a la base de datos
        mysqli_close($conexion);
        ?>
    </div>
</body>
</html>
