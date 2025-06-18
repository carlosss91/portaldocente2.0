<?php
session_start();
require_once '../models/Usuario.php';

// Verificar que el usuario haya iniciado sesión y tenga el rol adecuado
if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] !== "docente") {
    header("Location: login.php"); // Redirige al login si el usuario no está autenticado
    exit();
}

// Obtener la información del usuario
$usuarioModel = new Usuario();
$id_usuario = $_SESSION["id_usuario"];
$usuario = $usuarioModel->obtenerUsuarioPorId($id_usuario);

// Obtener el puesto en la lista basado en la puntuación
$puesto_lista = $usuarioModel->obtenerPuestoEnLista($id_usuario);

// Detectar la página activa para resaltar el apartado correspondiente
$pagina_activa = basename($_SERVER['PHP_SELF'], ".php");
