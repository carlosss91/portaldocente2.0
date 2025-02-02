<?php
session_start();
// Si el usuario ya ha iniciado sesión, lo redirige al index.php para evitar que vuelva a la pantalla de login
if (isset($_SESSION["usuario"])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../assets/css/styles.css"> <!-- Enlace al archivo de estilos -->
</head>
<body>
    <div class="login-box">
        <h2>Portal Docente</h2>

        <!-- Formulario de inicio de sesión -->
        <form action="../controllers/login.php" method="POST">
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>

        <!-- Mostrar mensaje de error si la autenticación falla -->
        <?php
        if (isset($_GET["error"])) {
            echo "<p class='error'>" . htmlspecialchars($_GET["error"]) . "</p>";
        }
        ?>
    </div>
</body>
</html>
