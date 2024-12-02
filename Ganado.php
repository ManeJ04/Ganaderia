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

// Consulta para obtener los datos de las tres tablas usando JOIN
$consulta = "
    SELECT 
        c.N_Reemo, 
        c.Motivo,
        a.NumeroArete, 
        a.Sexo, 
        a.Meses, 
        a.Fierro,
        a.Clasificacion, 
        g.RazonSocial
    FROM compraganado c
    INNER JOIN animales a ON a.idCompraGanado = c.idCompraGanado
    LEFT JOIN ganaderos g ON g.idCompraGanado = c.idCompraGanado
";

// Ejecutar la consulta
$guardar = $conexion->query($consulta);

// Verificar si la consulta tiene errores
if (!$guardar) {
    die("Error en la consulta: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganado - Ganadería El Rosario</title>
    <link rel="stylesheet" href="Ganado.css">
</head>
<body>

    <header>
        <h1>Ganadería El Rosario</h1>
        <button class="back-button" onclick="history.back()">
            <img src="Imagenes/Atras.png" alt="Atras">
        </button>
        <img src="https://cdn-icons-png.flaticon.com/512/32/32205.png" alt="Inicio" class="header-icon" onclick="window.location.href='inicio.php'">
    </header>

    <div class="container">
        <h2>Ganado</h2>
        <table>
            <tr>
                <th>NumeroReemo</th>
                <th>Motivo</th>
                <th>Numero de Arete</th>
                <th>Sexo</th>
                <th>Meses</th>
                <th>Clasificacion</th>
                <th>Fierro</th>
            </tr>
            <tbody>
                <?php if ($guardar->num_rows > 0): ?>
                    <?php while ($row = $guardar->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['N_Reemo']; ?></td>
                        <td><?php echo $row['Motivo']; ?></td>
                        <td><?php echo $row['NumeroArete']; ?></td>
                        <td><?php echo $row['Sexo']; ?></td>
                        <td><?php echo $row['Meses']; ?></td>
                        <td><?php echo $row['Clasificacion']; ?></td>
                        <td><?php echo $row['Fierro']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No hay datos disponibles</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
<footer>
        <p>&copy; 2024 MR11. Todos los derechos reservados.</p>
    </footer>
</html>