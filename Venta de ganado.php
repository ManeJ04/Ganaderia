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

// Ajustar la zona horaria a la correcta
date_default_timezone_set('America/Mexico_City');

// Obtener la fecha actual
$fecha_actual = date("Y-m-d");

$mensaje = ""; // Variable para el mensaje

// Manejar solicitud AJAX para obtener la ganancia
if (isset($_POST['ajax']) && $_POST['ajax'] === "true" && isset($_POST['NumeroArete'])) {
    $numeroArete = $_POST['NumeroArete'];

    // Consultar la ganancia en la tabla animales
    $consulta = "SELECT Ganancia FROM animales WHERE NumeroArete = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("i", $numeroArete);
    $stmt->execute();
    $stmt->bind_result($ganancia);
    $stmt->fetch();
    $stmt->close();

    // Retornar la ganancia o un valor por defecto
    echo $ganancia !== null ? $ganancia : "0.00";
    exit;
}

// Comprobar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['ajax'])) {
    $numeroArete = $_POST['NumeroArete'];
    $destino = $_POST['Destino'];
    $tipoVenta = $_POST['TipoVenta'];
    $pesoVenta = $_POST['PesoVenta'];
    $precioVenta = $_POST['PrecioVenta'];
    $fechaVenta = $_POST['FechaVenta'];

    // Consultar el N_Reemo de la tabla compraganado y la ganancia de la tabla animales
    $consultaDatos = "SELECT c.N_Reemo, a.Ganancia FROM compraganado c INNER JOIN animales a ON c.idCompraGanado = a.idCompraGanado WHERE a.NumeroArete = ?";
    $stmtDatos = $conexion->prepare($consultaDatos);
    $stmtDatos->bind_param("i", $numeroArete);
    $stmtDatos->execute();
    $stmtDatos->bind_result($nReemo, $ganancia);
    $stmtDatos->fetch();
    $stmtDatos->close();

    // Verificar que se obtuvieron resultados
    if ($nReemo !== null && $ganancia !== null) {
        // Calcular la ganancia total (PrecioVenta - Ganancia de la tabla animales)
        $gananciaTotal = $precioVenta - $ganancia;

        // Insertar la venta en la tabla ventaganado
        $stmtVenta = $conexion->prepare("INSERT INTO ventaganado (N_Reemo, Destino, TipoVenta, PesoVenta, PrecioVenta, FechaVenta, Ganancia) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmtVenta->bind_param("issiisi", $nReemo, $destino, $tipoVenta, $pesoVenta, $precioVenta, $fechaVenta, $gananciaTotal);

        if ($stmtVenta->execute()) {
            // Eliminar el animal de la tabla animales
            $stmtEliminar = $conexion->prepare("DELETE FROM animales WHERE NumeroArete = ?");
            $stmtEliminar->bind_param("i", $numeroArete);
            if ($stmtEliminar->execute()) {
                $mensaje = "Venta realizada exitosamente";
            } else {
                $mensaje = "Venta registrada, pero no se pudo eliminar el animal: " . $stmtEliminar->error;
            }
            $stmtEliminar->close();
        } else {
            $mensaje = "Error al registrar la venta: " . $stmtVenta->error;
        }
        $stmtVenta->close();
    } else {
        $mensaje = "No se encontró N_Reemo ni ganancia para el NumeroArete proporcionado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta de Ganado - Ganadería El Rosario</title>
    <link rel="stylesheet" href="Venta de ganado.css">
    <script>
        // Función para obtener la ganancia con AJAX
        function obtenerGanancia() {
            const numeroArete = document.getElementById("NumeroArete").value;

            if (numeroArete.trim() !== "") {
                // Realizar la solicitud AJAX
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Mostrar la ganancia en el campo correspondiente
                        document.getElementById("Ganancia").value = xhr.responseText;
                    }
                };
                xhr.send("ajax=true&NumeroArete=" + encodeURIComponent(numeroArete));
            }
        }
    </script>
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
    <form action="" method="POST">
        <label for="NumeroArete">Número de Arete</label>
        <input type="text" id="NumeroArete" name="NumeroArete" required oninput="obtenerGanancia()">

        <label for="Destino">Destino</label>
        <input type="text" id="Destino" name="Destino" required>

        <label for="TipoVenta">Tipo de Venta</label>
        <select id="TipoVenta" name="TipoVenta" required>
            <option value="" disabled selected>Seleccione el tipo de venta</option>
            <option value="engorda">Engorda</option>
            <option value="cria">Cría</option>
            <option value="sacrificio">Sacrificio</option>
        </select>

        <label for="PesoVenta">Peso de Venta (kg)</label>
        <input type="number" id="PesoVenta" name="PesoVenta" required min="0" step="0.01">

        <label for="Ganancia">Precio Sugerido</label>
        <input type="text" id="Ganancia" name="Ganancia" readonly>

        <label for="PrecioVenta">Precio de Venta ($)</label>
        <input type="number" id="PrecioVenta" name="PrecioVenta" required min="0" step="0.01">

        <label for="FechaVenta">Fecha de Venta</label>
        <input type="date" id="FechaVenta" name="FechaVenta" value="<?php echo $fecha_actual; ?>" required>

        <input type="submit" value="Registrar Venta">
    </form>

    <?php if ($mensaje): ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>
</div>

</body>
<footer>
    <p>&copy; 2024 MR11. Todos los derechos reservados.</p>
</footer>
</html>