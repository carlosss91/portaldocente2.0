<?php
require_once '../config/db.php'; // Asegurar que se incluye la conexión
require_once '../models/Adjudicacion.php';
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

$adjudicacion = new Adjudicacion();
$id_usuario = $_SESSION["id_usuario"];

// Verificar si se envió el formulario de adjudicación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["isla"]) && isset($_POST["municipio"])) {
        $isla = trim($_POST["isla"]);
        $municipio = trim($_POST["municipio"]);

        // Solo insertar si se seleccionaron valores válidos
        if (!empty($isla) && !empty($municipio)) {
            $adjudicacion->agregarAdjudicacion($id_usuario, $isla, $municipio);
        }
    }
    
    // Verificar si se ha enviado una solicitud para eliminar una adjudicación
    if (isset($_POST["eliminar_adjudicacion"])) {
        $id_adjudicacion = $_POST["eliminar_adjudicacion"];

        if (!empty($id_adjudicacion)) {
            $adjudicacion->eliminarAdjudicacion($id_adjudicacion);
        }
    }
    
    header("Location: ../views/adjudicaciones.php?success=1");
    exit();
}
?>
