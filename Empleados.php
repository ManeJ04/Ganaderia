<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empleados - Ganadería El Rosario</title>
    <link rel="stylesheet" href="Empleados.css">
</head>
<body>

    <header>
        <h1>Ganadería El Rosario</h1>
        <!-- Botón para regresar -->
        <button class="back-button" onclick="history.back()">
            <img src="Imagenes/Atras.png" alt="Atras">
        </button>
        <!-- Imagen como botón para regresar al inicio -->
        <img src="https://cdn-icons-png.flaticon.com/512/32/32205.png" alt="Icono" class="header-icon" onclick="window.location.href='Inicio.php'">
    </header>

    <div class="container">
        <div class="grid">
            <div class="grid-item" onclick="window.location.href='Medicar.php'">
                <img src="Imagenes/Medicina.jpg" alt="Medicinas">
                <p>Medicar</p>
            </div>
            <div class="grid-item" onclick="window.location.href='Alimentar.php'">
                <img src="https://img.freepik.com/vector-premium/ilustracion-dibujos-animados-vaca_29937-10306.jpg?w=360" alt="Alimento">
                <p>Alimentar</p>
            </div>
            <div class="grid-item" onclick="window.location.href='Venta de ganado.php'">
                <img src="Imagenes/Ventas.png" alt="Ventas">
                <p>Venta de Ganado</p>
            </div>
            <div class="grid-item" onclick="window.location.href='Compra de ganado.php'">
                <img src="Imagenes/Compras.png" alt="Compras">
                <p>Registro de Ganado</p>
            </div>
        </div>
    </div>

</body>
<footer>
        <p>&copy; 2024 MR11. Todos los derechos reservados.</p>
    </footer>
</html>