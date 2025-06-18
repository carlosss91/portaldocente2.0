<?php
session_start();

// Si el usuario ya ha iniciado sesión, redirigir al dashboard correspondiente
if (isset($_SESSION["usuario"])) {
    if ($_SESSION["rol"] === "administrador") {
        header("Location: views/admin.php");
    } else {
        header("Location: views/dashboard.php");
    }
    exit();
}

// Si no hay sesión, redirigir al login
header("Location: views/login.php");
exit();
?>
