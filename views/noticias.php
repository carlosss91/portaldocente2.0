<?php
session_start();
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "docente") {
    header("Location: login.php");
    exit();
}

// Detectar la página activa
$pagina_activa = basename($_SERVER['PHP_SELF'], ".php");

// Incluir el controlador de noticias
require_once '../controllers/NoticiaController.php';

// Obtener las noticias desde el controlador
$noticias = $noticiaController->mostrarNoticias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

        <img src="../assets/img/logo_canarias.png" alt="Gobierno de Canarias" class="logo-canarias">
        
        <div class="search-container">
            <input type="text" placeholder="Buscar..." class="search-bar">
            <button class="search-btn"><i class="fas fa-search"></i></button>
        </div>

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
            <a href="dashboard.php" class="nav-link <?php echo ($pagina_activa == 'dashboard') ? 'active' : ''; ?>">
                <img src="../assets/icons/home_static.png" alt="Inicio" class="sidebar-icon"> <span>Inicio</span>
            </a>
            <a href="noticias.php" class="nav-link active">
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

<!-- Contenido de noticias -->
<main class="content">
    <h2 class="page-title">Noticias</h2>
    
    <?php if (!empty($noticias)): ?>
        <?php foreach ($noticias as $noticia): ?>
            <div class="news-container">
                <div class="news-card" id="news-card-<?php echo $noticia['id_noticia']; ?>" onclick="toggleNews(<?php echo $noticia['id_noticia']; ?>)">
                    <!-- 🔹 Contenedor de imagen -->
                    <div class="news-image-container">
                        <img src="<?php echo htmlspecialchars($noticia['imagen_url']); ?>" alt="Noticia" class="news-card-img">
                        <div class="news-title"><?php echo htmlspecialchars($noticia['titulo']); ?></div>
                    </div>
                    
                    <!-- 🔹 Contenido desplegable de la noticia -->
                    <div class="news-content" id="news-<?php echo $noticia['id_noticia']; ?>">
                        <p><?php echo nl2br(htmlspecialchars($noticia['contenido'])); ?></p>
                        <small>
                            <strong>Autor:</strong> <?php echo htmlspecialchars($noticia['autor']); ?> |
                            <strong>Fecha:</strong> <?php echo date("d/m/Y", strtotime($noticia['fecha'])); ?>
                        </small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay noticias disponibles.</p>
    <?php endif; ?>
</main>


    <!-- Pie de página -->
    <footer class="footer">
        <div class="footer-left">© 2025 Gobierno de Canarias - Consejería de Educación</div>
        <div class="footer-right">
            <a href="#">Sobre Nosotros</a>
            <a href="#">Aviso Legal</a>
        </div>
    </footer>

    <script>
        function toggleNews(id) {
            let content = document.getElementById("news-" + id);
            content.classList.toggle("show-news");
        }
    </script>

</body>
</html>
