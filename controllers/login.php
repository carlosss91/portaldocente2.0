<?php
session_start(); // Inicia la sesión para poder almacenar datos del usuario autenticado

require_once "../config/db.php"; // Incluye la conexión a la base de datos

// Verifica que la solicitud sea de tipo POST (es decir, que proviene de un formulario)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]); // Obtiene el email del formulario y elimina espacios en blanco
    $password = trim($_POST["password"]); // Obtiene la contraseña del formulario y elimina espacios en blanco

    // Conectar a la base de datos
    $pdo = Database::conectar(); 

    // Prepara la consulta SQL para obtener el usuario por su email
    $sql = "SELECT * FROM usuario WHERE email = ?";
    $stmt = $pdo->prepare($sql); 
    $stmt->execute([$email]); // Ejecuta la consulta con el email proporcionado
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC); // Obtiene el resultado en un array asociativo

    // Verifica si el usuario existe en la base de datos
    if ($usuario) {
        // Verifica si la contraseña ingresada coincide con la almacenada en la base de datos
        // Si la contraseña está hasheada en la base de datos, usa password_verify()
        if ($password === $usuario["password"]) { 

            // Almacena los datos relevantes en la sesión
            $_SESSION["id_usuario"] = $usuario["id_usuario"]; // Guarda el ID del usuario
            $_SESSION["usuario"] = $usuario["email"]; // Guarda el email del usuario
            $_SESSION["rol"] = $usuario["rol"]; // Guarda el rol del usuario (ej. docente, administrador)

            // Redirige al usuario según su rol
            if ($usuario["rol"] === "administrador") {
                header("Location: ../views/admin.php");
            } else {
                header("Location: ../views/dashboard.php");
            }

            exit(); // Termina la ejecución del script tras la redirección
        } else {
            // Si la contraseña es incorrecta, redirige de nuevo al login con un mensaje de error
            header("Location: ../views/login.php?error=Contraseña incorrecta");
            exit();
        }
    } else {
        // Si el usuario no existe, redirige al login con un mensaje de error
        header("Location: ../views/login.php?error=Usuario no encontrado");
        exit();
    }
}
?>
