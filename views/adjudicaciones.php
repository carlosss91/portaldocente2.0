<?php
session_start();
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "docente") {
    header("Location: login.php");
    exit();
}

require_once '../models/Adjudicacion.php';
$adjudicacionModel = new Adjudicacion();
$adjudicaciones = $adjudicacionModel->obtenerAdjudicaciones($_SESSION["id_usuario"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adjudicaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
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

    <!-- Barra lateral -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-content">
            <a href="dashboard.php" class="nav-link">
                <img src="../assets/icons/home_static.png" alt="Inicio" class="sidebar-icon"> <span>Inicio</span>
            </a>
            <a href="noticias.php" class="nav-link">
                <img src="../assets/icons/news_static.png" alt="Noticias" class="sidebar-icon"> <span>Noticias</span>
            </a>
            <a href="adjudicaciones.php" class="nav-link active">
                <img src="../assets/icons/island_static.png" alt="Adjudicaciones" class="sidebar-icon"> <span>Adjudicaciones</span>
            </a>
            <a href="solicitudes.php" class="nav-link">
                <img src="../assets/icons/request_static.png" alt="Solicitudes" class="sidebar-icon"> <span>Solicitudes</span>
            </a>
            <a href="formacion.php" class="nav-link">
                <img src="../assets/icons/education_static.png" alt="Formación" class="sidebar-icon"> <span>Formación</span>
            </a>
        </div>
    </nav>

    <!-- Contenido -->
    <main class="content">
        <h2 class="page-title">Adjudicaciones</h2>

        <button id="mostrarFormulario" class="btn btn-primary">Nueva Adjudicación</button>

        <!-- Formulario de adjudicación -->
        <div id="formularioAdjudicacion" style="display:none;">
            <form action="../controllers/adjudicacionesController.php" method="POST">
                <label for="destino_actual">Destino:</label>
                <select name="destino_actual" required>
                    <option value="Tenerife">Tenerife</option>
                    <option value="Gran Canaria">Gran Canaria</option>
                    <option value="Lanzarote">Lanzarote</option>
                    <option value="Fuerteventura">Fuerteventura</option>
                    <option value="La Palma">La Palma</option>
                    <option value="La Gomera">La Gomera</option>
                    <option value="El Hierro">El Hierro</option>
                    <option value="La Graciosa">La Graciosa</option>
                </select>
                <button type="submit" class="btn btn-success">Guardar</button>
            </form>
        </div>

        <!-- Tabla de adjudicaciones -->
        <table class="table">
            <thead>
                <tr>
                    <th>Destino</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($adjudicaciones as $adj): ?>
                <tr>
                    <td><?= htmlspecialchars($adj["destino_actual"]) ?></td>
                    <td><?= htmlspecialchars($adj["fecha_adjudicacion"]) ?></td>
                    <td>
                        <form action="../controllers/adjudicacionesController.php" method="POST">
                            <input type="hidden" name="eliminar_adjudicacion" value="<?= $adj["id_adjudicacion"] ?>">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

</body>
</html>
