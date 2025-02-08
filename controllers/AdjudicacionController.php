<?php
require_once '../models/Adjudicacion.php';
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: ../views/login.php");
    exit();
}

$adjudicacion = new Adjudicacion();
$id_usuario = $_SESSION["id_usuario"];

// Verificar si se envió el formulario de adjudicación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["destino_actual"])) {
        $destino_actual = $_POST["destino_actual"];
        $adjudicacion->agregarAdjudicacion($id_usuario, $destino_actual);
    } elseif (isset($_POST["eliminar_adjudicacion"])) {
        $id_adjudicacion = $_POST["eliminar_adjudicacion"];
        $adjudicacion->eliminarAdjudicacion($id_adjudicacion);
    }

    header("Location: ../views/adjudicaciones.php");
    exit();
}
