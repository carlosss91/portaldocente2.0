<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Portal Docente</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body class="login-container">

    <!-- ╔═════════════════════════════════════════╗ -->
    <!-- ║      LOGOTIPO CABECERA                  ║ -->
    <!-- ╚═════════════════════════════════════════╝ -->
    <img src="../assets/img/logo_canarias.png" alt="Gobierno de Canarias" class="canarias-logo">

    <!-- ╔═════════════════════════════════════════╗ -->
    <!-- ║      CAJA DE LOGIN                      ║ -->
    <!-- ╚═════════════════════════════════════════╝ -->
    <div class="login-box">
        <img src="../assets/img/logo_portaldocente.png" alt="Portal Docente 2.0" class="portal-logo">
        <h2>Portal Docente</h2>

        <!-- ╔═════════════════════════════════════════╗ -->
        <!-- ║    FORMULARIO DE INICIO DE SESIÓN       ║ -->
        <!-- ╚═════════════════════════════════════════╝ -->
        <form action="../controllers/login.php" method="POST">
            <!-- Email -->
            <div class="form-group">
                <input class="form-control" type="email" name="email" placeholder="Correo electrónico" required>
            </div>
            <!-- Contraseña -->
            <div class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Contraseña" required>
            </div>

            <!-- ╔══════════════════════════╗ -->
            <!-- ║    RECUÉRDAME OPCIONAL    ║ -->
            <!-- ╚══════════════════════════╝ -->
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Recuérdame</label>
            </div>

            <!-- Botón enviar -->
            <button class="btn btn-primary" type="submit">Iniciar Sesión</button>

            <!-- Olvidé contraseña -->
            <div class="forgot-password">
                <a href="#">¿Has olvidado tu contraseña?</a>
            </div>

            <!-- Acceso Cl@ve -->
            <div class="clave-container">
                <button type="button" class="clave-btn" onclick="mostrarMensajeClave()">
                    <img src="../assets/icons/clave.png" alt="Acceder con Cl@ve">
                </button>
            </div>
        </form>

        <!-- ╔═════════════════════════════════════════╗ -->
        <!-- ║      MENSAJE DE ERROR OPCIONAL          ║ -->
        <!-- ╚═════════════════════════════════════════╝ -->
        <?php if (isset($_GET['error'])): ?>
            <p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>
    </div>

    <!-- ╔═════════════════════════════════════════╗ -->
    <!-- ║      PIE DE PÁGINA INCLUIDO              ║ -->
    <!-- ╚═════════════════════════════════════════╝ -->
    <?php include 'partials/footer.php'; ?>

</body>
</html>
