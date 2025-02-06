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
    <title>Portal Docente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>

    <!-- Botón para colapsar la barra lateral (CORREGIDO) -->
    <button id="toggle-btn" class="toggle-sidebar-btn" onclick="toggleSidebar()">☰</button>

    <!-- Barra superior -->
    <header class="top-bar">
        <img src="../assets/img/logo_canarias.png" alt="Gobierno de Canarias" class="logo-canarias">
        
    <!-- Campo de búsqueda -->
        <div class="search-container">
            <input type="text" placeholder="Buscar..." class="search-bar">
            <button class="search-btn"><i class="fas fa-search"></i></button>
        </div>

 <!-- Icono de usuario  -->
<div class="user-menu">
    <img src="../assets/icons/user_static.png" alt="Usuario" class="user-icon">
    <div class="dropdown-content" id="userDropdown">
        <a href="perfil.php">Mi Perfil</a>
        <a href="../controllers/logout.php">Cerrar Sesión</a>
    </div>
</div>

<!-- Barra lateral con iconos -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-content">
        <a href="dashboard.php" class="nav-link <?php echo ($pagina_activa == 'dashboard') ? 'active' : ''; ?>">
            <img src="../assets/icons/home_static.png" alt="Inicio" class="sidebar-icon"> <span>Inicio</span>
        </a>
        <a href="noticias.php" class="nav-link <?php echo ($pagina_activa == 'noticias') ? 'active' : ''; ?>">
            <img src="../assets/icons/news_static.png" alt="Noticias" class="sidebar-icon"> <span>Noticias</span>
        </a>
        <a href="ambito.php" class="nav-link <?php echo ($pagina_activa == 'ambito') ? 'active' : ''; ?>">
            <img src="../assets/icons/island_static.png" alt="Ámbito" class="sidebar-icon"> <span>Ámbito</span>
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
        <h2>¡Bienvenido, <?php echo htmlspecialchars($_SESSION["usuario"]); ?>!</h2>
        
    </main>

</body>
</html>
