<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Ganadería El Rosario</title>
    <link rel="stylesheet" href="Registro.css">
</head>
<body>
    <header>
        <h1>Ganadería el Rosario</h1>
        <!-- Botón para regresar usando imagen "Atras" -->
        <button class="back-button" onclick="window.history.back()">
            <img src="Imagenes/Atras.png" alt="Atrás">
        </button>
        <!-- Botón para regresar al inicio -->
        <button class="back-image-button" onclick="window.location.href='inicio.php'">
            <img src="https://cdn-icons-png.flaticon.com/512/32/32205.png" alt="Regresar">
        </button>
    </header>

    <div class="container">
        <form action="" method="POST">
            <input type="text" name="Nombre" placeholder="Nombre" required>
            <input type="text" name="ApellidoP" placeholder="Apellido Paterno" required>
            <input type="text" name="ApellidoM" placeholder="Apellido Materno" required>
            <select name="Sexo" required>
                <option value="" disabled selected>Seleccione su sexo</option>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="otro">Otro</option>
            </select>
            <input type="text" name="Telefono" placeholder="Teléfono (10 dígitos)" required pattern="\d{10}" title="Debe ingresar exactamente 10 dígitos.">
            <select name="Puesto" id="puesto" onchange="togglePasswordField()" required>
                <option value="" disabled selected>Seleccione su puesto</option>
                <option value="administrativo">Administrativo</option>
                <option value="dueño">Dueño</option>
                <option value="trabajador">Trabajador</option>
            </select>
            <input type="number" name="Salario" placeholder="Salario" required>
            <div id="passwordField">
                <input type="password" name="Clave" placeholder="Contraseña" required>
            </div>
            <input type="submit" value="Registrar">
        </form>
    </div>
</body>
<footer>
    <p>&copy; 2024 MR11. Todos los derechos reservados.</p>
</footer>
</html>

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

// Comprobar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['Nombre'];
    $apellidoP = $_POST['ApellidoP'];
    $apellidoM = $_POST['ApellidoM'];
    $sexo = $_POST['Sexo'];
    $telefono = $_POST['Telefono'];
    $puesto = $_POST['Puesto'];
    $salario = $_POST['Salario'];
    $clave = $_POST['Clave']; //No puede ser Null

    $stmt = $conexion->prepare("INSERT INTO empleados (Nombre, ApellidoP, ApellidoM, Sexo, Telefono, Puesto, Salario, Clave) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("ssssssis", $nombre, $apellidoP, $apellidoM, $sexo, $telefono, $puesto, $salario, $clave);
        if ($stmt->execute()) {
            echo "Empleado registrado exitosamente.";
        } else {
            echo "Error al registrar el empleado: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conexion->error;
    }
}
$conexion->close();
?>
