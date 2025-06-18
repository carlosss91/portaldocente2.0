<?php
// views/partials/sidebar.php

// Obtiene el nombre del fichero actual sin extensión, p.ej. 'solicitudes'
$pagina_activa = basename($_SERVER['SCRIPT_NAME'], '.php');
?>
<nav class="sidebar" id="sidebar">
    <div class="sidebar-content">
        <a href="dashboard.php"
           class="nav-link <?= $pagina_activa === 'dashboard' ? 'active' : '' ?>">
            <img src="/portaldocente/assets/icons/home_static.png" alt="Inicio" class="sidebar-icon">
            <span>Inicio</span>
        </a>
        <a href="noticias.php"
           class="nav-link <?= $pagina_activa === 'noticias' ? 'active' : '' ?>">
            <img src="/portaldocente/assets/icons/news_static.png" alt="Noticias" class="sidebar-icon">
            <span>Noticias</span>
        </a>
        <a href="adjudicaciones.php"
           class="nav-link <?= $pagina_activa === 'adjudicaciones' ? 'active' : '' ?>">
            <img src="/portaldocente/assets/icons/island_static.png" alt="Adjudicaciones" class="sidebar-icon">
            <span>Adjudicaciones</span>
        </a>
        <a href="solicitudes.php"
           class="nav-link <?= $pagina_activa === 'solicitudes' ? 'active' : '' ?>">
            <img src="/portaldocente/assets/icons/request_static.png" alt="Solicitudes" class="sidebar-icon">
            <span>Solicitudes</span>
        </a>
        <a href="formacion.php"
           class="nav-link <?= $pagina_activa === 'formacion' ? 'active' : '' ?>">
            <img src="/portaldocente/assets/icons/education_static.png" alt="Formación" class="sidebar-icon">
            <span>Formación</span>
        </a>
    </div>
</nav>
