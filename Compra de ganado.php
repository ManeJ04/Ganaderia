<?php
// Configuración de la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "GANADERIA2";

// Crear conexión
$conexion = mysqli_connect($server, $user, $pass, $db);

// Verificar conexión
if ($conexion->connect_errno) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Establecer la zona horaria global para PHP
date_default_timezone_set('America/Mexico_City');

// Establecer la zona horaria correcta en la conexión MySQL
mysqli_query($conexion, "SET time_zone = '+00:00'");

// Obtener las razones sociales de la tabla ganaderos
$ganaderosConsulta = "SELECT idGanadero, RazonSocial FROM ganaderos";
$resultadoGanaderos = $conexion->query($ganaderosConsulta);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Ganadería el Rosario</title>
    <link rel="stylesheet" href="Compra de ganado.css">
</head>
<body>
    <header>
        <h1>Ganadería el Rosario</h1>
        <button class="back-button" onclick="window.history.back()">
            <img src="Imagenes/Atras.png" alt="Atrás">
        </button>
        <button class="back-image-button" onclick="window.location.href='inicio.php'">
            <img src="https://cdn-icons-png.flaticon.com/512/32/32205.png" alt="Regresar">
        </button>
    </header>
    <div class = "Principal">
    <div class="container">
        <form action="" method="POST">
            <input type="text" name="N_Reemo" placeholder="Reemo" required>
            <select id="TipoVenta" name="Motivo" required>
                <option value="" disabled selected>Seleccione una opción</option>
                <option value="engorda">Engorda</option>
                <option value="cria">Cría</option>
                <option value="sacrificio">Sacrificio</option>
            </select>
            <input type="date" name="Fecha" id="Fecha" value="<?php echo date('Y-m-d'); ?>" required>
            <input type="text" name="NumeroArete" placeholder="Número de Arete" required pattern="\d+" title="Debe ser un número.">
            <select name="Sexo" required>
                <option value="" disabled selected>Seleccione el sexo</option>
                <option value="masculino">Macho</option>
                <option value="femenino">Hembra</option>
            </select>
            <input type="number" name="Meses" placeholder="Meses de Edad" required min="0" title="Ingrese el número de meses.">
            
            <!-- Campo modificado para seleccionar una letra de la A a la Z -->
            <select name="Fierro" required>
                <option value="" disabled selected>Seleccione La letra del Fierro</option>
                <?php
                foreach (range('A', 'Z') as $letra) {
                    echo "<option value=\"$letra\">$letra</option>";
                }
                ?>
            </select>
            
            <input type="number" name="Peso" placeholder="Peso (kg)" required min="0" step="0.01" title="Ingrese el peso en kilogramos.">
            <input type="number" name="PrecioCompra" placeholder="Precio de Compra ($)" required min="0" step="0.01" title="Ingrese el precio en pesos.">
            
            <!-- Campo para seleccionar al ganadero -->
            <select name="idGanadero" required>
                <option value="" disabled selected>Seleccione un ganadero</option>
                <?php while ($ganadero = $resultadoGanaderos->fetch_assoc()): ?>
                    <option value="<?php echo $ganadero['idGanadero']; ?>">
                        <?php echo $ganadero['RazonSocial']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <input type="submit" value="Registrar">
        </form>
    </div>
    <div class = "Fierro">
        <img src="Imagenes/Fierros.png" alt="Fierro">
    </div>
    </div>
</body>
<footer>
    <p>&copy; 2024 MR11. Todos los derechos reservados.</p>
</footer>
</html>

<?php
// Comprobar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Obtener datos del formulario
    $nReemo = $_POST['N_Reemo'];
    $motivo = $_POST['Motivo'];
    $fecha = $_POST['Fecha'];
    $numeroArete = $_POST['NumeroArete'];
    $sexo = $_POST['Sexo'];
    $meses = $_POST['Meses'];
    $fierro = $_POST['Fierro'];
    $peso = $_POST['Peso'];
    $precioCompra = $_POST['PrecioCompra'];
    $idGanadero = $_POST['idGanadero'];

    // Calcular PrecioTotal (PrecioCompra * Peso)
    $precioTotal = $precioCompra * $peso;

    // Asignar Ganancia (en este caso, la ganancia es igual a PrecioTotal)
    $ganancia = $precioTotal;

    // Determinar la clasificación basada solo en los meses
    if ($meses >= 1 && $meses <= 15) {
        $clasificacion = 'Becerro/Becerra';
    } elseif ($meses >= 16 && $meses <= 24) {
        $clasificacion = 'Torete/Vacona';
    } else {
        $clasificacion = 'Toro/Vaca';
    }

    // Iniciar transacción
    mysqli_begin_transaction($conexion);

    try {
        // Insertar datos
        $stmtCompraganado = $conexion->prepare("INSERT INTO compraganado (N_Reemo, Motivo, Fecha) VALUES (?, ?, ?)");
        $stmtCompraganado->bind_param("sss", $nReemo, $motivo, $fecha);
        $stmtCompraganado->execute();
        $idCompraGanado = $conexion->insert_id;

        $stmtAnimales = $conexion->prepare("INSERT INTO animales (NumeroArete, Sexo, Meses, Fierro, Peso, PrecioCompra, PrecioTotal, Ganancia, Clasificacion, idCompraGanado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtAnimales->bind_param("isiiiiissi", $numeroArete, $sexo, $meses, $fierro, $peso, $precioCompra, $precioTotal, $ganancia, $clasificacion, $idCompraGanado);
        $stmtAnimales->execute();

        // Confirmar la transacción
        mysqli_commit($conexion);
        echo "Compra y animal registrados correctamente.";
    } catch (Exception $e) {
        // Deshacer transacción en caso de error
        mysqli_rollback($conexion);
        echo "Error: " . $e->getMessage();
    }
}

// Cerrar la conexión
$conexion->close();
?>