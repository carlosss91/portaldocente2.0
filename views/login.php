<?php
session_start();
// Si el usuario ya inició sesión, redirigirlo al index
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
    <title>Iniciar Sesión - Portal Docente</title>
    <link rel="stylesheet" href="../assets/css/styles.css"> <!-- Enlace al CSS -->
</head>
<body>

    <!-- Logo del Gobierno de Canarias (Arriba a la izquierda) -->
    <img src="../assets/img/logo_canarias.png" alt="Gobierno de Canarias" class="canarias-logo">

    <div class="login-box">
        <!-- Logo de Portal Docente 2.0 (Encima del título) -->
        <img src="../assets/img/logo_portaldocente.png" alt="Portal Docente 2.0" class="portal-logo">

        <h2>Portal Docente</h2>

        <!-- Formulario de inicio de sesión -->
        <form action="../controllers/login.php" method="POST">
            <div class="input-group">
               
                <input class="form-control" type="email" name="email" id="email" placeholder="Correo electrónico" required>
            </div>

            <div class="input-group">
               
                <input class="form-control" type="password" name="password" id="password" placeholder="Contraseña" required>
            </div>

            <!-- Opción de "Recuérdame" -->
            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Recuérdame</label>
            </div>

            <button class="btn btn-primary" type="submit">Iniciar Sesión</button>

            <!-- Enlace para recuperar contraseña -->
            <div class="forgot-password">
                <a href="#">¿Olvidaste tu contraseña?</a>
            </div>
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
