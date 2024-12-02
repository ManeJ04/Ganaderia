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

// Probar la consulta
$consulta = "SELECT * FROM ventaganado";
$guardar = $conexion->query($consulta);

if (!$guardar) {
    die("Error en la consulta: " . $conexion->error);
}

// No cerrar la conexión aquí para usar los datos en la página HTML
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganancias - Ganadería El Rosario</title>
    <link rel="stylesheet" href="Dueño.css">
</head>
<body>

    <header>
        <h1>Ganadería El Rosario</h1>
        <!-- Botón para regresar -->
        <button class="back-button" onclick="history.back()">
            <img src="Imagenes/Atras.png" alt="Atras">
        </button>
        <!-- Imagen como botón para regresar al inicio -->
        <img src="https://cdn-icons-png.flaticon.com/512/32/32205.png" alt="Inicio" class="header-icon" onclick="window.location.href='Inicio.php'">
    </header>

    <div class="container">
        <h2>Reporte de Ganancias</h2>
        <table>
            <tr>
                <th>Fecha</th>
                <th>Ganancia</th>
                <th>Numero de Reemo</th>
                <th>Destino de venta</th>

            </tr>
            <tbody>
                <?php while ($row = $guardar->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['FechaVenta']; ?></td>
                    <td><?php echo $row['Ganancia']; ?></td>
                    <td><?php echo $row['N_Reemo']; ?></td>
                    <td><?php echo $row['Destino']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
<footer>
        <p>&copy; 2024 MR11. Todos los derechos reservados.</p>
    </footer>
</html>

