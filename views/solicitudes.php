<?php
session_start();
require_once '../models/Solicitud.php';

// Verifica que el usuario haya iniciado sesión y tenga el rol adecuado
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "docente") {
    header("Location: login.php"); // Redirige al login si el usuario no está autenticado
    exit();
}

// Verifica si el ID del usuario está definido en la sesión
if (!isset($_SESSION["id_usuario"])) {
    die("Error: ID de usuario no definido en la sesión.");
}

// Crea una instancia del modelo de solicitudes
$solicitudModel = new Solicitud();

// Obtiene las solicitudes del usuario autenticado
$solicitudes = $solicitudModel->obtenerSolicitudes($_SESSION["id_usuario"]);

// Asegurar que $solicitudes sea un array antes de recorrerlo
if (!is_array($solicitudes)) {
    $solicitudes = []; // Si la consulta falla, se asigna un array vacío para evitar errores en el bucle
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/solicitudes.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>
    <!-- Header y barra lateral -->
    <?php include 'partials/header.php'; ?>
    <?php include 'partials/sidebar.php'; ?>

    <!-- Contenido -->
    <main class="content">
        <h2 class="page-title">Solicitudes</h2>
        <button id="mostrarFormulario" class="btn btn-primary" onclick="toggleFormularioSolicitudes()">Nueva Solicitud</button>
        
        <div id="formularioSolicitud" style="display:none;">
            <form action="../controllers/SolicitudController.php" method="POST">
                <label for="tipo_solicitud">Tipo de solicitud:</label>
                <select name="tipo_solicitud" class="form-select" required>
                    <option value="">Seleccione el tipo</option>
                    <option value="cambio de destino">Cambio de destino</option>
                    <option value="nueva adjudicación">Nueva adjudicación</option>
                </select>
                
                <label for="detalles_destino_solicitado">Detalles del destino solicitado:</label>
                <textarea name="detalles_destino_solicitado" class="form-control" required></textarea>
                
                <button type="submit" class="btn btn-success">Enviar Solicitud</button>
            </form>
        </div>

        <!-- Tabla de solicitudes -->
        <table class="table">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Estado</th>
            <th>Detalles</th>
            <th>Fecha</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($solicitudes as $solicitud): ?>
        <tr data-id="<?= $solicitud['id_solicitud'] ?>">
            <td><?= htmlspecialchars($solicitud["tipo_solicitud"] ?? 'No disponible') ?></td>
            <td><?= htmlspecialchars($solicitud["estado_solicitud"] ?? 'No disponible') ?></td>
            <td><?= htmlspecialchars($solicitud["detalles_destino_solicitado"] ?? 'No disponible') ?></td>
            <td><?= htmlspecialchars($solicitud["fecha_solicitud"] ?? 'No disponible') ?></td>
            <td>
                <button class="btn btn-light border" onclick="eliminarSolicitud(this)" title="Eliminar">
                    <img src="../assets/icons/eliminar.png" alt="Eliminar" width="20">
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    </main>
      <!-- Pie de página -->
    <?php include 'partials/footer.php'; ?>
</body>
</html>
