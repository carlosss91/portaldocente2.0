<?php
session_start();
require_once '../models/Usuario.php';
require_once '../models/Noticia.php';
require_once '../models/Adjudicacion.php';
require_once '../models/Solicitud.php';

// Verificar que el usuario haya iniciado sesión y tenga el rol de administrador
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "administrador") {
    header("Location: login.php");
    exit();
}

// Obtener los usuarios
$usuarioModel = new Usuario();
$usuarios = $usuarioModel->obtenerUsuarios();

// Obtener las noticias
$noticiaModel = new Noticia();
$noticias = $noticiaModel->obtenerNoticias();

// Obtener las adjudicaciones (corregido el error de $)
$adjudicacionModel = new Adjudicacion();
$adjudicaciones = $adjudicacionModel->obtenerTodasAdjudicaciones(); // Se corrigió la función llamada

// Obtener las solicitudes
$solicitudModel = new Solicitud();
$solicitudes = $solicitudModel->obtenerTodasSolicitudes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
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
                <img src="../assets/icons/search_static.png" alt="Buscar" class="sidebar-icon">
            </button>
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
    <main class="content">    
    <h2 class="page-title">Panel de Administración</h2>
        
       <!-- Sección de Usuarios -->
       <section>
            <h3>Usuarios</h3>
            <button class="btn btn-primary" id="btnNuevoUsuario">Nuevo Usuario</button>
            <button class="btn btn-secondary">Ordenar A-Z</button>
            <button class="btn btn-secondary">Ordenar por Puntuación</button>
            <button class="btn btn-secondary">Ordenar por Fecha de Creación</button>

         <!-- Formulario para agregar un nuevo usuario (oculto) -->
            <div id="formularioUsuario" style="display: none; margin-top: 15px;">
                <form action="../controllers/UsuarioController.php" method="POST">
                <input type="hidden" name="action" value="crear"> <!-- 🔹 Agregar esta línea -->
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" class="form-control form-control-sm" required>
                    </div>

                    <div class="form-group">
                        <label for="apellido">Apellido:</label>
                        <input type="text" name="apellido" class="form-control form-control-sm" required>
                    </div>
                    <label for="dni">DNI:</label>
                    <input type="text" name="dni" class="form-control form-control-sm" required>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" class="form-control form-control-sm" required>
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <input type="text" name="telefono" class="form-control form-control-sm" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Contraseña:</label>
                        <input type="password" name="password" class="form-control form-control-sm" required>
                    </div>

                    <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select name="rol" class="form-control form-control-sm" required>
                        <option value="docente">Docente</option>
                        <option value="administrador">Administrador</option>
                    </select>

                    </div>

                    <div class="form-group">
                        <label for="isla">Isla:</label>
                        <select name="isla" class="form-control form-control-sm">
                            <option value="Tenerife">Tenerife</option>
                            <option value="Gran Canaria">Gran Canaria</option>
                            <option value="Lanzarote">Lanzarote</option>
                            <option value="Fuerteventura">Fuerteventura</option>
                            <option value="La Palma">La Palma</option>
                            <option value="La Gomera">La Gomera</option>
                            <option value="El Hierro">El Hierro</option>
                            <option value="La Graciosa">La Graciosa</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="disponibilidad">Disponibilidad:</label>
                        <select name="disponibilidad" class="form-control form-control-sm">
                            <option value="1">Disponible</option>
                            <option value="0">No Disponible</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="puntuacion">Puntuación:</label>
                        <input type="number" name="puntuacion" class="form-control form-control-sm" step="0.01" required>
                    </div>

                    <button type="submit" class="btn btn-success">Guardar</button>
                </form>
            </div>


            <!-- Tabla con la lista de usuarios -->
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI/NIE</th>
                        <th>Email</th>
                        <th>Disponibilidad</th>
                        <th>Isla</th>
                        <th>Puntuación</th>
                        <th>Puesto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                    <td><?= htmlspecialchars($usuario['id_usuario']); ?></td>
                    <td><?= htmlspecialchars($usuario['nombre']); ?></td>
                        <td><?= htmlspecialchars($usuario['apellido']); ?></td>
                        <td><?= htmlspecialchars($usuario['dni']); ?></td>
                        <td><?= htmlspecialchars($usuario['email']); ?></td>
                        <td><?= $usuario['disponibilidad'] ? 'Disponible' : 'No Disponible'; ?></td>
                        <td><?= htmlspecialchars($usuario['isla']); ?></td>
                        <td><?= htmlspecialchars($usuario['puntuacion']); ?></td>
                        <td><?= $usuarioModel->obtenerPuestoEnLista($usuario['id_usuario']); ?></td>
                        <td>
                        <form action="../controllers/UsuarioController.php" method="POST">
                            <input type="hidden" name="action" value="editar">
                            <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario']; ?>">
                            <button type="submit" class="btn btn-light border">
                                <img src="../assets/icons/editar.png" alt="Editar" width="20">
                            </button>
                        </form>
                        <form action="../controllers/UsuarioController.php" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario? Esta acción no se puede deshacer.');">
                            <input type="hidden" name="action" value="eliminar">
                            <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario']; ?>">
                            <button type="submit" class="btn btn-light border">
                                <img src="../assets/icons/eliminar.png" alt="Eliminar" width="20">
                            </button>
                        </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
         

        </section>

        <!-- Sección de Noticias -->
