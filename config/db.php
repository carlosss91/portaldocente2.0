<?php
$host = "localhost";  // MySQL Server de Workbench
$user = "root";       // Usuario de MySQL
$pass = "root";           // Contraseña (déjala vacía si no configuraste una)
$dbname = "portal_docente"; // Nombre de la base de datos

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>