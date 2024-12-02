<?php
// Configuración de la base de datos
$server = "localhost";
$user = "root";
$pass = "";
$db = "GANADERIA2";

// Crear conexión
$conexion = mysqli_connect($server, $user, $pass, $db);

// Verificar conexión
if (!$conexion) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Obtener medicamentos con unidad de medida en Ml
$medicamentos = [];
$queryMedicamentos = "SELECT Nombre, Cantidad, PrecioUnidad FROM almacen WHERE UnidadMedida = 'Ml'";
$resultadoMedicamentos = mysqli_query($conexion, $queryMedicamentos);
if ($resultadoMedicamentos) {
    while ($fila = mysqli_fetch_assoc($resultadoMedicamentos)) {
        $medicamentos[] = $fila;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Medicamentos - Ganadería El Rosario</title>
    <link rel="stylesheet" href="Medicar.css">
</head>
<body>

    <header>
        <h1>Ganadería El Rosario</h1>
        
        <!-- Botón en la esquina superior izquierda -->
        <button class="back-button-left" onclick="history.back()">
            <img src="Imagenes/Atras.png" alt="Atrás">
        </button>

        <!-- Botón en la esquina superior derecha -->
        <button class="back-button-right" onclick="window.location.href='inicio.php'">
            <img src="https://cdn-icons-png.flaticon.com/512/32/32205.png" alt="Regresar">
        </button>
    </header>

    <div class="container">
        <form action="" method="POST">
            <!-- Cambiado a un campo de texto para el nombre del empleado -->
            <input type="text" name="nombre_empleado" placeholder="Nombre del Empleado" required>
            <input type="text" name="arete" placeholder="Arete" required>

            <h3>Seleccione un medicamento:</h3>
            <?php if (!empty($medicamentos)): ?>
                <?php foreach ($medicamentos as $medicamento): ?>
                    <div class="medicamento-option">
                        <label>
                            <input type="radio" name="medicamento" value="<?= $medicamento['Nombre']; ?>" required>
                            <?= $medicamento['Nombre']; ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay medicamentos disponibles con unidad de medida en Ml.</p>
            <?php endif; ?>

            <button type="submit" class="submit-button">Medicar</button>
        </form>
    </div>

</body>
<footer>
        <p>&copy; 2024 MR11. Todos los derechos reservados.</p>
</footer>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombreEmpleado = $_POST['nombre_empleado'];
    $numeroArete = $_POST['arete'];
    $nombreMedicamento = $_POST['medicamento'];

    // Validar si existe el empleado por nombre
    $query = "SELECT idEmpleado FROM empleados WHERE Nombre = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $nombreEmpleado);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo "<div class='Mensaje'>El empleado no existe en la base de datos.</div>";
        exit;
    }
    $stmt->fetch();
    $stmt->close();

    // Validar si existe el número de arete
    $query = "SELECT * FROM animales WHERE NumeroArete = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $numeroArete);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo "<div class='Mensaje'>El número de arete no existe en la base de datos.</div>";
        exit;
    }
    $stmt->close();

    // Obtener información del medicamento seleccionado
    $query = "SELECT Cantidad, PrecioUnidad FROM almacen WHERE Nombre = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $nombreMedicamento);
    $stmt->execute();
    $stmt->bind_result($cantidadDisponible, $precioUnidad);
    $stmt->fetch();
    $stmt->close();

    // Verificar si hay al menos 5 ml del medicamento
    if ($cantidadDisponible < 5) {
        echo "<div class='Mensaje'>No hay suficiente cantidad del medicamento seleccionado.</div>";
        exit;
    }

    // Calcular el costo total de los 5 ml utilizados
    $costoTotal = $precioUnidad * 5;

    // Descontar los 5 ml del medicamento
    $nuevaCantidad = $cantidadDisponible - 5;
    $query = "UPDATE almacen SET Cantidad = ? WHERE Nombre = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("is", $nuevaCantidad, $nombreMedicamento);
    if (!$stmt->execute()) {
        echo "<div class='Mensaje'>Error al actualizar el medicamento: " . $stmt->error . "</div>";
        exit;
    }
    $stmt->close();

    // Obtener la ganancia actual del animal
    $gananciaActual = 0;
    $query = "SELECT Ganancia FROM animales WHERE NumeroArete = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $numeroArete);
    $stmt->execute();
    $stmt->bind_result($gananciaActual);
    $stmt->fetch();
    $stmt->close();

    // Calcular la nueva ganancia
    $nuevaGanancia = $gananciaActual + $costoTotal;

    // Actualizar la ganancia del animal
    $query = "UPDATE animales SET Ganancia = ? WHERE NumeroArete = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("di", $nuevaGanancia, $numeroArete);
    if ($stmt->execute()) {
        echo "<div class='Mensaje'>El medicamento fue aplicado correctamente</div>";
    } else {
        echo "<div class='Mensaje'>Error al actualizar la ganancia del animal: " . $stmt->error . "</div>";
    }
    $stmt->close();

    // Cerrar la conexión
    mysqli_close($conexion);
}
?>
