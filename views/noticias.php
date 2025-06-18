<?php
session_start();
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "docente") {
    header("Location: login.php");
    exit();
}

// Detectar la página activa
$pagina_activa = basename($_SERVER['PHP_SELF'], ".php");

// Incluir el controlador de noticias
require_once '../controllers/NoticiaController.php';

// Obtener las noticias desde el controlador
$noticias = $noticiaController->mostrarNoticias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/noticias.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>

    <!-- Header y barra lateral -->
    <?php include 'partials/header.php'; ?>
    <?php include 'partials/sidebar.php'; ?>

<!-- Contenido de noticias -->
<main class="content">
    <h2 class="page-title">Noticias</h2>

   <?php if (!empty($noticias)): ?>
    <div class="news-list">
        <?php foreach ($noticias as $noticia): ?>
            <div class="news-container">
                <div class="news-card" id="news-card-<?php echo $noticia['id_noticia']; ?>" onclick="toggleNews(<?php echo $noticia['id_noticia']; ?>)">
                    <!-- 🔹 Contenedor de imagen -->
                    <div class="news-image-container">
                        <img src="<?php echo htmlspecialchars($noticia['imagen_url']); ?>" alt="Noticia" class="news-card-img">
                        <div class="news-title"><?php echo htmlspecialchars($noticia['titulo']); ?></div>
                    </div>
                    
                    <!-- 🔹 Contenido desplegable de la noticia -->
                    <div class="news-content" id="news-<?php echo $noticia['id_noticia']; ?>">
                        <p><?php echo nl2br(htmlspecialchars($noticia['contenido'])); ?></p>
                        <small>
                            <strong>Autor:</strong> <?php echo htmlspecialchars($noticia['autor']); ?> |
                            <strong>Fecha:</strong> <?php echo date("d/m/Y", strtotime($noticia['fecha'])); ?>
                        </small>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay noticias disponibles.</p>
    <?php endif; ?>
    
</main>


    <!-- Pie de página -->
   <?php include 'partials/footer.php'; ?>

    <script>
        function toggleNews(id) {
            let content = document.getElementById("news-" + id);
            content.classList.toggle("show-news");
        }
    </script>

</body>
</html>
