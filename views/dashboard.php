<?php
session_start();
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "docente") {
    header("Location: login.php");
    exit();
}

// Detectar la página activa para resaltar el apartado correspondiente
$pagina_activa = basename($_SERVER['PHP_SELF'], ".php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Docente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
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
                <a href="usuario.php">Mi Perfil</a>
                <a href="../controllers/logout.php">Cerrar Sesión</a>
            </div>
        </div>

    </header>

    <!-- Barra lateral con iconos -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-content">
            <a href="dashboard.php" class="nav-link <?php echo ($pagina_activa == 'dashboard') ? 'active' : ''; ?>">
                <img src="../assets/icons/home_static.png" alt="Inicio" class="sidebar-icon"> <span>Inicio</span>
            </a>
            <a href="noticias.php" class="nav-link <?php echo ($pagina_activa == 'noticias') ? 'active' : ''; ?>">
                <img src="../assets/icons/news_static.png" alt="Noticias" class="sidebar-icon"> <span>Noticias</span>
            </a>
            <a href="adjudicaciones.php" class="nav-link <?php echo ($pagina_activa == 'Adjudicaciones') ? 'active' : ''; ?>">
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

    <!-- Contenido dinámico -->
    <main class="content">
        <h2 class="welcome-text">¡Bienvenido, <?php echo htmlspecialchars($_SESSION["usuario"]); ?>!</h2>
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
