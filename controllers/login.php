<?php
session_start(); // Inicia la sesión para manejar la autenticación del usuario

require_once "../config/db.php"; // Incluye la conexión a la base de datos

// Verifica si el formulario ha sido enviado por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]); // Obtiene el email y elimina espacios en blanco
    $password = trim($_POST["password"]); // Obtiene la contraseña y elimina espacios en blanco

    // Prepara la consulta para buscar el usuario en la base de datos
    $stmt = $conn->prepare("SELECT id_usuario, nombre, rol, password FROM usuario WHERE email = ?");
    $stmt->bind_param("s", $email); // Asigna el email a la consulta
    $stmt->execute(); // Ejecuta la consulta
    $result = $stmt->get_result(); // Obtiene los resultados

    // Verifica si el usuario existe en la base de datos
    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc(); // Obtiene los datos del usuario

        // Verifica si la contraseña ingresada coincide con la almacenada (ajustar si se usa hash)
        if ($password === $usuario["password"]) { 
            // Guarda los datos del usuario en la sesión
            $_SESSION["usuario"] = $usuario["nombre"];
            $_SESSION["rol"] = $usuario["rol"];
            
            // Redirige al dashboard correspondiente según el rol
            if ($usuario["rol"] === "administrador") {
                header("Location: ../views/admin.php");
            } else {
                header("Location: ../views/dashboard.php");
            }
            exit();
        } else {
            // Si la contraseña es incorrecta, redirige al login con un mensaje de error
            header("Location: ../views/login.php?error=Contraseña incorrecta");
            exit();
        }
    } else {
        // Si el usuario no existe, redirige al login con un mensaje de error
        header("Location: ../views/login.php?error=Usuario no encontrado");
        exit();
    }
} else {
    // Si se intenta acceder directamente sin enviar el formulario, redirige al login
    header("Location: ../views/login.php");
    exit();
}
?>
