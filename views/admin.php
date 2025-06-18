<?php
session_start();

// Ahora requerimos el controller, no sólo el modelo
require_once '../controllers/UsuarioController.php';
require_once '../models/Noticia.php';
require_once '../models/Adjudicacion.php';
require_once '../models/Solicitud.php';

// Verificar que el usuario haya iniciado sesión y tenga el rol de administrador
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "administrador") {
    header("Location: login.php");
    exit();
}

// Instanciamos el controller de usuarios
$usuarioController = new UsuarioController();

// Leemos parámetros de ordenación (si los hay)
$sort  = $_GET['sort']  ?? null;
$order = $_GET['order'] ?? 'asc';

// Obtenemos la lista de usuarios ordenada o por defecto
$usuarios     = $usuarioController->listarUsuarios($sort, $order);
// Para calcular el puesto en la lista seguimos usando el modelo interno
$usuarioModel = $usuarioController->getUsuarioModel();

// Obtener las noticias
$noticiaModel     = new Noticia();
$noticias         = $noticiaModel->obtenerNoticias();

// Obtener las adjudicaciones
$adjudicacionModel = new Adjudicacion();
$adjudicaciones    = $adjudicacionModel->obtenerTodasAdjudicaciones();

// Obtener las solicitudes
$solicitudModel = new Solicitud();
$solicitudes    = $solicitudModel->obtenerTodasSolicitudes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <!-- Tus estilos -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <!-- Main centrado con .admin-container -->
    <main class="content admin-container">
        <h2 class="page-title">Panel de Administración</h2>

     <?php
        // Calculamos el próximo orden para cada columna
        $nextName  = ($sort==='nombre'         && $order==='asc') ? 'desc' : 'asc';
        $nextPunt  = ($sort==='puntuacion'     && $order==='asc') ? 'desc' : 'asc';
        $nextFecha = ($sort==='fecha_creacion' && $order==='asc') ? 'desc' : 'asc';
    ?>
    <section>
        <h3>Usuarios</h3>
        <!-- Botón para mostrar el formulario de nuevo usuario -->
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
            <button class="btn btn-primary" id="btnNuevoUsuario">Nuevo Usuario</button>
            <!-- Botones de ordenación -->
            <a href="?sort=nombre&order=<?= $nextName ?>" class="btn btn-secondary">
                A-Z <?= $sort==='nombre' ? ($order==='asc' ? '↑' : '↓') : '' ?>
            </a>
            <a href="?sort=puntuacion&order=<?= $nextPunt ?>" class="btn btn-secondary">
                Puntuación <?= $sort==='puntuacion' ? ($order==='asc' ? '↑' : '↓') : '' ?>
            </a>
            <a href="?sort=fecha_creacion&order=<?= $nextFecha ?>" class="btn btn-secondary">
                Fecha creación <?= $sort==='fecha_creacion' ? ($order==='asc' ? '↑' : '↓') : '' ?>
            </a>

        </div>

        <!-- Formulario para agregar un nuevo usuario (oculto) -->
        <div id="formularioUsuario" class="mt-3" style="display: none;">
            <form action="../controllers/UsuarioController.php" method="POST">
                <input type="hidden" name="action" value="crear">
                <!-- grid de inputs -->
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" class="form-control form-control-sm" required>
                </div>
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" name="apellido" class="form-control form-control-sm" required>
                </div>
                <div class="form-group">
                    <label for="dni">DNI:</label>
                    <input type="text" name="dni" class="form-control form-control-sm" required>
                </div>
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

        <!-- Tabla de Usuarios -->
        <div class="table-responsive w-100 mb-4">
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
                    <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['id_usuario']) ?></td>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['apellido']) ?></td>
                        <td><?= htmlspecialchars($u['dni']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= $u['disponibilidad'] ? 'Disponible' : 'No Disponible' ?></td>
                        <td><?= htmlspecialchars($u['isla']) ?></td>
                        <td><?= htmlspecialchars($u['puntuacion']) ?></td>
                        <td><?= $usuarioModel->obtenerPuestoEnLista($u['id_usuario']) ?></td>
                        <td>
                            <form action="../controllers/UsuarioController.php" method="POST" class="d-inline">
                                <input type="hidden" name="action" value="editar">
                                <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                                <button type="submit" class="btn btn-light border">
                                    <img src="../assets/icons/editar.png" alt="Editar" width="20">
                                </button>
                            </form>
                            <form action="../controllers/UsuarioController.php" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este usuario?');">
                                <input type="hidden" name="action" value="eliminar">
                                <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                                <button type="submit" class="btn btn-light border">
                                    <img src="../assets/icons/eliminar.png" alt="Eliminar" width="20">
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

        <!-- Sección de Noticias -->
        <section>
            <h3>Noticias</h3>
        <!-- Botón para mostrar el formulario de nueva noticia -->
            <button id="btnNuevaNoticia" class="btn btn-primary">Nueva Noticia</button>
            
            <!-- Formulario para agregar una nueva noticia (oculto) -->
            <div id="formularioNoticia" class="mt-3" style="display: none;">
                <form action="../controllers/NoticiaController.php" method="POST">
                    <input type="hidden" name="accion" value="agregar">
                    <input type="hidden" name="autor_id" value="<?= $_SESSION['id_usuario']; ?>">
                    <div class="mb-2">
                        <label for="titulo">Título:</label>
                        <input type="text" name="titulo" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label for="contenido">Contenido:</label>
                        <textarea name="contenido" class="form-control"></textarea>
                    </div>
                    <div class="mb-2">
                        <label for="imagen">URL de Imagen:</label>
                        <input type="text" name="imagen_url" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <button type="button" id="btnCancelar" class="btn btn-secondary">Cancelar</button>
                </form>
            </div>
            <div class="table-responsive w-100 mb-4">
                <table class="table">
                    <thead>
                        <tr><th>Título</th><th>Contenido</th><th>Imagen</th><th>Fecha</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($noticias as $n): ?>
                        <tr>
                            <td><?= htmlspecialchars($n['titulo']); ?></td>
                            <td><?= htmlspecialchars(substr($n['contenido'],0,100)) . '...'; ?></td>
                            <td><?= basename($n['imagen_url']); ?></td>
                            <td><?= htmlspecialchars($n['fecha']); ?></td>
                            <td class="text-center">
                                <form action="../controllers/NoticiaController.php" method="POST" class="d-inline">
                                    <input type="hidden" name="accion" value="eliminar">
                                    <input type="hidden" name="id_noticia" value="<?= $n['id_noticia']; ?>">
                                    <button type="submit" class="btn btn-light border" onclick="return confirm('¿Eliminar noticia?');">
                                        <img src="../assets/icons/eliminar.png" alt="Eliminar" width="20">
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Sección de Adjudicaciones -->
        <section>
            <h3>Adjudicaciones</h3>
            <div class="table-responsive w-100 mb-4">
                <table class="table">
                    <thead>
                        <tr><th>Usuario</th><th>Isla</th><th>Municipio</th><th>Fecha</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($adjudicaciones as $a): ?>
                        <tr>
                            <td><?= htmlspecialchars($a['nombre_usuario']); ?></td>
                            <td><?= htmlspecialchars($a['isla']); ?></td>
                            <td><?= htmlspecialchars($a['municipio']); ?></td>
                            <td><?= htmlspecialchars($a['fecha_adjudicacion']); ?></td>
                            <td>
                                <button class="btn btn-light border"><img src="../assets/icons/editar.png" alt="Editar" width="20"></button>
                                <button class="btn btn-light border"><img src="../assets/icons/eliminar.png" alt="Eliminar" width="20"></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Sección de Solicitudes -->
        <section>
            <h3>Solicitudes</h3>
            <div class="table-responsive w-100 mb-4">
                <table class="table">
                    <thead>
                        <tr><th>Usuario</th><th>Tipo</th><th>Estado</th><th>Detalles</th><th>Fecha</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($solicitudes as $s): ?>
                        <tr>
                            <td><?= htmlspecialchars($s['nombre_usuario']); ?></td>
                            <td><?= htmlspecialchars($s['tipo_solicitud']); ?></td>
                            <td><?= htmlspecialchars($s['estado_solicitud']); ?></td>
                            <td><?= htmlspecialchars($s['detalles_destino_solicitado']); ?></td>
                            <td><?= htmlspecialchars($s['fecha_solicitud']); ?></td>
                            <td>
                                <button class="btn btn-light border"><img src="../assets/icons/editar.png" alt="Editar" width="20"></button>
                                <button class="btn btn-light border"><img src="../assets/icons/eliminar.png" alt="Eliminar" width="20"></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <?php include 'partials/footer.php'; ?>
</body>
</html>
