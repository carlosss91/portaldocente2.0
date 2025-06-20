<?php
session_start();
require_once '../models/Usuario.php';

// Verificar que el usuario haya iniciado sesión
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

// Obtener la información del usuario
$usuarioModel = new Usuario();
$id_usuario = $_SESSION["id_usuario"];
$usuario = $usuarioModel->obtenerUsuarioPorId($id_usuario);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/usuario.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>
    <!-- ╔═════════════════════════════════════════╗ -->
    <!-- ║         Cabecera común                  ║ -->
    <!-- ╚═════════════════════════════════════════╝ -->
    <?php include __DIR__ . '/partials/header.php'; ?>
    <?php include __DIR__ . '/partials/sidebar.php'; ?>

   

    <!-- ╔═════════════════════════════════════════╗ -->
    <!-- ║         Contenido.                      ║ -->
    <!-- ╚═════════════════════════════════════════╝ -->
    <main class="content">
        <h2 class="page-title">Mi Perfil</h2>
        <!-- Mostrar mensaje de error si las contraseñas no coinciden -->
        <?php if (isset($_GET['error']) && $_GET['error'] === 'pass_no_coincide'): ?>
            <div class="alert alert-danger text-center mb-3">
                Error: Las contraseñas no coinciden.
            </div>
        <?php endif; ?>
        <!--Formulario de perfil-->
        <div class="perfil-container">
            <form action="../controllers/UsuarioController.php" method="POST">
                <!-- Indica al controlador que estamos editando -->
                <input type="hidden" name="action" value="editar">
                <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                <input type="hidden" name="dni" value="<?= htmlspecialchars($usuario['dni']) ?>">
                <input type="hidden" name="rol" value="<?= htmlspecialchars($usuario['rol']) ?>">
                <input type="hidden" name="puntuacion" value="<?= htmlspecialchars($usuario['puntuacion']) ?>">


                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

                <label for="apellido">Apellido:</label>
                <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>

                <label for="email">Correo electrónico:</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>

                <label for="telefono">Teléfono:</label>
                <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($usuario['telefono']) ?>">

                <label for="disponibilidad">Disponibilidad:</label>
                <select name="disponibilidad" class="form-select">
                    <option value="1" <?= $usuario['disponibilidad'] ? 'selected' : '' ?>>Disponible</option>
                    <option value="0" <?= !$usuario['disponibilidad'] ? 'selected' : '' ?>>No Disponible</option>
                </select>

                <label for="isla">Isla:</label>
                <select name="isla" class="form-select">
                    <option value="Tenerife" <?= $usuario['isla'] === 'Tenerife' ? 'selected' : '' ?>>Tenerife</option>
                    <option value="Gran Canaria" <?= $usuario['isla'] === 'Gran Canaria' ? 'selected' : '' ?>>Gran Canaria</option>
                    <option value="Lanzarote" <?= $usuario['isla'] === 'Lanzarote' ? 'selected' : '' ?>>Lanzarote</option>
                    <option value="Fuerteventura" <?= $usuario['isla'] === 'Fuerteventura' ? 'selected' : '' ?>>Fuerteventura</option>
                    <option value="La Palma" <?= $usuario['isla'] === 'La Palma' ? 'selected' : '' ?>>La Palma</option>
                    <option value="La Gomera" <?= $usuario['isla'] === 'La Gomera' ? 'selected' : '' ?>>La Gomera</option>
                    <option value="El Hierro" <?= $usuario['isla'] === 'El Hierro' ? 'selected' : '' ?>>El Hierro</option>
                    <option value="La Graciosa" <?= $usuario['isla'] === 'La Graciosa' ? 'selected' : '' ?>>La Graciosa</option>
                </select>


                <!-- Input para ingresar una nueva contraseña (opcional) -->
                <label for="password">Nueva Contraseña (Opcional):</label>
                <div class="password-container mb-3 d-flex align-items-center">
                    <input type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        autocomplete="new-password">
                    <button type="button"
                            class="btn btn-light border toggle-password-btn ms-2"
                            onclick="togglePassword('password','eye-icon-new')">
                        <img src="../assets/icons/eye_closed.png"
                            alt="Mostrar/Ocultar"
                            id="eye-icon-new"
                            width="20" height="20"
                            autocomplete="new-password">
                    </button>
                </div>

                <!-- Confirmar nueva contraseña -->
                <label for="password_confirm">Confirmar Contraseña:</label>
                <div class="password-container mb-4 d-flex align-items-center">
                    <input type="password"
                        name="password_confirm"
                        id="password_confirm"
                        class="form-control">
                    <button type="button"
                            class="btn btn-light border toggle-password-btn ms-2"
                            onclick="togglePassword('password_confirm','eye-icon-confirm')">
                        <img src="../assets/icons/eye_closed.png"
                            alt="Mostrar/Ocultar"
                            id="eye-icon-confirm"
                            width="20" height="20">
                    </button>
                </div>

                <label for="puntuacion">Puntuación:</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($usuario['puntuacion']) ?>" disabled>

                <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </form>
        </div>
    </main>
    <!-- ╔═════════════════════════════════════════╗ -->
    <!-- ║         Pie de página                   ║ -->
    <!-- ╚═════════════════════════════════════════╝ -->
    <?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
