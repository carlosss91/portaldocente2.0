<?php
session_start();

// Verifica que el usuario haya iniciado sesión y tenga el rol adecuado
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "docente") {
    header("Location: login.php");
    exit();
}

// Determina la página activa para resaltar la opción en la barra lateral
$pagina_activa = basename($_SERVER['PHP_SELF'], ".php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/formacion.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>
    <!-- Header y barra lateral -->
    <?php include 'partials/header.php'; ?>
    <?php include 'partials/sidebar.php'; ?>
    
    <!-- Contenido -->
    <main class="content">
        <h2 class="page-title">Formación</h2>
        
        <div class="formacion-container">
            <!-- Tarjeta de formación -->
            <div class="card">
                <img src="../assets/img/formacion/ia.gif" alt="Formación 1" class="card-img">
                <div class="card-body">
                    <h5 class="card-title">Curso de Inteligencia Artificial</h5>
                    <p class="card-text">Descubre todas las tendencias y novedades de la IA para aplicarlas en el entorno educativo</p>
                </div>
            </div>

            <div class="card">
                <img src="../assets/img/formacion/web.gif" alt="Formación 2" class="card-img">
                <div class="card-body">
                    <h5 class="card-title">Taller de Desarrollo Web y Moodle</h5>
                    <p class="card-text">Aprende las últimas tendencias en desarrollo web y moodle </p>
                </div>
            </div>

            <div class="card">
                <img src="../assets/img/formacion/seguridad.gif" alt="Formación 3" class="card-img">
                <div class="card-body">
                    <h5 class="card-title">Formación en Seguridad Informática</h5>
                    <p class="card-text">Mejora tus habilidades en ciberseguridad con este curso intensivo para docentes</p>
                </div>
            </div>
        </div>
    </main>
      <!-- Pie de página -->
    <?php include 'partials/footer.php'; ?>

</body>
</html>
