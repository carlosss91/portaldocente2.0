<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Portal Docente</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="login-container">

    <!-- Logo del Gobierno de Canarias (Pequeño y arriba a la izquierda) -->
    <img src="../assets/img/logo_canarias.png" alt="Gobierno de Canarias" class="canarias-logo">

    <div class="login-box">
        <!-- Logo de Portal Docente 2.0 -->
        <img src="../assets/img/logo_portaldocente.png" alt="Portal Docente 2.0" class="portal-logo">

        <h2>Portal Docente</h2>

        <!-- Formulario de inicio de sesión -->
        <form action="../controllers/login.php" method="POST">
            <!-- Campo Usuario -->
            <div class="form-group">
                <input class="form-control" type="email" name="email" placeholder="Correo electrónico" required>
            </div>
            <!-- Campo Contraseña -->
            <div class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Contraseña" required>
            </div>

            <!-- Casilla "Recuérdame" -->
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Recuérdame</label>
            </div>

            <!-- Botón de inicio de sesión -->
            <button class="btn btn-primary" type="submit">Iniciar Sesión</button>

            <!-- Enlace para recuperar contraseña -->
            <div class="forgot-password">
                <a href="#">¿Has olvidado tu contraseña?</a>
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


