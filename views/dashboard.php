<?php include '../includes/init.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Docente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>
       <!-- Header y barra lateral -->
    <?php include 'partials/header.php'; ?>
    <?php include 'partials/sidebar.php'; ?>

    <!-- Contenido dinámico -->
    <main class="content">
        <h2 class="welcome-text">¡Bienvenid@, <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?>!</h2>
        
        <!-- Tabla con la información del usuario -->
        <table class="table">
            <thead>
                <tr>
                    <th>Disponibilidad</th>
                    <th>Isla</th>
                    <th>Puntuación</th>
                    <th>Puesto en Lista</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $usuario['disponibilidad'] ? 'Disponible' : 'No Disponible'; ?></td>
                    <td><?= htmlspecialchars($usuario['isla']); ?></td>
                    <td><?= htmlspecialchars($usuario['puntuacion']); ?></td>
                    <td><?= $puesto_lista; ?></td>
                </tr>
            </tbody>
        </table>
    </main>

    <!-- Pie de página -->
  <?php include 'partials/footer.php'; ?>
</body>
</html>
