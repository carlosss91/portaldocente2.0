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
    <?php if(isset($_GET['error']) && $_GET['error']==='usuario_duplicado'): ?>
        <div class="alert alert-danger text-center">
            Ya existe un usuario con ese DNI o email. Por favor, utiliza otro.
        </div>
    <?php endif; ?>


<!-- ===========================================
     SECCIÓN DE USUARIOS
     =========================================== -->
<section>
  <h3>Usuarios</h3>

  <!-- Botones de acción y ordenación -->
  <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
    <!-- Nuevo usuario (abre formulario) -->
    <button class="btn btn-primary" id="btnNuevoUsuario">
      Nuevo Usuario
    </button>
    <!-- Ordenar alfabéticamente -->
    <a href="?sort=nombre&order=<?= $nextName ?>" class="btn btn-secondary">
      A-Z <?= $sort==='nombre' ? ($order==='asc' ? '↑' : '↓') : '' ?>
    </a>
    <!-- Ordenar por puntuación -->
    <a href="?sort=puntuacion&order=<?= $nextPunt ?>" class="btn btn-secondary">
      Puntuación <?= $sort==='puntuacion' ? ($order==='asc' ? '↑' : '↓') : '' ?>
    </a>
    <!-- Ordenar por fecha de creación -->
    <a href="?sort=fecha_creacion&order=<?= $nextFecha ?>" class="btn btn-secondary">
      Fecha creación <?= $sort==='fecha_creacion' ? ($order==='asc' ? '↑' : '↓') : '' ?>
    </a>
  </div>

  <!-- ===========================================
       FORMULARIO OCULTO DE CREAR/EDITAR
       =========================================== -->
  <div id="formularioUsuario"
       class="mt-3"
       style="display:none; max-width:800px; margin:auto;">
    <form id="formUsuario"
          action="../controllers/UsuarioController.php"
          method="POST">
      <!-- Indica acción: 'crear' o 'editar' -->
      <input type="hidden" name="action" id="formAction" value="crear">
      <!-- ID de usuario (para editar) -->
      <input type="hidden" name="id_usuario" id="formIdUsuario" value="">

      <!-- Nombre -->
      <div class="form-group">
        <label for="formNombre">Nombre:</label>
        <input type="text"
               id="formNombre"
               name="nombre"
               class="form-control form-control-sm"
               required>
      </div>

      <!-- Apellido -->
      <div class="form-group">
        <label for="formApellido">Apellido:</label>
        <input type="text"
               id="formApellido"
               name="apellido"
               class="form-control form-control-sm"
               required>
      </div>

      <!-- DNI -->
      <div class="form-group">
        <label for="formDni">DNI:</label>
        <input type="text"
               id="formDni"
               name="dni"
               class="form-control form-control-sm"
               required>
      </div>

      <!-- Email -->
      <div class="form-group">
        <label for="formEmail">Email:</label>
        <input type="email"
               id="formEmail"
               name="email"
               class="form-control form-control-sm"
               required>
      </div>

      <!-- Teléfono -->
      <div class="form-group">
        <label for="formTelefono">Teléfono:</label>
        <input type="text"
               id="formTelefono"
               name="telefono"
               class="form-control form-control-sm">
      </div>

      <!-- Contraseña -->
      <div class="form-group">
        <label for="formPassword">Contraseña:</label>
        <input type="password"
               id="formPassword"
               name="password"
               class="form-control form-control-sm"
               placeholder="Obligatorio al crear, opcional al editar">
      </div>

      <!-- Rol -->
      <div class="form-group">
        <label for="formRol">Rol:</label>
        <select id="formRol"
                name="rol"
                class="form-control form-control-sm"
                required>
          <option value="docente">Docente</option>
          <option value="administrador">Administrador</option>
        </select>
      </div>

      <!-- Isla -->
      <div class="form-group">
        <label for="formIsla">Isla:</label>
        <select id="formIsla"
                name="isla"
                class="form-control form-control-sm">
          <option value="">— Selecciona —</option>
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

      <!-- Disponibilidad -->
      <div class="form-group">
        <label for="formDisponibilidad">Disponibilidad:</label>
        <select id="formDisponibilidad"
                name="disponibilidad"
                class="form-control form-control-sm">
          <option value="1">Disponible</option>
          <option value="0">No Disponible</option>
        </select>
      </div>

      <!-- Puntuación -->
      <div class="form-group">
        <label for="formPuntuacion">Puntuación:</label>
        <input type="number"
               id="formPuntuacion"
               name="puntuacion"
               class="form-control form-control-sm"
               step="0.01">
      </div>

      <!-- Botones Crear/Actualizar y Cancelar -->
      <div class="mt-3 d-flex gap-2">
        <button type="submit"
                id="formSubmit"
                class="btn btn-success">
          Guardar
        </button>
        <button type="button"
                id="formCancel"
                class="btn btn-secondary">
          Cancelar
        </button>
      </div>
    </form>
  </div>

  <!-- ===========================================
       TABLA DE USUARIOS
       =========================================== -->
    <div class="table-responsive w-100 mb-4">
      <table class="table">
        <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Apellido</th>
      <th>DNI/NIE</th>
      <th>Email</th>
      <th>Teléfono</th>
      <th>Rol</th>
      <th>Disponibilidad</th>
      <th>Isla</th>
      <th>Puntuación</th>
      <th>Puesto</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($usuarios as $u): ?>
    <tr data-id="<?= $u['id_usuario'] ?>"
        data-nombre="<?= htmlspecialchars($u['nombre']) ?>"
        data-apellido="<?= htmlspecialchars($u['apellido']) ?>"
        data-dni="<?= htmlspecialchars($u['dni']) ?>"
        data-email="<?= htmlspecialchars($u['email']) ?>"
        data-telefono="<?= htmlspecialchars($u['telefono']) ?>"
        data-rol="<?= $u['rol'] ?>"
        data-disponibilidad="<?= $u['disponibilidad'] ?>"
        data-isla="<?= htmlspecialchars($u['isla']) ?>"
        data-puntuacion="<?= htmlspecialchars($u['puntuacion']) ?>">
      <td><?= $u['id_usuario'] ?></td>
      <td><?= htmlspecialchars($u['nombre']) ?></td>
      <td><?= htmlspecialchars($u['apellido']) ?></td>
      <td><?= htmlspecialchars($u['dni']) ?></td>
      <td><?= htmlspecialchars($u['email']) ?></td>
      <td><?= htmlspecialchars($u['telefono']) ?></td>
      <td><?= htmlspecialchars($u['rol']) ?></td>
      <td><?= $u['disponibilidad'] ? 'Disponible' : 'No Disponible' ?></td>
      <td><?= htmlspecialchars($u['isla']) ?></td>
      <td><?= htmlspecialchars($u['puntuacion']) ?></td>
      <td><?= $usuarioModel->obtenerPuestoEnLista($u['id_usuario']) ?></td>
      <td>
            <!-- EDITAR: botón que abre el formulario con datos -->
            <button type="button"
                    class="btn btn-light border btn-editar">
              <img src="../assets/icons/editar.png"
                   alt="Editar"
                   width="20">
            </button>

            <!-- ELIMINAR: formulario POST con confirm -->
            <form action="../controllers/UsuarioController.php"
                  method="POST"
                  class="d-inline"
                  onsubmit="return confirm('¿Eliminar este usuario?');">
              <input type="hidden" name="action" value="eliminar">
              <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
              <button type="submit" class="btn btn-light border">
                <img src="../assets/icons/eliminar.png"
                     alt="Eliminar"
                     width="20">
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</section>


