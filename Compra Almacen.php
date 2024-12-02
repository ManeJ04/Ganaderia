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
    <title>Reporte de Ganancias - Ganadería El Rosario</title>
    <link rel="stylesheet" href="Compra Almacen.css">
</head>
<body>

<header>
    <h1>Ganadería El Rosario</h1>
    <button class="back-button" onclick="history.back()">
        <img src="Imagenes/Atras.png" alt="Atras">
    </button>
    <button class="home-button" onclick="window.location.href='inicio.php'">
        <img src="https://cdn-icons-png.flaticon.com/512/32/32205.png" alt="Regresar">
    </button>
</header>

<div class="container">
    <h2>Reporte de Ventas</h2>
    <table>
        <tr>
            <th>Fecha</th>
            <th>Ganancia</th>
            <th>Número de Reemo</th>
            <th>Destino de Venta</th>
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

<footer>
    <p>&copy; 2024 MR11. Todos los derechos reservados.</p>
</footer>

</body>
</html>
