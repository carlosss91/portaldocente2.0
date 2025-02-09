<?php
require_once '../config/db.php'; // Incluir la conexión a la base de datos
require_once '../models/Solicitud.php'; // Importar el modelo de Solicitud
session_start();

// Verificar que el usuario esté autenticado antes de continuar
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../views/login.php"); // Redirigir si no hay sesión activa
    exit();
}

$solicitud = new Solicitud(); // Crear una instancia del modelo
$id_usuario = $_SESSION["id_usuario"]; // Obtener el ID del usuario autenticado

// Verificar si se envió el formulario de solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se está enviando una nueva solicitud
    if (isset($_POST["tipo_solicitud"]) && isset($_POST["detalles_destino_solicitado"])) {
        $tipo_solicitud = trim($_POST["tipo_solicitud"]); // Limpiar la entrada de tipo de solicitud
        $detalles_destino = trim($_POST["detalles_destino_solicitado"]); // Limpiar los detalles

        // Insertar en la base de datos solo si los valores son válidos
        if (!empty($tipo_solicitud) && !empty($detalles_destino)) {
            $solicitud->agregarSolicitud($id_usuario, $tipo_solicitud, $detalles_destino);
        }
    }
    
    // Verificar si se ha enviado una solicitud para eliminar una solicitud existente
    if (isset($_POST["eliminar_solicitud"])) {
        $id_solicitud = $_POST["eliminar_solicitud"];

        // Solo eliminar si el ID es válido
        if (!empty($id_solicitud)) {
            $solicitud->eliminarSolicitud($id_solicitud);
        }
    }
    
    header("Location: ../views/solicitudes.php?success=1"); // Redirigir tras completar la acción
    exit();
}
?>
