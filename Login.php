<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ganadería El Rosario</title>
    <link rel="stylesheet" href="Login.css">
</head>
<body>
    <header>
        <h1>Ganadería El Rosario</h1>
        <!-- Botón para regresar al inicio -->
        <button class="back-button" onclick="window.location.href='Inicio.php'">
            <img src="https://cdn-icons-png.flaticon.com/512/32/32205.png" alt="Regresar">
        </button>
    </header>

    <div class="content">
        <div class="login-container">
            <form method="POST" action="Login.php">
                <label for="Nombre">Nombre:</label>
                <input type="text" id="Nombre" name="Nombre" placeholder="Ingrese su Nombre" required>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="Clave" placeholder="Ingrese su contraseña" required>

                <button type="submit" name="Boton" class="login-button">Entrar</button>
            </form>
        </div>
    </div>
</body>
<footer>
    <p>&copy; 2024 MR11. Todos los derechos reservados.</p>
</footer>
</html>

<?php
// Verifica si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Configuración de la base de datos
    $server = "localhost";
    $user = "root";
    $pass = "";
    $db = "GANADERIA2";

    // Crear conexión
    $conexion = new mysqli($server, $user, $pass, $db);

    // Verificar conexión
    if ($conexion->connect_errno) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Validar si el botón de envío ha sido presionado
    if (isset($_POST["Boton"])) {
        // Validar que los campos no estén vacíos
        if (empty($_POST["Nombre"]) || empty($_POST["Clave"])) {
            echo "<p style='color: red;'>Los campos están vacíos</p>";
        } else {
            // Obtener los datos del formulario
            $usuario = mysqli_real_escape_string($conexion, $_POST["Nombre"]);
            $claveIngresada = mysqli_real_escape_string($conexion, $_POST["Clave"]);

            // Consulta para obtener el usuario y verificar el puesto
            $sql = $conexion->prepare("SELECT * FROM empleados WHERE BINARY Nombre = ? AND Puesto IN ('administrativo', 'dueño')");
            $sql->bind_param("s", $usuario);
            $sql->execute();
            $result = $sql->get_result();

            // Verificar si se encontró un usuario con el puesto permitido
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $claveGuardada = $row['Clave']; // Contraseña almacenada en la base de datos

                // Comparar la contraseña directamente (sin encriptación)
                if ($claveIngresada === $claveGuardada) {
                    // Redirigir a la página de gerentes u otra página de destino
                    echo "<p style='color: green;'>Acceso permitido. Redirigiendo...</p>";
                    header("Location: Gerentes.php");
                    exit();
                } else {
                    echo "<p style='color: red;'>La contraseña es incorrecta</p>";
                }
            } else {
                echo "<p style='color: red;'>No tienes permisos para acceder o el nombre de usuario es incorrecto</p>";
            }
        }
    }

    // Cerrar la conexión
    $conexion->close();
}
?>