<section>
    <h3>Noticias</h3>
    <button id="btnNuevaNoticia" class="btn btn-primary">Nueva Noticia</button>

    <!-- Formulario para agregar una nueva noticia (inicialmente oculto) -->
    <div id="formularioNoticia" style="display: none; margin-top: 15px;">
        <form action="../controllers/NoticiaController.php" method="POST">
            <input type="hidden" name="accion" value="agregar">
            <input type="hidden" name="autor_id" value="<?= $_SESSION['id_usuario']; ?>">

            <label for="titulo">Título:</label>
            <input type="text" name="titulo" class="form-control" required>

            <label for="contenido">Contenido:</label>
            <textarea name="contenido" class="form-control" required></textarea>

            <label for="imagen">URL de Imagen:</label>
            <input type="text" name="imagen_url" class="form-control">

            <button type="submit" class="btn btn-success">Guardar</button>
            <button type="button" id="btnCancelar" class="btn btn-secondary">Cancelar</button>
        </form>
    </div>

    <!-- Tabla con la lista de noticias -->
    <table class="table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Contenido</th>
                <th>Imagen</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($noticias as $noticia): ?>
            <tr>
                <td><?= htmlspecialchars($noticia['titulo']); ?></td>
                <td><?= htmlspecialchars(substr($noticia['contenido'], 0, 100)) . '...'; ?></td>
                <td><?= basename($noticia['imagen_url']); ?></td>
                <td><?= htmlspecialchars($noticia['fecha']); ?></td>
                <td>
                <form action="../controllers/NoticiaController.php" method="POST" style="display:inline;">
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id_noticia" value="<?= $noticia['id_noticia']; ?>">
                    <button type="submit" class="btn btn-light border" onclick="return confirm('¿Seguro que deseas eliminar esta noticia?');">
                        <img src="../assets/icons/eliminar.png" alt="Eliminar" width="20">
                    </button>
                </form>

                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>


        <!-- Sección de Adjudicaciones -->
        <section>
            <h3>Adjudicaciones</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Isla</th>
                        <th>Municipio</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($adjudicaciones as $adjudicacion): ?>
                    <tr>
                        <td><?= htmlspecialchars($adjudicacion['nombre_usuario']); ?></td>
                        <td><?= htmlspecialchars($adjudicacion['isla']); ?></td>
                        <td><?= htmlspecialchars($adjudicacion['municipio']); ?></td>
                        <td><?= htmlspecialchars($adjudicacion['fecha_adjudicacion']); ?></td>
                        <td>
                            <button class="btn btn-light border"><img src="../assets/icons/editar.png" alt="Editar" width="20"></button>
                            <button class="btn btn-light border"><img src="../assets/icons/eliminar.png" alt="Eliminar" width="20"></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <!-- Sección de Solicitudes -->
        <section>
            <h3>Solicitudes</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Detalles</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($solicitudes as $solicitud): ?>
                    <tr>
                        <td><?= htmlspecialchars($solicitud['nombre_usuario']); ?></td>
                        <td><?= htmlspecialchars($solicitud['tipo_solicitud']); ?></td>
                        <td><?= htmlspecialchars($solicitud['estado_solicitud']); ?></td>
                        <td><?= htmlspecialchars($solicitud['detalles_destino_solicitado']); ?></td>
                        <td><?= htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
                        <td>
                            <button class="btn btn-light border"><img src="../assets/icons/editar.png" alt="Editar" width="20"></button>
                            <button class="btn btn-light border"><img src="../assets/icons/eliminar.png" alt="Eliminar" width="20"></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-left">© 2025 Gobierno de Canarias - Consejería de Educación</div>
        <div class="footer-right">
            <a href="#">Sobre Nosotros</a>
            <a href="#">Aviso Legal</a>
        </div>
    </footer>
</body>
</html>
