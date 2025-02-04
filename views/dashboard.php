<?php
session_start();
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "docente") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Docente</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

    <h1>Bienvenido al Panel Docente</h1>
    <p>Usuario: <?php echo $_SESSION["usuario"]; ?></p>

    <nav>
        <ul>
            <li><a href="noticias.php">Ver Noticias</a></li>
            <li><a href="adjudicaciones.php">Mis Adjudicaciones</a></li>
            <li><a href="solicitudes.php">Mis Solicitudes</a></li>
            <li><a href="../controllers/logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>

</body>
</html>
