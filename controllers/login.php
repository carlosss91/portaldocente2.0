<?php
// ╔═════════════════════════════════════════╗
// ║      CONTROLADOR DE LOGIN (controllers/login.php)   ║
// ╚═════════════════════════════════════════╝

// Mostrar errores para depuración (retirar en producción)
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start(); // Inicia la sesión para almacenar datos del usuario

// Ruta absoluta al archivo de configuración de BD
require_once __DIR__ . '/../config/db.php';

// ╔═════════════════════════════════════════╗
// ║     PROCESO DE AUTENTICACIÓN POST       ║
// ╚═════════════════════════════════════════╝
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ╔════════════════════════╗
    // ║   RECIBIR CREDENCIALES ║
    // ╚════════════════════════╝
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    // ╔════════════════════════╗
    // ║   CONSULTA DE USUARIO  ║
    // ╚════════════════════════╝
    $pdo  = Database::conectar();
    $sql  = 'SELECT id_usuario, email, password, rol FROM usuario WHERE email = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // ╔════════════════════════╗
    // ║   VERIFICAR EXISTENCIA ║
    // ╚════════════════════════╝
    if (!$usuario) {
        header('Location: ../views/login.php?error=Usuario no encontrado');
        exit();
    }

    // ╔════════════════════════╗
    // ║   VERIFICAR CONTRASEÑA ║
    // ╚════════════════════════╝
    $hash = $usuario['password'];
    $ok   = false;

    // Comprobación con hash
    if (password_verify($password, $hash)) {
        $ok = true;
    }
    // Fallback para contraseñas en texto plano
    elseif ($password === $hash) {
        $ok = true;
        // Re-hasear y actualizar
        $update = $pdo->prepare('UPDATE usuario SET password = ? WHERE id_usuario = ?');
        $update->execute([
            password_hash($password, PASSWORD_DEFAULT),
            $usuario['id_usuario']
        ]);
    }

    if (! $ok) {
        header('Location: ../views/login.php?error=Contraseña incorrecta');
        exit();
    }

    // ╔════════════════════════╗
    // ║   INICIAR SESIÓN       ║
    // ╚════════════════════════╝
    $_SESSION['id_usuario'] = $usuario['id_usuario'];
    $_SESSION['usuario']    = $usuario['email'];
    $_SESSION['rol']        = $usuario['rol'];

    // ╔════════════════════════╗
    // ║   REDIRECCIÓN POR ROL  ║
    // ╚════════════════════════╝
    if ($usuario['rol'] === 'administrador') {
        header('Location: ../views/admin.php');
    } else {
        header('Location: ../views/dashboard.php');
    }
    exit();
} else {
    // Si no es POST, redirigir al login
    header('Location: ../views/login.php');
    exit();
}
?>