<!-- ===========================================
     SECCIÓN DE NOTICIAS
     =========================================== -->
<section id="noticias">
  <h3>Noticias</h3>

  <!-- ╔═════════════════════════════════════════╗ -->
  <!-- ║   BOTÓN NUEVA NOTICIA (abre formulario) ║ -->
  <!-- ╚═════════════════════════════════════════╝ -->
  <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
    <button class="btn btn-primary" id="btnNuevaNoticia">
      Nueva Noticia
    </button>
  </div>

  <!-- ╔═════════════════════════════════════════╗ -->
  <!-- ║   FORMULARIO OCULTO CREAR / EDITAR      ║ -->
  <!-- ╚═════════════════════════════════════════╝ -->
  <div id="formularioNoticia"
       class="mt-3"
       style="display:none; max-width:800px; margin:auto;">
    <form id="formNoticia"
          action="../controllers/NoticiaController.php"
          method="POST">
      <input type="hidden" name="action" id="formNoticiaAction" value="crear">
      <input type="hidden" name="id_noticia" id="formIdNoticia" value="">

      <!-- Título -->
      <div class="form-group">
        <label for="formTitulo">Título:</label>
        <input type="text"
               id="formTitulo"
               name="titulo"
               class="form-control form-control-sm"
               required>
      </div>

      <!-- Contenido -->
      <div class="form-group">
        <label for="formContenido">Contenido:</label>
        <textarea id="formContenido"
                  name="contenido"
                  class="form-control form-control-sm"
                  rows="4"
                  required></textarea>
      </div>

      <!-- Ruta de imagen -->
      <div class="form-group">
        <label for="formImagen">Imagen (URL o ruta):</label>
        <input type="text"
               id="formImagen"
               name="imagen_url"
               class="form-control form-control-sm"
               placeholder="e.g. /assets/img/noticia.jpg">
      </div>

      <!-- Botones Guardar y Cancelar -->
      <div class="mt-3 d-flex gap-2">
        <button type="submit"
                id="formSubmitNoticia"
                class="btn btn-success">
          Guardar
        </button>
        <button type="button"
                id="formCancelNoticia"
                class="btn btn-secondary">
          Cancelar
        </button>
      </div>
    </form>
  </div>

