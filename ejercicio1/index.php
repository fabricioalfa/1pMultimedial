<!DOCTYPE html>
<html lang="en">
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
        // Definir detalles de las cuentas bancarias
        $cuentas = array(
            array(
                "tipo" => "Cuenta Corriente",
                "descripcion" => "Ideal para realizar operaciones diarias, como pagos y transferencias.",
                "tasa_interes" => "0.10%",
                "minimo_apertura" => "$100",
            ),
            array(
                "tipo" => "Cuenta de Ahorros",
                "descripcion" => "Perfecta para ahorrar dinero y ganar intereses sobre el saldo.",
                "tasa_interes" => "0.50%",
                "minimo_apertura" => "$50",
            ),
            array(
                "tipo" => "Cuenta de Inversión",
                "descripcion" => "Opción para invertir dinero y obtener rendimientos más altos.",
                "tasa_interes" => "2.00%",
                "minimo_apertura" => "$1000",
            ),
        );

        // Mostrar detalles de las cuentas bancarias
        foreach ($cuentas as $cuenta) {
            echo "<div class='cuenta'>";
            echo "<h2>{$cuenta['tipo']}</h2>";
            echo "<p><strong>Descripción:</strong> {$cuenta['descripcion']}</p>";
            echo "<p><strong>Tasa de Interés:</strong> {$cuenta['tasa_interes']}</p>";
            echo "<p><strong>Mínimo de Apertura:</strong> {$cuenta['minimo_apertura']}</p>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
