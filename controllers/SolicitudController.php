<?php
require_once '../config/db.php'; // Asegurar que se incluye la conexión
require_once '../models/Solicitud.php';
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

$solicitud = new Solicitud();
$id_usuario = $_SESSION["id_usuario"];

// Verificar si se envió el formulario de solicitud
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["tipo_solicitud"]) && isset($_POST["detalles_destino_solicitado"])) {
        $tipo_solicitud = trim($_POST["tipo_solicitud"]);
        $detalles_destino = trim($_POST["detalles_destino_solicitado"]);

        // Solo insertar si los valores son válidos
        if (!empty($tipo_solicitud) && !empty($detalles_destino)) {
            $solicitud->agregarSolicitud($id_usuario, $tipo_solicitud, $detalles_destino);
        }
    }
    
    // Verificar si se ha enviado una solicitud para eliminar una solicitud existente
    if (isset($_POST["eliminar_solicitud"])) {
        $id_solicitud = $_POST["eliminar_solicitud"];

        if (!empty($id_solicitud)) {
            $solicitud->eliminarSolicitud($id_solicitud);
        }
    }
    
    header("Location: ../views/solicitudes.php?success=1");
    exit();
}
?>
