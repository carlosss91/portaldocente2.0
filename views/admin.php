<?php
session_start();
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "administrador") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

    <h1>Bienvenido al Panel de Administración</h1>
    <p>Usuario: <?php echo $_SESSION["usuario"]; ?></p>

    <nav>
        <ul>
            <li><a href="noticias.php">Gestión de Noticias</a></li>
            <li><a href="adjudicaciones.php">Gestión de Adjudicaciones</a></li>
            <li><a href="solicitudes.php">Gestión de Solicitudes</a></li>
            <li><a href="../controllers/logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>

</body>
</html>
