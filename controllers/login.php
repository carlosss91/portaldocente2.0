<?php
session_start();
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Consulta para obtener el usuario
    $sql = "SELECT * FROM usuario WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        // Verificar contraseña (ajustar si usas hash en la BD)
        if ($password === $usuario["password"]) { // Cambiar a password_verify($password, $usuario["password"]) si está hasheada
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
