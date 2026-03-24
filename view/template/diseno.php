<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instituto Tecnológico</title>
    <link rel="stylesheet" href="view/css/style.css">
</head>
<body>

    <nav class="navbar">
        <div class="logo">
            <h2>🏫 MiEscuela</h2>
        </div>
        <ul class="nav-links">
            <?php if(isset($_GET["ruta"]) && $_GET["ruta"] == "tienda"): ?>
            <li><a href="index.php">🏠 Inicio</a></li>
            <li><a href="#" class="btn-carrito-nav" onclick="toggleCarrito()">🛒 Carrito (<span id="contador-carrito">0</span>)</a></li>
        <?php else: ?>
            <li><a href="#inicio">Inicio</a></li>
            <li><a href="#nosotros">Nosotros</a></li>
            <li><a href="#academico">Oferta Académica</a></li>
            <li><a href="#docentes">Docentes</a></li>
            <li><a href="#instalaciones">Instalaciones</a></li>
            <li><a href="#contacto">Contacto</a></li>
            <li><a href="tienda" class="btn-venta">🛒 Tienda</a></li>
        <?php endif; ?>
        </ul>
    </nav>

    <main>
        <?php
        // Enrutador MVC básico
        if(isset($_GET["ruta"])){
            if($_GET["ruta"] == "tienda"){
                include "tienda.php"; 
            } else {
                include "inicio.php";
            }
        } else {
            include "inicio.php";
        }
        ?>
    </main>

    <footer class="footer-escolar">
        <p>&copy; 2026 Instituto Tecnológico. Todos los derechos reservados.</p>
    </footer>

    <script src="view/js/app.js"></script>
    <script src="view/js/tienda.js"></script>
</body>
</html>