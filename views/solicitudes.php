<?php
session_start();
require_once '../models/Solicitud.php';

// Verifica que el usuario haya iniciado sesión y tenga el rol adecuado
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "docente") {
    header("Location: login.php"); // Redirige al login si el usuario no está autenticado
    exit();
}

// Verifica si el ID del usuario está definido en la sesión
if (!isset($_SESSION["id_usuario"])) {
    die("Error: ID de usuario no definido en la sesión.");
}

// Crea una instancia del modelo de solicitudes
$solicitudModel = new Solicitud();

// Obtiene las solicitudes del usuario autenticado
$solicitudes = $solicitudModel->obtenerSolicitudes($_SESSION["id_usuario"]);

// Asegurar que $solicitudes sea un array antes de recorrerlo
if (!is_array($solicitudes)) {
    $solicitudes = []; // Si la consulta falla, se asigna un array vacío para evitar errores en el bucle
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/solicitudes.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>
      <!-- Barra superior -->
      <header class="top-bar">
        <!-- Botón de colapsar con iconos dinámicos -->
        <button id="toggle-btn" class="toggle-sidebar-btn" onclick="toggleSidebar()">
            <img id="collapse-icon" src="../assets/icons/menu_static.png" alt="Colapsar" class="toggle-icon sidebar-icon">
        </button>


        <!-- Logo Gobierno de Canarias -->
        <img src="../assets/img/logo_canarias.png" alt="Gobierno de Canarias" class="logo-canarias">
        
        <!-- Campo de búsqueda -->
        <div class="search-container">
            <input type="text" placeholder="Buscar..." class="search-bar">
            <button class="search-btn">
                <i class="fas fa-search">
                <img src="../assets/icons/search_static.png" alt="Inicio" class="sidebar-icon">
            </i></button>
        </div>

        <!-- Icono de usuario -->
        <div class="user-menu" onclick="toggleUserMenu(event)">
            <img src="../assets/icons/user_static.png" alt="Usuario" class="user-icon">
            <div class="dropdown-content" id="userDropdown">
                <a href="perfil.php">Mi Perfil</a>
                <a href="../controllers/logout.php">Cerrar Sesión</a>
            </div>
        </div>

    </header>


    <!-- Barra lateral -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-content">
            <a href="dashboard.php" class="nav-link <?php echo ($pagina_activa == 'dashboard') ? 'active' : ''; ?>">
                <img src="../assets/icons/home_static.png" alt="Inicio" class="sidebar-icon"> <span>Inicio</span>
            </a>
            <a href="noticias.php" class="nav-link <?php echo ($pagina_activa == 'noticias') ? 'active' : ''; ?>">
                <img src="../assets/icons/news_static.png" alt="Noticias" class="sidebar-icon"> <span>Noticias</span>
            </a>
            <a href="adjudicaciones.php" class="nav-link <?php echo ($pagina_activa == 'adjudicaciones') ? 'active' : ''; ?>">
                <img src="../assets/icons/island_static.png" alt="Adjudicaciones" class="sidebar-icon"> <span>Adjudicaciones</span>
            </a>
            <a href="solicitudes.php" class="nav-link <?php echo ($pagina_activa == 'solicitudes') ? 'active' : ''; ?>">
                <img src="../assets/icons/request_static.png" alt="Solicitudes" class="sidebar-icon"> <span>Solicitudes</span>
            </a>
            <a href="formacion.php" class="nav-link <?php echo ($pagina_activa == 'formacion') ? 'active' : ''; ?>">
                <img src="../assets/icons/education_static.png" alt="Formación" class="sidebar-icon"> <span>Formación</span>
            </a>
        </div>
    </nav>

    <!-- Contenido -->
    <main class="content">
        <h2 class="page-title">Solicitudes</h2>
        <button id="mostrarFormulario" class="btn btn-primary" onclick="toggleFormularioSolicitudes()">Nueva Solicitud</button>
        
        <div id="formularioSolicitud" style="display:none;">
            <form action="../controllers/SolicitudController.php" method="POST">
                <label for="tipo_solicitud">Tipo de solicitud:</label>
                <select name="tipo_solicitud" class="form-select" required>
                    <option value="">Seleccione el tipo</option>
                    <option value="cambio de destino">Cambio de destino</option>
                    <option value="nueva adjudicación">Nueva adjudicación</option>
                </select>
                
                <label for="detalles_destino_solicitado">Detalles del destino solicitado:</label>
                <textarea name="detalles_destino_solicitado" class="form-control" required></textarea>
                
                <button type="submit" class="btn btn-success">Enviar Solicitud</button>
            </form>
        </div>

        <!-- Tabla de solicitudes -->
        <table class="table">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Estado</th>
            <th>Detalles</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($solicitudes as $solicitud): ?>
        <tr data-id="<?= $solicitud['id_solicitud'] ?>">
            <td><?= htmlspecialchars($solicitud["tipo_solicitud"] ?? 'No disponible') ?></td>
            <td><?= htmlspecialchars($solicitud["estado_solicitud"] ?? 'No disponible') ?></td>
            <td><?= htmlspecialchars($solicitud["detalles_destino_solicitado"] ?? 'No disponible') ?></td>
            <td><?= htmlspecialchars($solicitud["fecha_solicitud"] ?? 'No disponible') ?></td>
            <td>
                <button class="btn btn-light border" onclick="eliminarSolicitud(this)" title="Eliminar">
                    <img src="../assets/icons/eliminar.png" alt="Eliminar" width="20">
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    </main>
       <!-- Pie de página -->
       <footer class="footer">
        <div class="footer-left">© 2025 Gobierno de Canarias - Consejería de Educación</div>
        <div class="footer-right">
            <a href="#">Sobre Nosotros</a>
            <a href="#">Aviso Legal</a>
        </div>
    </footer>
</body>
</html>