<!-- ╔═════════════════════════════════════════╗ -->
<!-- ║         TABLA DE NOTICIAS               ║ -->
<!-- ╚═════════════════════════════════════════╝ -->
<div class="table-responsive w-100 mb-4">
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Contenido</th>
        <th>Imagen</th>
        <th>Fecha Creación</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($noticias as $n): ?>
      <tr
        data-id="<?= $n['id_noticia'] ?>"
        data-titulo="<?= htmlspecialchars($n['titulo']) ?>"
        data-contenido="<?= htmlspecialchars($n['contenido']) ?>"
        data-imagen_url="<?= htmlspecialchars($n['imagen_url']) ?>"
        data-fecha="<?= htmlspecialchars($n['fecha']) ?>" 
      >
        <td><?= $n['id_noticia'] ?></td>
        <td><?= htmlspecialchars($n['titulo']) ?></td>
        <td><?= nl2br(htmlspecialchars($n['contenido'])) ?></td>
        <td>
          <?php if (!empty($n['imagen_url'])): ?>
            <img src="<?= htmlspecialchars($n['imagen_url']) ?>"
                 alt="Imagen noticia"
                 style="max-height:50px;">
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($n['fecha']) ?></td>
        <td>
          <!-- EDITAR NOTICIA -->
          <button type="button"
                  class="btn btn-light border btn-editar-noticia">
            <img src="../assets/icons/editar.png"
                 alt="Editar"
                 width="20">
          </button>
          <!-- ELIMINAR NOTICIA -->
          <form action="../controllers/NoticiaController.php"
                method="POST"
                class="d-inline"
                onsubmit="return confirm('¿Eliminar esta noticia?');">
            <input type="hidden" name="action" value="eliminar">
            <input type="hidden" name="id_noticia" value="<?= $n['id_noticia'] ?>">
            <button type="submit" class="btn btn-light border">
              <img src="../assets/icons/eliminar.png"
                   alt="Eliminar"
                   width="20">
            </button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</section>
   
  <!-- ===========================================
       TABLA DE ADJUDICACIONES
       =========================================== -->
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

        
  <!-- ===========================================
       TABLA DE SOLICITUDES
       =========================================== -->
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
