<?php
session_start();
require_once '../models/Usuario.php';

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

// Obtener la información del usuario
$usuarioModel = new Usuario();
$id_usuario = $_SESSION["id_usuario"];
$usuario = $usuarioModel->obtenerUsuarioPorId($id_usuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/usuario.css">
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
        <h2 class="page-title">Mi Perfil</h2>
        
        <!--Formulario de perfil-->
        <div class="perfil-container">
            <form action="../controllers/UsuarioController.php" method="POST">
                <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">

                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

                <label for="apellido">Apellido:</label>
                <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>

                <label for="email">Correo electrónico:</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>

                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($usuario['telefono']) ?>">

                <label for="disponibilidad">Disponibilidad:</label>
                <select name="disponibilidad" class="form-select">
                    <option value="1" <?= $usuario['disponibilidad'] ? 'selected' : '' ?>>Disponible</option>
                    <option value="0" <?= !$usuario['disponibilidad'] ? 'selected' : '' ?>>No Disponible</option>
                </select>

                <label for="isla">Isla:</label>
                <select name="isla" class="form-select">
                    <option value="Tenerife" <?= $usuario['isla'] === 'Tenerife' ? 'selected' : '' ?>>Tenerife</option>
                    <option value="Gran Canaria" <?= $usuario['isla'] === 'Gran Canaria' ? 'selected' : '' ?>>Gran Canaria</option>
                    <option value="Lanzarote" <?= $usuario['isla'] === 'Lanzarote' ? 'selected' : '' ?>>Lanzarote</option>
                    <option value="Fuerteventura" <?= $usuario['isla'] === 'Fuerteventura' ? 'selected' : '' ?>>Fuerteventura</option>
                    <option value="La Palma" <?= $usuario['isla'] === 'La Palma' ? 'selected' : '' ?>>La Palma</option>
                    <option value="La Gomera" <?= $usuario['isla'] === 'La Gomera' ? 'selected' : '' ?>>La Gomera</option>
                    <option value="El Hierro" <?= $usuario['isla'] === 'El Hierro' ? 'selected' : '' ?>>El Hierro</option>
                    <option value="La Graciosa" <?= $usuario['isla'] === 'La Graciosa' ? 'selected' : '' ?>>La Graciosa</option>
                </select>

                <!-- Mostrar la contraseña actual en un campo solo de lectura con botón para mostrar/ocultar -->
                <label for="password_actual">Contraseña Actual:</label>
                <div class="password-container">
                    <input type="password" id="password_actual" class="form-control" value="<?= htmlspecialchars($usuario['password']) ?>" disabled>
                    <button type="button" class="toggle-password" onclick="togglePassword('password_actual', 'eye-icon-actual')">
                        <img src="../assets/icons/eye_closed.png" alt="Mostrar" id="eye-icon-actual">
                    </button>
                </div>

                <!-- Input para ingresar una nueva contraseña (opcional) -->
                <label for="password">Nueva Contraseña (Opcional):</label>
                <div class="password-container">
                    <input type="password" name="password" id="password" class="form-control">
                    <button type="button" class="toggle-password" onclick="togglePassword('password', 'eye-icon')">
                        <img src="../assets/icons/eye_closed.png" alt="Mostrar" id="eye-icon">
                    </button>
                </div>



                <label for="puntuacion">Puntuación:</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['puntuacion']) ?>" disabled>

                <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </form>
        </div>
    </main>

</body>
</html>
