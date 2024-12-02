<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alimentación - Ganadería El Rosario</title>
    <link rel="stylesheet" href="Alimentar.css">
</head>
<body>

    <header>
        <h1>Ganadería El Rosario</h1>

        <!-- Botón en la esquina superior izquierda -->
        <button class="back-button-left" onclick="history.back()">
            <img src="Imagenes/Atras.png" alt="Atras">
        </button>

        <!-- Botón en la esquina superior derecha -->
        <button class="back-button-right" onclick="window.location.href='inicio.php'">
            <img src="https://cdn-icons-png.flaticon.com/512/32/32205.png" alt="Regresar al inicio">
        </button>
    </header>

    <div class="container">
        <form action="alimentar.php" method="POST">
            <!-- Cambiado a campo de texto para el nombre del empleado -->
            <input type="text" name="nombre_empleado" placeholder="Nombre del Empleado" required>
            <input type="text" name="arete" placeholder="Arete" required>

            <div class="comida-option">
                <label><input type="radio" name="Opcion" value="comida1" required> Abasto</label>
            </div>
            <div class="comida-option">
                <label><input type="radio" name="Opcion" value="comida2"> Inicio</label>
            </div>
            <div class="comida-option">
                <label><input type="radio" name="Opcion" value="comida3"> Desarrollo</label>
            </div>
            <div class="comida-option">
                <label><input type="radio" name="Opcion" value="comida4"> Engorda</label>
            </div>
            <div class="comida-option">
                <label><input type="radio" name="Opcion" value="comida5"> Finalización</label>
            </div>
            <button type="submit" class="submit-button">Alimentar</button>
        </form>
    </div>

</body>
<footer>
    <p>&copy; 2024 MR11. Todos los derechos reservados.</p>
</footer>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Configuración de la base de datos
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "GANADERIA2";

    // Crear conexión
    $conexion = mysqli_connect($server, $user, $pass, $db);

    // Comprobar conexión
    if (!$conexion) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // Obtener los valores del formulario
    $nombreEmpleado = $_POST['nombre_empleado'];
    $idAnimal = $_POST['arete'];
    $tipoAlimento = $_POST['Opcion'];

    // Verificar si el empleado existe por su nombre
    $query = "SELECT idEmpleado FROM empleados WHERE Nombre = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $nombreEmpleado);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo '<div class="Mensaje">El empleado no existe en la tabla empleados.</div>';
        exit;
    }
    $stmt->fetch();
    $stmt->close();

    // Verificar si el animal existe por su número de arete
    $query = "SELECT NumeroArete FROM animales WHERE NumeroArete = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idAnimal);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo '<div class="Mensaje">El número de arete no existe en la tabla animales.</div>';
        exit;
    }
    $stmt->close();

    // Función para obtener el precio de cada ingrediente
    function obtenerPrecioIngrediente($conexion, $nombreIngrediente) {
        $query = "SELECT PrecioUnidad FROM Almacen WHERE Nombre = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $nombreIngrediente);
        $stmt->execute();
        $stmt->bind_result($precioUnidad);
        $stmt->fetch();
        $stmt->close();
        return $precioUnidad ?: 0;
    }

    // Procesar el alimento según la opción seleccionada
    $alimentos = [];
    $gananciaTotal = 0;

    switch ($tipoAlimento) {
        case 'comida1':
            $alimentos = ['Rastrojo' => 9.35, 'Maiz' => 1.1, 'Sal' => 0.44, 'Electrolitos' => 0.11];
            break;
        case 'comida2':
            $alimentos = ['Rastrojo' => 2.6829, 'Maiz Roaldo' => 2.1463, 'Maiz' => 3.4341];
            break;
        case 'comida3':
            $alimentos = ['Rastrojo' => 2.1463, 'Maiz Roaldo' => 2.1463, 'Maiz' => 3.4341];
            break;
        case 'comida4':
            $alimentos = ['Rastrojo' => 2.1890, 'Maiz Roaldo' => 3.2835, 'Maiz' => 2.7363];
            break;
        case 'comida5':
            $alimentos = ['Rastrojo' => 2.189, 'Maiz Roaldo' => 3.2835, 'Maiz' => 3.2835];
            break;
        default:
            echo '<div class="Mensaje">Tipo de alimento inválido.</div>';
            exit;
    }

    foreach ($alimentos as $ingrediente => $cantidad) {
        $precio = obtenerPrecioIngrediente($conexion, $ingrediente);
        $gananciaTotal += $cantidad * $precio;

        // Actualizar inventario
        $query = "UPDATE Almacen SET Cantidad = Cantidad - ? WHERE Nombre = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ds", $cantidad, $ingrediente);
        $stmt->execute();
        $stmt->close();
    }

    // Actualizar la ganancia del animal
    $query = "UPDATE animales SET Ganancia = Ganancia + ? WHERE NumeroArete = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("di", $gananciaTotal, $idAnimal);
    $stmt->execute();
    $stmt->close();

    echo '<div class="Mensaje">El animal se alimentó correctamente.</div>';

    // Cerrar la conexión
    mysqli_close($conexion);
}
?>
