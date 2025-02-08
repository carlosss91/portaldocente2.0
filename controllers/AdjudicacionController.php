<?php
require_once '../config/db.php'; // Asegura que se conecta a la base de datos
require_once '../models/Adjudicacion.php';
session_start();

// Verifica que el usuario esté autenticado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

$adjudicacion = new Adjudicacion(Database::conectar()); // Pasa la conexión al modelo
$id_usuario = $_SESSION["id_usuario"];

// Verificar si se envió el formulario de adjudicación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["adjudicaciones"]) && is_array($_POST["adjudicaciones"])) {
        foreach ($_POST["adjudicaciones"] as $adjudicacionData) {
            $isla = isset($adjudicacionData["isla"]) ? trim($adjudicacionData["isla"]) : "";
            $municipio = isset($adjudicacionData["municipio"]) ? trim($adjudicacionData["municipio"]) : "";

            // Solo inserta si se seleccionaron valores válidos
            if (!empty($isla) && !empty($municipio)) {
                $adjudicacion->agregarAdjudicacion($id_usuario, $isla, $municipio);
            }
        }
    } elseif (isset($_POST["eliminar_adjudicacion"])) {
        $id_adjudicacion = $_POST["eliminar_adjudicacion"];
        $adjudicacion->eliminarAdjudicacion($id_adjudicacion);
    }

    header("Location: ../views/adjudicaciones.php");
    exit();
}
?>
