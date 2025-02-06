<?php
session_start();
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "administrador") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS (Para animaciones y funcionalidad) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>

    <!-- Barra superior -->
    <header class="top-bar">
        <img src="../assets/img/logo_canarias.png" alt="Gobierno de Canarias" class="logo-canarias">
        <input type="text" placeholder="Buscar..." class="search-bar">
        
        <div class="user-menu">
            <img src="../assets/img/user_icon.png" alt="Usuario" class="user-icon">
            <div class="dropdown-content">
                <a href="perfil.php">Mi Perfil</a>
                <a href="../controllers/logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </header>

    <!-- Barra lateral -->
    <nav class="sidebar">
        <ul>
            <li><a href="#" onclick="mostrarSeccion('noticias')">📰 Noticias</a></li>
            <li><a href="#" onclick="mostrarSeccion('solicitudes')">📄 Solicitudes</a></li>
            <li><a href="#" onclick="mostrarSeccion('adjudicaciones')">🏫 Adjudicaciones</a></li>
            <li><a href="#" onclick="mostrarSeccion('formacion')">📚 Formación</a></li>
        </ul>
    </nav>

    <!-- Contenido dinámico -->
    <main class="content">
        <section id="noticias" class="section">
            <h3>Gestión de Noticias</h3>
            <p>Aquí podrás crear, editar y eliminar noticias.</p>
        </section>

        <section id="solicitudes" class="section" style="display:none;">
            <h3>Gestión de Solicitudes</h3>
            <p>Aquí se gestionan todas las solicitudes enviadas por docentes.</p>
        </section>

        <section id="adjudicaciones" class="section" style="display:none;">
            <h3>Gestión de Adjudicaciones</h3>
            <p>Aquí puedes administrar las adjudicaciones de docentes.</p>
        </section>

        <section id="formacion" class="section" style="display:none;">
            <h3>Formación</h3>
            <p>Espacio en desarrollo para gestionar formación docente.</p>
        </section>
    </main>

</body>
</html>
