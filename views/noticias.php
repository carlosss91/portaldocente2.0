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
    <title>Noticias</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/noticias.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>

    <!-- Barra superior -->
    <header class="top-bar">
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
        <div class="user-menu">
            <img src="../assets/icons/user_static.png" alt="Usuario" class="user-icon">
            <div class="dropdown-content" id="userDropdown">
                <a href="perfil.php">Mi Perfil</a>
                <a href="../controllers/logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </header>

    <!-- Barra lateral con iconos -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-content">
            <a href="dashboard.php" class="nav-link">
                <img src="../assets/icons/home_static.png" alt="Inicio" class="sidebar-icon"> <span>Inicio</span>
            </a>
            <a href="noticias.php" class="nav-link active">
                <img src="../assets/icons/news_static.png" alt="Noticias" class="sidebar-icon"> <span>Noticias</span>
            </a>
            <a href="ambito.php" class="nav-link">
                <img src="../assets/icons/island_static.png" alt="Ámbito" class="sidebar-icon"> <span>Ámbito</span>
            </a>
            <a href="solicitudes.php" class="nav-link">
                <img src="../assets/icons/request_static.png" alt="Solicitudes" class="sidebar-icon"> <span>Solicitudes</span>
            </a>
            <a href="formacion.php" class="nav-link">
                <img src="../assets/icons/education_static.png" alt="Formación" class="sidebar-icon"> <span>Formación</span>
            </a>
        </div>
    </nav>

    <!-- Contenido de noticias -->
    <main class="content">
        <h2 class="page-title">Noticias</h2>
        
        <div class="news-container">
            <div class="news-card" onclick="toggleNews(1)">
                <img src="../assets/img/consejeria.jpg" alt="Noticia 1">
                <div class="news-overlay">
                    <div class="news-title">La Consejería de Educación amplía la oferta de plazas docentes</div>
                </div>
                <div class="news-content" id="news-1">Más detalles sobre la noticia...</div>
            </div>
            <div class="news-card" onclick="toggleNews(2)">
                <img src="../assets/img/cursos.jpg" alt="Noticia 2">
                <div class="news-overlay">
                    <div class="news-title">Cursos gratuitos sobre herramientas digitales</div>
                </div>
                <div class="news-content" id="news-2">Más detalles sobre la noticia...</div>
            </div>
            <div class="news-card" onclick="toggleNews(3)">
                <img src="../assets/img/inclusion.jpg" alt="Noticia 3">
                <div class="news-overlay">
                    <div class="news-title">Nueva normativa sobre inclusión educativa</div>
                </div>
                <div class="news-content" id="news-3">Más detalles sobre la noticia...</div>
            </div>
            <div class="news-card" onclick="toggleNews(4)">
                <img src="../assets/img/innovacion.jpg" alt="Noticia 4">
                <div class="news-overlay">
                    <div class="news-title">Convocatoria de proyectos educativos innovadores</div>
                </div>
                <div class="news-content" id="news-4">Más detalles sobre la noticia...</div>
            </div>
        </div>
    </main>

    <script>
        function toggleNews(id) {
            let content = document.getElementById("news-" + id);
            content.classList.toggle("show-news");
        }
    </script>

</body>
</html>
