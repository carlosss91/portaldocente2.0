<?php
// view/partials/header.php

// 1) Si no has definido BASE_URL (p.ej. en config.php), lo calculamos aquí:
//    - $_SERVER['SCRIPT_NAME'] nos da la ruta URL desde la raíz hasta el .php actual
//    - dirname(..., 2) sube dos niveles: desde /views/noticias.php a /
//    - así BASE_URL apunta siempre a la carpeta raíz de tu app
if (!defined('BASE_URL')) {
    $base = dirname($_SERVER['SCRIPT_NAME'], 2);
    define('BASE_URL', $base === DIRECTORY_SEPARATOR ? '' : $base);
}
?>
<header class="top-bar">
    <!-- Botón de colapsar -->
    <button id="toggle-btn" class="toggle-sidebar-btn" onclick="toggleSidebar()">
        <img id="collapse-icon"
             src="<?= BASE_URL ?>/assets/icons/menu_static.png"
             alt="Colapsar"
             class="toggle-icon sidebar-icon">
    </button>

    <!-- Logo -->
    <img src="<?= BASE_URL ?>/assets/img/logo_canarias.png"
         alt="Gobierno de Canarias"
         class="logo-canarias">

    <!-- Búsqueda -->
    <div class="search-container">
        <input type="text" placeholder="Buscar..." class="search-bar">
        <button class="search-btn">
            <img src="<?= BASE_URL ?>/assets/icons/search_static.png"
                 alt="Buscar"
                 class="sidebar-icon">
        </button>
    </div>

    <!-- Menú de usuario -->
    <div class="user-menu" onclick="toggleUserMenu(event)">
        <img src="<?= BASE_URL ?>/assets/icons/user_static.png"
             alt="Usuario"
             class="user-icon">
        <div class="dropdown-content" id="userDropdown">
            <a href="<?= BASE_URL ?>/views/usuario.php">Mi Perfil</a>
            <a href="<?= BASE_URL ?>/controllers/logout.php">Cerrar Sesión</a>
        </div>
    </div>
</header>
