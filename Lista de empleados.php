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
$consulta = "SELECT * FROM empleados";
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
    <title>Empleados - Ganadería El Rosario</title>
    <link rel="stylesheet" href="Lista de empleados.css">
</head>
<body>

    <header>
        <h1>Ganadería El Rosario</h1>
        <!-- Botón para regresar -->
        <button class="back-button" onclick="history.back()">
            <img src="Imagenes/Atras.png" alt="Atras">
        </button>
        <!-- Imagen como botón para regresar al inicio -->
        <img src="https://cdn-icons-png.flaticon.com/512/32/32205.png" alt="Inicio" class="header-icon" onclick="window.location.href='inicio.php'">
    </header>

    <div class="container">
        <h2>Empleados</h2>
            <table>
            <tr>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Sexo</th>
                <th>Telefono</th>
                <th>Puesto</th>
                <th>Salario</th>
            </tr>
            <tbody>
                <?php while ($row = $guardar->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['Nombre']; ?></td>
                    <td><?php echo $row['ApellidoP']; ?></td>
                    <td><?php echo $row['ApellidoM']; ?></td>
                    <td><?php echo $row['Sexo']; ?></td>
                    <td><?php echo $row['Telefono']; ?></td>
                    <td><?php echo $row['Puesto']; ?></td>
                    <td><?php echo $row['Salario']; ?></td>
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
