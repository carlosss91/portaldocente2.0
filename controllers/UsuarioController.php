<?php
session_start();
require_once '../models/Usuario.php';
require_once '../models/Adjudicacion.php';

class UsuarioController {
    private $usuarioModel;

    public function __construct() {
        // Inicializa el modelo de usuario
        $this->usuarioModel = new Usuario();
    }

    // Método para obtener los datos del usuario autenticado
    public function obtenerUsuarioSesion() {
        // Verifica si la sesión del usuario está definida
        if (!isset($_SESSION['id_usuario'])) {
            return null; // Retorna null si no hay usuario en sesión
        }
        
        // Obtiene los datos del usuario desde la base de datos
        return $this->usuarioModel->obtenerUsuarioPorId($_SESSION['id_usuario']);
    }
}

// Verifica que la sesión esté activa y contiene el ID de usuario
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../views/login.php"); // Redirige al login si no está autenticado
    exit();
}

$adjudicacionModel = new Adjudicacion();
$adjudicaciones = $adjudicacionModel->obtenerAdjudicaciones($_SESSION["id_usuario"]);

$pagina_activa = basename($_SERVER['PHP_SELF'], ".php");
?>
