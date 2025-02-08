<?php
session_start();
require_once '../models/Adjudicacion.php';

// Verifica que el usuario haya iniciado sesión y tenga el rol adecuado
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "docente") {
    header("Location: login.php"); // Redirige al login si el usuario no está autenticado
    exit();
}

// Verifica si el ID del usuario está definido en la sesión
if (!isset($_SESSION["id_usuario"])) {
    die("Error: ID de usuario no definido en la sesión.");
}

// Crea una instancia del modelo de adjudicaciones
$adjudicacionModel = new Adjudicacion();

// Obtiene las adjudicaciones del usuario autenticado
$adjudicaciones = $adjudicacionModel->obtenerAdjudicaciones($_SESSION["id_usuario"]);

// Asegurar que $adjudicaciones sea un array antes de recorrerlo
if (!is_array($adjudicaciones)) {
    $adjudicaciones = []; // Si la consulta falla, se asigna un array vacío para evitar errores en el bucle
}

// Listado de islas y municipios actualizados
$municipios_por_isla = [
    "Tenerife" => ["Adeje", "Arafo", "Arico", "Arona", "Buenavista del Norte", "Candelaria", "El Rosario", "El Sauzal", "El Tanque", "Fasnia", "Garachico", "Granadilla de Abona", "Guía de Isora", "Güímar", "Icod de los Vinos", "La Guancha", "La Matanza de Acentejo", "La Orotava", "La Victoria de Acentejo", "Los Realejos", "Puerto de la Cruz", "San Cristóbal de La Laguna", "San Juan de la Rambla", "San Miguel de Abona", "Santa Cruz de Tenerife", "Santa Úrsula", "Santiago del Teide", "Tacoronte", "Tegueste", "Vilaflor de Chasna"],
    "Gran Canaria" => ["Agaete", "Agüimes", "Artenara", "Arucas", "Firgas", "Gáldar", "Ingenio", "La Aldea de San Nicolás", "Las Palmas de Gran Canaria", "Mogán", "Moya", "San Bartolomé de Tirajana", "Santa Brígida", "Santa Lucía de Tirajana", "Santa María de Guía", "Tejeda", "Telde", "Teror", "Valleseco", "Valsequillo de Gran Canaria", "Vega de San Mateo"],
    "Lanzarote" => ["Arrecife", "Haría", "San Bartolomé", "Teguise", "Tías", "Tinajo", "Yaiza"],
    "Fuerteventura" => ["Antigua", "Betancuria", "La Oliva", "Pájara", "Puerto del Rosario", "Tuineje"],
    "La Palma" => ["Barlovento", "Breña Alta", "Breña Baja", "Fuencaliente", "Garafía", "Los Llanos de Aridane", "El Paso", "Puntagorda", "Puntallana", "San Andrés y Sauces", "Santa Cruz de La Palma", "Tazacorte", "Tijarafe", "Villa de Mazo"],
    "La Gomera" => ["Agulo", "Alajeró", "Hermigua", "San Sebastián de La Gomera", "Valle Gran Rey", "Vallehermoso"],
    "El Hierro" => ["Frontera", "El Pinar de El Hierro", "Valverde"],
    "La Graciosa" => ["Caleta de Sebo"]
];

// Determina la página activa para resaltar la opción correspondiente en la barra lateral
$pagina_activa = basename($_SERVER['PHP_SELF'], ".php");
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
        <h2 class="page-title">Adjudicaciones</h2>
        <button id="mostrarFormulario" class="btn btn-primary" onclick="toggleFormulario()">Nueva Adjudicación</button>
        
        <div id="formularioAdjudicacion" style="display:none;">
            <form action="../controllers/adjudicacionesController.php" method="POST">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <div class="d-flex align-items-center mb-2 gap-3">
                        <span><?= $i ?>.</span>
                        <select name="adjudicaciones[<?= $i ?>][isla]" class="form-select" id="isla<?= $i ?>" onchange="actualizarMunicipios(this, <?= $i ?>)">
                            <option value="">Seleccione una isla</option>
                            <?php foreach (array_keys($municipios_por_isla) as $isla): ?>
                                <option value="<?= $isla ?>"><?= $isla ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="adjudicaciones[<?= $i ?>][municipio]" class="form-select" id="municipio<?= $i ?>">
                            <option value="">Seleccione un municipio</option>
                        </select>
                    </div>
                <?php endfor; ?>
                <button type="submit" class="btn btn-success">Guardar</button>
            </form>
    </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Isla</th>
                    <th>Municipio</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($adjudicaciones as $adj): ?>
                <tr>
                    <td><?= htmlspecialchars($adj["isla"] ?? 'No disponible') ?></td>
                    <td><?= htmlspecialchars($adj["municipio"] ?? 'No disponible') ?></td>
                    <td><?= htmlspecialchars($adj["fecha_adjudicacion"] ?? 'No disponible') ?></td>
                    <td>
                        <button class="btn btn-warning">Editar</button>
                        <button class="btn btn-danger">Eliminar</button>
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
