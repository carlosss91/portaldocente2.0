<?php
session_start();
// Asegurar que el usuario está autenticado y es docente
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "docente") {
    header("Location: login.php");
    exit();
}

// Detectar la página activa para resaltar en el menú
$pagina_activa = basename($_SERVER['PHP_SELF'], ".php");

// Incluir el controlador y obtener noticias
require_once __DIR__ . '/../controllers/NoticiaController.php';
$noticiaController = new NoticiaController();
$noticias = $noticiaController->mostrarNoticias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias</title>
    <!-- Librerías CSS y JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/noticias.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>

    <!-- ╔═════════════════════════════════════════╗ -->
    <!-- ║   Cabecera y menú lateral              ║ -->
    <!-- ╚═════════════════════════════════════════╝ -->
    <?php include __DIR__ . '/partials/header.php'; ?>
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

    <!-- ╔═════════════════════════════════════════╗ -->
    <!-- ║        Contenido principal              ║ -->
    <!-- ╚═════════════════════════════════════════╝ -->
    <main class="content">
        <h2 class="page-title">Noticias</h2>

        <?php if (!empty($noticias)): ?>
        <div class="news-list">
            <?php foreach ($noticias as $noticia): ?>
            <div class="news-container">
                <div class="news-card" id="news-card-<?= $noticia['id_noticia'] ?>" 
                     onclick="toggleNews(<?= $noticia['id_noticia'] ?>)">
                    <!-- Imagen y título -->
                    <div class="news-image-container">
                        <img src="<?= htmlspecialchars($noticia['imagen_url']) ?>"
                             alt="Noticia" class="news-card-img">
                        <div class="news-title"><?= htmlspecialchars($noticia['titulo']) ?></div>
                    </div>
                    <!-- Contenido oculto inicialmente -->
                    <div class="news-content" id="news-<?= $noticia['id_noticia'] ?>">
                        <p><?= nl2br(htmlspecialchars($noticia['contenido'])) ?></p>
                        <small>
                            <strong>Autor:</strong> <?= htmlspecialchars($noticia['autor']) ?> |
                            <strong>Fecha:</strong> <?= date("d/m/Y", strtotime($noticia['fecha'])) ?>
                        </small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p>No hay noticias disponibles.</p>
        <?php endif; ?>
    </main>

    <!-- ╔═════════════════════════════════════════╗ -->
    <!-- ║         Pie de página                  ║ -->
    <!-- ╚═════════════════════════════════════════╝ -->
   <?php include __DIR__ . '/partials/footer.php'; ?>

    <!-- Script para toggle de noticias (redundante si ya existe en script.js) -->
    <script>
        function toggleNews(id) {
            const content = document.getElementById("news-" + id);
            content.classList.toggle("show-news");
        }
    </script>

</body>
</html>
