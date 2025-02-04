<?php
session_start();
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión

// Redirige al login
header("Location: http://localhost/portaldocente2.0/views/login.php");
exit();
?>
