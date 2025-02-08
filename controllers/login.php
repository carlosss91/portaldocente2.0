<?php
session_start();
require_once "../config/db.php"; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // 🔹 Establecer la conexión correctamente
    $pdo = Database::conectar(); 

    // Consulta para obtener el usuario
    $sql = "SELECT * FROM usuario WHERE email = ?";
    $stmt = $pdo->prepare($sql); // ✅ Usamos $pdo en vez de $conn
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // 🔹 Verificar la contraseña (Si está hasheada, usa password_verify)
        if ($password === $usuario["password"]) { // ⚠️ Si usas hash, cambia esto a `password_verify($password, $usuario["password"])`
            // Guardar sesión del usuario
            $_SESSION["usuario"] = $usuario["email"];
            $_SESSION["rol"] = $usuario["rol"];

            // Redirección según el rol
            if ($usuario["rol"] === "administrador") {
                header("Location: http://localhost/portaldocente2.0/views/admin.php");
                exit();
            } else {
                header("Location: http://localhost/portaldocente2.0/views/dashboard.php");
                exit();
            }
        } else {
            header("Location: ../views/login.php?error=Contraseña incorrecta");
            exit();
        }
    } else {
        header("Location: ../views/login.php?error=Usuario no encontrado");
        exit();
    }
}
?>
