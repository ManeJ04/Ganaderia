<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerentes - Ganadería El Rosario</title>
    <link rel="stylesheet" href="Gerentes.css">
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
        <div class="grid">
            <!-- Primera fila -->
            <div class="grid-item">
                <button onclick="window.location.href='Compra Almacen.php'">
                    <img src="Imagenes/Compras.png" alt="Compras">
                    <p>Ventas</p>
                </button>
            </div>
            <div class="grid-item">
                <button onclick="window.location.href='Ganado.php'">
                    <img src="Imagenes/Ventas.png" alt="Ventas">
                    <p>Corrales</p>
                </button>
            </div>

            <!-- Segunda fila -->
            <div class="grid-item">
                <button onclick="window.location.href='Almacen.php'">
                    <img src="Imagenes/Almacen.png" alt="Almacen">
                    <p>Almacen</p>
                </button>
            </div>
            <div class="grid-item">
                <button onclick="window.location.href='Lista de empleados.php'">
                    <img src="Imagenes/trabajadores.jpg" alt="Empleados">
                    <p>Empleados</p>
                </button>
            </div>
        </div>
    </div>

</body>
<footer>
        <p>&copy; 2024 MR11. Todos los derechos reservados.</p>
    </footer>
</html>